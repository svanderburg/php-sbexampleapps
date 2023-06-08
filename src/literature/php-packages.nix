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
        name = "svanderburg-php-sbbiblio-96fb7334ec9133e2b4c8ab91aa363a0610852d9b";
        url = "https://github.com/svanderburg/php-sbbiblio.git";
        rev = "96fb7334ec9133e2b4c8ab91aa363a0610852d9b";
        sha256 = "16l94gdb05afw77368ii2rw50x4nkhivshyfrk4llwd02q0247my";
      };
    };
    "svanderburg/php-sbcrud" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbcrud-2a03d319e074854fed4030b98672d35e746770d3";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "2a03d319e074854fed4030b98672d35e746770d3";
        sha256 = "0rixyjc1f36vv96r4hxx1xrpd2ai4pb4sz27vcyni8qfkjvdri31";
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
        name = "svanderburg-php-sblayout-c7b6f86ab9e95a9508833581f0e0e5dd107ae931";
        url = "https://github.com/svanderburg/php-sblayout.git";
        rev = "c7b6f86ab9e95a9508833581f0e0e5dd107ae931";
        sha256 = "0z6x1193ldnmk9wskwn8cx3cpbkgn0bsgscpxy7c5xi8hkk8xsn9";
      };
    };
  };
  devPackages = {};
in
composerEnv.buildPackage {
  inherit packages devPackages noDev;
  name = "sbexampleapps-literature";
  src = composerEnv.filterSrc ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "GPL-3.0";
  };
}
