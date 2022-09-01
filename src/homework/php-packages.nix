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
        name = "svanderburg-php-sbcrud-e9e5e12df31152346f017f696bdfe35bf74e9d41";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "e9e5e12df31152346f017f696bdfe35bf74e9d41";
        sha256 = "0ip5dmymaap4l7zgl4a6z92g6ysmvv7nw9swqgw0svmwkx3ysiwx";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-72a189a2d2d6e220b518654dba2009f3e20f5daa";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "72a189a2d2d6e220b518654dba2009f3e20f5daa";
        sha256 = "1x9x1hd1p6c3iq39kwwcgjxjsp9hnwplql80m67kim3rs2y73pjb";
      };
    };
    "svanderburg/php-sblayout" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sblayout-fcbba66c36af3ab258b50dc5f396b82d2170851c";
        url = "https://github.com/svanderburg/php-sblayout.git";
        rev = "fcbba66c36af3ab258b50dc5f396b82d2170851c";
        sha256 = "0wvjcgsx2g7c25krk8hckdgflczps7qsnw6wqj22glq7065c10d0";
      };
    };
  };
  devPackages = {};
in
composerEnv.buildPackage {
  inherit packages devPackages noDev;
  name = "sbexampleapps-homework";
  src = composerEnv.filterSrc ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "GPL-3.0";
  };
}
