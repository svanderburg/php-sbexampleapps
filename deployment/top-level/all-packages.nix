{system, pkgs}:

let
  callPackage = pkgs.lib.callPackageWith (pkgs // pkgs.xlibs // self);

  self = {
    ### Databases

    cmsdb = callPackage ../pkgs/databases/cmsdb { };

    cmsgallerydb = callPackage ../pkgs/databases/cmsgallerydb { };

    homeworkdb = callPackage ../pkgs/databases/homeworkdb { };

    literaturedb = callPackage ../pkgs/databases/literaturedb { };

    usersdb = callPackage ../pkgs/databases/usersdb { };

    portaldb = callPackage ../pkgs/databases/portaldb { };

    ### Web applications

    cms = callPackage ../pkgs/webapplications/cms { };

    cmsgallery = callPackage ../pkgs/webapplications/cmsgallery { };

    homework = callPackage ../pkgs/webapplications/homework { };

    literature = callPackage ../pkgs/webapplications/literature { };

    users = callPackage ../pkgs/webapplications/users { };

    portal = callPackage ../pkgs/webapplications/portal { };
  };
in
self
