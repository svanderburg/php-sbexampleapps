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
  name = "sbexampleapps-homework";
  src = composerEnv.filterSrc ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "GPL-3.0";
  };
}
