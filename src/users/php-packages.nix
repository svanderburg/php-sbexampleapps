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
        name = "svanderburg-php-sbcrud-ad7c08b431811ec5cf4321e576993faeeaa089ec";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "ad7c08b431811ec5cf4321e576993faeeaa089ec";
        sha256 = "0g1m1f2fmjbfjcplgx2zkxiwnldj07anfgbrc1hamd78x4ir30g6";
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
        name = "svanderburg-php-sblayout-1fb91bccd9d60ba072be1bc1a8d41b9699e01245";
        url = "https://github.com/svanderburg/php-sblayout.git";
        rev = "1fb91bccd9d60ba072be1bc1a8d41b9699e01245";
        sha256 = "1j56srp4lii7bgf21vh6ypw6v5jm4kg3jcj06882g8qmjhsw8fdh";
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
