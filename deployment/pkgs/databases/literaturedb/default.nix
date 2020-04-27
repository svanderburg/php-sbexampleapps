{stdenv}:
{mysqlUsername, mysqlPassword}:

stdenv.mkDerivation rec {
  name = "literaturedb";
  buildCommand = ''
    mkdir -p $out/mysql-databases
    (
      echo "grant all on ${name}.* to '${mysqlUsername}'@'localhost' identified by '${mysqlPassword}';"
      echo "grant all on ${name}.* to '${mysqlUsername}'@'%' identified by '${mysqlPassword}';"
      cat ${../../../../src/literature/sql/createdb.sql}
      echo "flush privileges;"
    ) > $out/mysql-databases/literature.sql
  '';
}
