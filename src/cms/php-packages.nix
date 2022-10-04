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
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-81dd24557596513132ab236a50096c424ba8a838";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "81dd24557596513132ab236a50096c424ba8a838";
        sha256 = "095p528i4kjm95fd69a8vx5ghd5qsyvzslsgcbhv7316dda9xa54";
      };
    };
    "svanderburg/php-sbgallery" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbgallery-9105f320fd174aa6eb82c91f8ff3c63f098f187c";
        url = "https://github.com/svanderburg/php-sbgallery.git";
        rev = "9105f320fd174aa6eb82c91f8ff3c63f098f187c";
        sha256 = "1rr6caz0xxn0rpd89z9b4lijp0jlzaavjrnqz22k47vz6nwmp4ys";
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
    "svanderburg/php-sbpagemanager" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbpagemanager-f558b504d1423840a9ed455f7e4049c4f2e6cc50";
        url = "https://github.com/svanderburg/php-sbpagemanager.git";
        rev = "f558b504d1423840a9ed455f7e4049c4f2e6cc50";
        sha256 = "0swf0n2wmkpmw2fhm9vj2ng8rrfqm6r0w7k5phqcdbj2v489s39s";
      };
    };
  };
  devPackages = {};
in
composerEnv.buildPackage {
  inherit packages devPackages noDev;
  name = "sbexampleapps-cms";
  src = composerEnv.filterSrc ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "GPL-3.0";
  };
}
