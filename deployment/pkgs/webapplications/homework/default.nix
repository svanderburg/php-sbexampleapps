{stdenv, lndir, pkgs, system}:
{homeworkdb, usersdb}:

let
  homeworkPkg = (import ../../../../src/homework {
    inherit pkgs system;
  }).override {
    removeComposerArtifacts = true;
  };
in
stdenv.mkDerivation {
  name = "homework";
  buildInputs = [ lndir ];
  buildCommand = ''
    mkdir -p $out/webapps/homework
    lndir ${homeworkPkg} $out/webapps/homework
    # Remove bundled configuration file
    rm $out/webapps/homework/includes/config.php
    # Generate a new configuration file from inter-dependencies
    cat > $out/webapps/homework/includes/config.php <<EOF
    <?php
    \$config = array(
        /* Connection settings for the user database */
        "usersDbDsn" => "mysql:host=${usersdb.target.properties.hostname};dbname=${usersdb.name}",
        "usersDbUsername" => "${usersdb.target.container.mysqlUsername}",
        "usersDbPassword" => "${usersdb.target.container.mysqlPassword}",

        /* Connection settings for the application database */
        "dbDsn" => "mysql:host=${homeworkdb.target.properties.hostname};dbname=${homeworkdb.name}",
        "dbUsername" => "${homeworkdb.target.container.mysqlUsername}",
        "dbPassword" => "${homeworkdb.target.container.mysqlPassword}",
    );
    ?>
    EOF
  '';
}
