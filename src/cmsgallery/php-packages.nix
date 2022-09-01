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
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-d4af4608337e9d44108e084675dfef6a24ad0bc0";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "d4af4608337e9d44108e084675dfef6a24ad0bc0";
        sha256 = "1sqxgdv9sll49dsrf9cin1wlf8zfwha54crijimfnbqs12lx1i34";
      };
    };
    "svanderburg/php-sbgallery" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbgallery-428718e5d71a7d9e3cfaf038e352f3ff735ec31b";
        url = "https://github.com/svanderburg/php-sbgallery.git";
        rev = "428718e5d71a7d9e3cfaf038e352f3ff735ec31b";
        sha256 = "1lhnmzjavqfchjn1liab4cviwa0xw3s8h47fg25y2w3c1wwcpn6h";
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
  name = "sbexampleapps-cmsgallery";
  src = composerEnv.filterSrc ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "GPL-3.0";
  };
}
