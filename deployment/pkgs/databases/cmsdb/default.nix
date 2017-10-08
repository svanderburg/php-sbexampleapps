{stdenv}:

stdenv.mkDerivation {
  name = "cmsdb";
  buildCommand = ''
    mkdir -p $out/mysql-databases
    cp ${../../../../src/cms/sql/createdb.sql} $out/mysql-databases/cms.sql
  '';
}
