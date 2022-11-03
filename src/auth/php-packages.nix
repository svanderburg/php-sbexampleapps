{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-fedfaf3a04498befd1e63d08f8d24892e49ecc5d";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "fedfaf3a04498befd1e63d08f8d24892e49ecc5d";
        sha256 = "05ylqn3k1fqmc24gl4mpbg4sh0cz39zdbzkxnyvljmwsn39dw4gs";
      };
    };
    "svanderburg/php-sblayout" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sblayout-db265a949119dc000ae0a2d0db93e54127e82fde";
        url = "https://github.com/svanderburg/php-sblayout.git";
        rev = "db265a949119dc000ae0a2d0db93e54127e82fde";
        sha256 = "189z0wxmryxsm90qhvwl3dawbq90rdjydjp2hsffxd0nnzsx0hm6";
      };
    };
  };
  devPackages = {};
in
composerEnv.buildPackage {
  inherit packages devPackages noDev;
  name = "sbexampleapps-auth";
  src = composerEnv.filterSrc ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "GPL-3.0";
  };
}
