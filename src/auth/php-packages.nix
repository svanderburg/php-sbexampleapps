{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
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
  name = "sbexampleapps-auth";
  src = ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "GPL-3.0";
  };
}
