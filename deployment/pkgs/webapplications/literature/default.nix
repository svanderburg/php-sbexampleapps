{stdenv, lndir, pkgs, system}:
{literaturedb, usersdb}:

let
  literaturePkg = import ../../../../src/literature {
    inherit pkgs system;
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
        "usersDbUsername" => "${usersdb.target.container.mysqlUsername}",
        "usersDbPassword" => "${usersdb.target.container.mysqlPassword}",

        /* Connection settings for the application database */
        "dbDsn" => "mysql:host=${literaturedb.target.properties.hostname};dbname=${literaturedb.name}",
        "dbUsername" => "${literaturedb.target.container.mysqlUsername}",
        "dbPassword" => "${literaturedb.target.container.mysqlPassword}",
    );
    ?>
    EOF
  '';
}
