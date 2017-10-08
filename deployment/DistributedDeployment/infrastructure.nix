{
  "test1" = {
    properties = {
      hostname = "test1";
    };
    containers = {
      apache-webapplication = {
        documentRoot = "/var/www";
      };
    };
  };
  "test2" = {
    properties = {
      hostname = "test2";
    };
    containers = {
      mysql-database = {
        mysqlPort = "3306";
      };
    };
  };
}
