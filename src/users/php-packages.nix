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
  name = "sbexampleapps-users";
  src = ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "GPL-3.0";
  };
}