{stdenv}:

stdenv.mkDerivation {
  name = "cmsgallerydb";
  buildCommand = ''
    mkdir -p $out/mysql-databases
    cp ${../../../../src/cmsgallery/sql/createdb.sql} $out/mysql-databases/cmsgallery.sql
  '';
}
