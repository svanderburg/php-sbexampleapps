{stdenv}:
{mysqlUsername, mysqlPassword}:

stdenv.mkDerivation rec {
  name = "cmsgallerydb";
  buildCommand = ''
    mkdir -p $out/mysql-databases
    (
      echo "grant all on ${name}.* to '${mysqlUsername}'@'localhost' identified by '${mysqlPassword}';"
      echo "grant all on ${name}.* to '${mysqlUsername}'@'%' identified by '${mysqlPassword}';"
      cat ${../../../../src/cmsgallery/sql/createdb.sql}
      echo "flush privileges;"
    ) > $out/mysql-databases/cmsgallery.sql
  '';
}
