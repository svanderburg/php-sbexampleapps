{stdenv}:

stdenv.mkDerivation {
  name = "portaldb";
  buildCommand = ''
    mkdir -p $out/mysql-databases
    cp ${../../../../src/portal/sql/createdb.sql} $out/mysql-databases/portal.sql
  '';
}
