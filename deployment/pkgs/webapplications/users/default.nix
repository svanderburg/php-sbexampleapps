{stdenv, lndir, pkgs, system}:
{usersdb}:

let
  usersPkg = import ../../../../src/users {
    inherit pkgs system;
  };
in
stdenv.mkDerivation {
  name = "users";
  buildInputs = [ lndir ];
  buildCommand = ''
    mkdir -p $out/webapps/users
    lndir ${usersPkg} $out/webapps/users
    # Remove bundled configuration file
    rm $out/webapps/users/includes/config.php
    # Generate a new configuration file from inter-dependencies
    cat > $out/webapps/users/includes/config.php <<EOF
    <?php
    \$config = array(
        "dbDsn" => "mysql:host=${usersdb.target.properties.hostname};dbname=${usersdb.name}",
        "dbUsername" => "${usersdb.target.container.mysqlUsername}",
        "dbPassword" => "${usersdb.target.container.mysqlPassword}",
    );
    ?>
    EOF
  '';
}
