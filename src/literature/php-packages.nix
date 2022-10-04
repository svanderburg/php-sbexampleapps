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
        name = "svanderburg-php-sbcrud-d934bee6133327b745fcfe574cff4b476f3d71c3";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "d934bee6133327b745fcfe574cff4b476f3d71c3";
        sha256 = "1mxr049wkn7pbsc8aagygysqmri1vxaka1kzgsbz3cgrbf64nq8v";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-a762a1e7e85e7c10b82ecf682694e9fccf37c033";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "a762a1e7e85e7c10b82ecf682694e9fccf37c033";
        sha256 = "0rbhslb717lv845qnhq6wfb2i9z5d3x286hhci7f2aw7n3barfjy";
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
