{stdenv}:

stdenv.mkDerivation {
  name = "literaturedb";
  buildCommand = ''
    mkdir -p $out/mysql-databases
    cp ${../../../../src/literature/sql/createdb.sql} $out/mysql-databases/literature.sql
  '';
}
