{stdenv, lndir, pkgs, system}:
{cmsdb, usersdb}:

let
  cmsPkg = import ../../../../src/cms {
    inherit pkgs system;
  };
in
stdenv.mkDerivation {
  name = "cms";
  buildInputs = [ lndir ];
  buildCommand = ''
    mkdir -p $out/webapps/cms
    lndir ${cmsPkg} $out/webapps/cms
    # Remove bundled configuration file
    rm $out/webapps/cms/includes/config.php
    # Generate a new configuration file from inter-dependencies
    cat > $out/webapps/cms/includes/config.php <<EOF
    <?php
    \$config = array(
        /* Connection settings for the user database */
        "usersDbDsn" => "mysql:host=${usersdb.target.properties.hostname};dbname=${usersdb.name}",
        "usersDbUsername" => "${usersdb.target.container.mysqlUsername}",
        "usersDbPassword" => "${usersdb.target.container.mysqlPassword}",

        /* Connection settings for the application database */
        "dbDsn" => "mysql:host=${cmsdb.target.properties.hostname};dbname=${cmsdb.name}",
        "dbUsername" => "${cmsdb.target.container.mysqlUsername}",
        "dbPassword" => "${cmsdb.target.container.mysqlPassword}",
    );
    ?>
    EOF
  '';
}
