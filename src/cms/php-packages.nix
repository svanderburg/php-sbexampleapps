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
        name = "svanderburg-php-sbcrud-13d9c9fc224a0436e0dfc23159211a4e40a45321";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "13d9c9fc224a0436e0dfc23159211a4e40a45321";
        sha256 = "1c5kh3pgpqxvpr93h5az85w3wchbljqsaaigg2n1m6r2slzyrfpn";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-fedfaf3a04498befd1e63d08f8d24892e49ecc5d";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "fedfaf3a04498befd1e63d08f8d24892e49ecc5d";
        sha256 = "05ylqn3k1fqmc24gl4mpbg4sh0cz39zdbzkxnyvljmwsn39dw4gs";
      };
    };
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-704bccf00a0487d40290a66cd740279477c41c1f";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "704bccf00a0487d40290a66cd740279477c41c1f";
        sha256 = "1nn66xl9p20w0wg4371m6z81164km6j6f4q09sypxh7pg1fp3yl4";
      };
    };
    "svanderburg/php-sbgallery" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbgallery-3207f1044953d2b8b90779cacc2419495d608776";
        url = "https://github.com/svanderburg/php-sbgallery.git";
        rev = "3207f1044953d2b8b90779cacc2419495d608776";
        sha256 = "1d2q7vfizdk8vniqmwm5142v3vbr607za41gnm5w86syc9p8hvfx";
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
    "svanderburg/php-sbpagemanager" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbpagemanager-16e0e69ef166b0de5d090c9723a3e924eedf0e9e";
        url = "https://github.com/svanderburg/php-sbpagemanager.git";
        rev = "16e0e69ef166b0de5d090c9723a3e924eedf0e9e";
        sha256 = "17acf9i8gnm41a38lr2nm18f9pnir9b5w0wp5xj2q1712diyri81";
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
