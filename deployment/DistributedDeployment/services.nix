{distribution, invDistribution, pkgs, system}:

let
  customPkgs = import ../top-level/all-packages.nix {
    inherit pkgs system;
  };
in
rec {
  ### Databases

  cmsdb = {
    name = "cmsdb";
    pkg = customPkgs.cmsdb;
    type = "mysql-database";
  };

  cmsgallerydb = {
    name = "cmsgallerydb";
    pkg = customPkgs.cmsgallerydb;
    type = "mysql-database";
  };

  homeworkdb = {
    name = "homeworkdb";
    pkg = customPkgs.homeworkdb;
    type = "mysql-database";
  };

  literaturedb = {
    name = "literaturedb";
    pkg = customPkgs.literaturedb;
    type = "mysql-database";
  };

  usersdb = {
    name = "usersdb";
    pkg = customPkgs.usersdb;
    type = "mysql-database";
  };

  portaldb = {
    name = "portaldb";
    pkg = customPkgs.portaldb;
    type = "mysql-database";
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
  };

  cmsgallery = {
    name = "cmsgallery";
    pkg = customPkgs.cmsgallery;
    dependsOn = {
      inherit usersdb cmsgallerydb;
    };
    type = "apache-webapplication";
    appName = "CMS Gallery";
  };

  homework = {
    name = "homework";
    pkg = customPkgs.homework;
    dependsOn = {
      inherit usersdb homeworkdb;
    };
    type = "apache-webapplication";
    appName = "Homework";
  };

  literature = {
    name = "literature";
    pkg = customPkgs.literature;
    dependsOn = {
      inherit usersdb literaturedb;
    };
    type = "apache-webapplication";
    appName = "Literature";
  };

  users = {
    name = "users";
    pkg = customPkgs.users;
    dependsOn = {
      inherit usersdb;
    };
    type = "apache-webapplication";
    appName = "Users";
  };

  portal = {
    name = "portal";
    pkg = customPkgs.portal;
    dependsOn = {
      inherit usersdb portaldb;
      inherit cms cmsgallery homework literature users;
    };
    type = "apache-webapplication";
    appName = "Portal";
  };
}
