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
    "svanderburg/php-sbbiblio" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbbiblio-27ed6f5e5c41b75f0af459edf01662b816209a93";
        url = "https://github.com/svanderburg/php-sbbiblio.git";
        rev = "27ed6f5e5c41b75f0af459edf01662b816209a93";
        sha256 = "18y9xgbfyl7ssl0zyfkibr8dc6axmmw8iayg27d3hc6vc51cfyn6";
      };
    };
    "svanderburg/php-sbcrud" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbcrud-63b864007e9c8fe2cea180483fb08ceb5bd5ea7d";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "63b864007e9c8fe2cea180483fb08ceb5bd5ea7d";
        sha256 = "08vbalm60gh6nz819f8z88dlsmhv84203fpmli6bc1b3qii8pcd5";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-502b053ea3475f4e562f8f7ede48904ee65f8e7e";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "502b053ea3475f4e562f8f7ede48904ee65f8e7e";
        sha256 = "02q1qqry2cpqs7yqghi26l2ggw7dmwsb0xmd9f3m49i36b8w4mmn";
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
  name = "sbexampleapps-literature";
  src = composerEnv.filterSrc ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "GPL-3.0";
  };
}
