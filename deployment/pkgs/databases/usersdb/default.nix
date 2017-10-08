{stdenv}:

stdenv.mkDerivation {
  name = "usersdb";
  buildCommand = ''
    mkdir -p $out/mysql-databases
    cp ${../../../../src/users/sql/createdb.sql} $out/mysql-databases/users.sql
  '';
}
