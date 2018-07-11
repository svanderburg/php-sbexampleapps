{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "sbexampleapps/auth" = {
      targetDir = "";
      src = ../auth;
    };
    "sbexampleapps/layout" = {
      targetDir = "";
      src = ../layout;
    };
    "svanderburg/php-sbcrud" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbcrud-a455c8db84b8304e06ba9092e7fb63fd0f05f083";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "a455c8db84b8304e06ba9092e7fb63fd0f05f083";
        sha256 = "1r4mzs1agv4d4a5kclar43ifqq5bwyybzdxm6hq6yml1a0w6p7sh";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-6452dfc654e8a58c867ffe59cd06d734b6ea9286";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "6452dfc654e8a58c867ffe59cd06d734b6ea9286";
        sha256 = "0cp1l56n1lpp3hg5iy7dxbz63mnjspwycmyipfpkn3ipj5wgdj3l";
      };
    };
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-7093f6fa7976c9066b0ae6dd17cd49633a0d4627";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "7093f6fa7976c9066b0ae6dd17cd49633a0d4627";
        sha256 = "0kmgsrcqwyh8dqvank4i4hqy7652rw1y2185wgkfq93cj9wkdvgm";
      };
    };
    "svanderburg/php-sbgallery" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbgallery-804ee70c3f4e4c9796f675819a7e2a1d10db5a9d";
        url = "https://github.com/svanderburg/php-sbgallery.git";
        rev = "804ee70c3f4e4c9796f675819a7e2a1d10db5a9d";
        sha256 = "0v1jj50qg65zr3f5nl1jz4p665a082cpn31x5rhg4p4xg2klnyma";
      };
    };
    "svanderburg/php-sblayout" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sblayout-44493847ebf631b52cf81607e2eb227c65390acb";
        url = "https://github.com/svanderburg/php-sblayout.git";
        rev = "44493847ebf631b52cf81607e2eb227c65390acb";
        sha256 = "0rj5hf85fck4bjpyn645k082nricrm13fpq7s84nahwb61m1lk44";
      };
    };
  };
  devPackages = {};
in
composerEnv.buildPackage {
  inherit packages devPackages noDev;
  name = "sbexampleapps-cmsgallery";
  src = ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "GPL-3.0";
  };
}