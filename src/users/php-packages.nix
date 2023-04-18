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
        name = "svanderburg-php-sbcrud-7a5162f12847dd5b1e96703668543d2b7f1b5024";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "7a5162f12847dd5b1e96703668543d2b7f1b5024";
        sha256 = "0562r9zy3lxm99b1dw2npski6j4b3992g5ziqm4vacxx45vgdqc7";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-f61559eaca14e0795960e3c5cb42a2fec1c5efdd";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "f61559eaca14e0795960e3c5cb42a2fec1c5efdd";
        sha256 = "1krdcz4kzxfq2lxzwmrrly5k918gnvj0qbvj0dwndglr4l1hr7z0";
      };
    };
    "svanderburg/php-sblayout" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sblayout-56b8668eae093d47c9ab8f6fab0522524cb0a89e";
        url = "https://github.com/svanderburg/php-sblayout.git";
        rev = "56b8668eae093d47c9ab8f6fab0522524cb0a89e";
        sha256 = "0c32hfayb70r5nq3bz38vqkqgy39qcn116vydvx37dkgvqnalvjq";
      };
    };
  };
  devPackages = {};
in
composerEnv.buildPackage {
  inherit packages devPackages noDev;
  name = "sbexampleapps-users";
  src = composerEnv.filterSrc ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "GPL-3.0";
  };
}
