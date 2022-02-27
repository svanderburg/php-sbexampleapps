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
        name = "svanderburg-php-sbbiblio-6812e3084050cd07785fb5a3f1ad558c79859a9e";
        url = "https://github.com/svanderburg/php-sbbiblio.git";
        rev = "6812e3084050cd07785fb5a3f1ad558c79859a9e";
        sha256 = "0845d3q13b5c37y6jxsx81bzk5yjlx3f8lp71a6bgv5qkkxaas0k";
      };
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
    "svanderburg/php-sblayout" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sblayout-7b56951ac05dc73e57499c68680dd140d944e711";
        url = "https://github.com/svanderburg/php-sblayout.git";
        rev = "7b56951ac05dc73e57499c68680dd140d944e711";
        sha256 = "0wh554fdnnkb73xn798nj2yn73yr4f456hp9bbgi238gmaw5xwns";
      };
    };
  };
  devPackages = {};
in
composerEnv.buildPackage {
  inherit packages devPackages noDev;
  name = "sbexampleapps-literature";
  src = ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "GPL-3.0";
  };
}
