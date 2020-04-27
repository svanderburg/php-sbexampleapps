{distribution, invDistribution, pkgs, system}:

let
  customPkgs = import ../top-level/all-packages.nix {
    inherit pkgs system;
  };

  groups = {
    cms = "CMS";
    cmsgallery = "CMS gallery";
    homework = "Homework";
    literature = "Literature";
    portal = "Portal";
    users = "Users";
  };
in
rec {
  ### Databases

  cmsdb = rec {
    name = "cmsdb";
    mysqlUsername = "cmsdb";
    mysqlPassword = "cmsdb";
    pkg = customPkgs.cmsdb {
      inherit mysqlUsername mysqlPassword;
    };
    type = "mysql-database";
    group = groups.cms;
    description = "Database backend of CMS";
  };

  cmsgallerydb = rec {
    name = "cmsgallerydb";
    mysqlUsername = "cmsgallerydb";
    mysqlPassword = "cmsgallerydb";
    pkg = customPkgs.cmsgallerydb {
      inherit mysqlUsername mysqlPassword;
    };
    type = "mysql-database";
    group = groups.cmsgallery;
    description = "Database backend of CMS gallery";
  };

  homeworkdb = rec {
    name = "homeworkdb";
    mysqlUsername = "homeworkdb";
    mysqlPassword = "homeworkdb";
    pkg = customPkgs.homeworkdb {
      inherit mysqlUsername mysqlPassword;
    };
    type = "mysql-database";
    group = groups.homework;
    description = "Database backend of homework";
  };

  literaturedb = rec {
    name = "literaturedb";
    mysqlUsername = "literaturedb";
    mysqlPassword = "literaturedb";
    pkg = customPkgs.literaturedb {
      inherit mysqlUsername mysqlPassword;
    };
    type = "mysql-database";
    group = groups.literature;
    description = "Database backend of literature";
  };

  usersdb = rec {
    name = "usersdb";
    mysqlUsername = "usersdb";
    mysqlPassword = "usersdb";
    pkg = customPkgs.usersdb {
      inherit mysqlUsername mysqlPassword;
    };
    type = "mysql-database";
    group = groups.users;
    description = "Database backend of users";
  };

  portaldb = rec {
    name = "portaldb";
    mysqlUsername = "portaldb";
    mysqlPassword = "portaldb";
    pkg = customPkgs.portaldb {
      inherit mysqlUsername mysqlPassword;
    };
    type = "mysql-database";
    deployState = true;
    group = groups.portal;
    description = "Database backend of portal";
  };

  ### Web applications

  cms = {
    name = "cms";
    pkg = customPkgs.cms;
    dependsOn = {
      inherit usersdb cmsdb;
    };
    type = "apache-webapplication";
    appName = "CMS";
    group = groups.cms;
    description = "Front-end of the CMS";
  };

  cmsgallery = {
    name = "cmsgallery";
    pkg = customPkgs.cmsgallery;
    dependsOn = {
      inherit usersdb cmsgallerydb;
    };
    type = "apache-webapplication";
    appName = "CMS Gallery";
    group = groups.cmsgallery;
    description = "Front-end of the CMS gallery";
  };

  homework = {
    name = "homework";
    pkg = customPkgs.homework;
    dependsOn = {
      inherit usersdb homeworkdb;
    };
    type = "apache-webapplication";
    appName = "Homework";
    group = groups.homework;
    description = "Front-end of homework";
  };

  literature = {
    name = "literature";
    pkg = customPkgs.literature;
    dependsOn = {
      inherit usersdb literaturedb;
    };
    type = "apache-webapplication";
    appName = "Literature";
    group = groups.literature;
    description = "Front-end of literature";
  };

  users = {
    name = "users";
    pkg = customPkgs.users;
    dependsOn = {
      inherit usersdb;
    };
    type = "apache-webapplication";
    appName = "Users";
    group = groups.users;
    description = "Front-end of users";
  };

  portal = {
    name = "portal";
    pkg = customPkgs.portal;
    dependsOn = {
      inherit usersdb portaldb;
    };
    connectsTo = {
      inherit cms cmsgallery homework literature users;
    };
    type = "apache-webapplication";
    appName = "Portal";
    group = groups.portal;
    description = "Front-end of portal";
  };
}
