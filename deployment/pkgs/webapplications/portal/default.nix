{stdenv, lndir, pkgs, system}:
{portaldb, usersdb, ...}@interDeps:

let
  portalPkg = (import ../../../../src/portal {
    inherit pkgs system;
  }).override {
    removeComposerArtifacts = true;
  };

  # Filter all web applications out of the inter dependencies
  webappInterDeps = stdenv.lib.filter (serviceName:
    let
      service = builtins.getAttr serviceName interDeps;
    in
    service.type == "apache-webapplication"
  ) (builtins.attrNames interDeps);
in
stdenv.mkDerivation {
  name = "portal";
  buildInputs = [ lndir ];
  buildCommand = ''
    mkdir -p $out/webapps/portal
    lndir ${portalPkg} $out/webapps/portal

    # Remove bundled configuration file
    rm $out/webapps/portal/includes/config.php
    # Generate a new configuration file from inter-dependencies
    cat > $out/webapps/portal/includes/config.php <<EOF
    <?php
    use SBLayout\Model\Page\ExternalPage;

    \$config = array(
        /* Connection settings for the user database */
        "usersDbDsn" => "mysql:host=${usersdb.target.properties.hostname};dbname=${usersdb.name}",
        "usersDbUsername" => "${usersdb.mysqlUsername}",
        "usersDbPassword" => "${usersdb.mysqlPassword}",

        /* Connection settings for the application database */
        "dbDsn" => "mysql:host=${portaldb.target.properties.hostname};dbname=${portaldb.name}",
        "dbUsername" => "${portaldb.mysqlUsername}",
        "dbPassword" => "${portaldb.mysqlPassword}",

        /* External application configuration */
        "externalApps" => array(
            ${stdenv.lib.concatMapStrings (serviceName:
              let
                service = builtins.getAttr serviceName interDeps;
              in
              ''
                "${service.name}" => new ExternalPage("${service.appName}", "http://${service.target.properties.hostname}/${service.name}"),
              ''
            ) webappInterDeps}
        )
    );
    ?>
    EOF

    # Create fileset deployment descriptor
    ( for i in $out/webapps/portal/*
      do
          echo "symlink $i"
          echo "target ."
      done
    ) > $out/.dysnomia-fileset

    echo "mkdir gallery" >> $out/.dysnomia-fileset
  '';
}
