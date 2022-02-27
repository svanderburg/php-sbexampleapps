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
        name = "svanderburg-php-sbcrud-e28e621b0e01c8adea385ac18ceaa3ee68e67d54";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "e28e621b0e01c8adea385ac18ceaa3ee68e67d54";
        sha256 = "1czrmr4rvgsda2gjn12pixbxixnfd0mp41904h77z67s1q1qj3hk";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-e316850043401eb9f6edb64df4b1fb63db690691";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "e316850043401eb9f6edb64df4b1fb63db690691";
        sha256 = "1b96k0xknvk2mdl303h8q41dkr6adbl7plipx81qwbhhg3wzpzgp";
      };
    };
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-50d8eb0c2a34b432804ba73294e90381d4e187e4";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "50d8eb0c2a34b432804ba73294e90381d4e187e4";
        sha256 = "1qizmaqjasmqsyrdhyzwzwvxlgh4pnk6kq1h85kr2lsliai93b4g";
      };
    };
    "svanderburg/php-sbgallery" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbgallery-b441b5ca9623b847653c943dc14b8542fea82cb5";
        url = "https://github.com/svanderburg/php-sbgallery.git";
        rev = "b441b5ca9623b847653c943dc14b8542fea82cb5";
        sha256 = "0vlmy93wxmixn954385s2175azv81m72q0d59n2a3yg2cnflm51m";
      };
    };
    "svanderburg/php-sblayout" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sblayout-7b56951ac05dc73e57499c68680dd140d944e711";
        url = "https://github.com/svanderburg/php-sblayout.git";
        rev = "7b56951ac05dc73e57499c68680dd140d944e711";
        sha256 = "0wh554fdnnkb73xn798nj2yn73yr4f456hp9bbgi238gmaw5xwns";
      };
    };
    "svanderburg/php-sbpagemanager" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbpagemanager-3c0b24deca1550542f70c8c3ddf7832481757fa2";
        url = "https://github.com/svanderburg/php-sbpagemanager.git";
        rev = "3c0b24deca1550542f70c8c3ddf7832481757fa2";
        sha256 = "0mra4khlmcs5jf5yzkjl09bii77v36zaqwdcfqk92hgsr52gvsaa";
      };
    };
  };
  devPackages = {};
in
composerEnv.buildPackage {
  inherit packages devPackages noDev;
  name = "sbexampleapps-cms";
  src = ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "GPL-3.0";
  };
}
