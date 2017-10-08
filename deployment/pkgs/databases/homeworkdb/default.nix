{stdenv}:

stdenv.mkDerivation {
  name = "homeworkdb";
  buildCommand = ''
    mkdir -p $out/mysql-databases
    cp ${../../../../src/homework/sql/createdb.sql} $out/mysql-databases/homework.sql
  '';
}
