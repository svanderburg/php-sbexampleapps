{stdenv, lndir, pkgs, system}:
{literaturedb, usersdb}:

let
  literaturePkg = (import ../../../../src/literature {
    inherit pkgs system;
  }).override {
    removeComposerArtifacts = true;
  };
in
stdenv.mkDerivation {
  name = "literature";
  buildInputs = [ lndir ];
  buildCommand = ''
    mkdir -p $out/webapps/literature
    lndir ${literaturePkg} $out/webapps/literature
    # Remove bundled configuration file
    rm $out/webapps/literature/includes/config.php
    # Generate a new configuration file from inter-dependencies
    cat > $out/webapps/literature/includes/config.php <<EOF
    <?php
    \$config = array(
        /* Connection settings for the user database */
        "usersDbDsn" => "mysql:host=${usersdb.target.properties.hostname};dbname=${usersdb.name}",
        "usersDbUsername" => "${usersdb.mysqlUsername}",
        "usersDbPassword" => "${usersdb.mysqlPassword}",

        /* Connection settings for the application database */
        "dbDsn" => "mysql:host=${literaturedb.target.properties.hostname};dbname=${literaturedb.name}",
        "dbUsername" => "${literaturedb.mysqlUsername}",
        "dbPassword" => "${literaturedb.mysqlPassword}",
    );
    ?>
    EOF

    # Create fileset deployment descriptor
    ( for i in $out/webapps/literature/*
      do
          echo "symlink $i"
          echo "target literature"
      done
    ) > $out/.dysnomia-fileset

    echo "mkdir literature/pdf" >> $out/.dysnomia-fileset
  '';
}
