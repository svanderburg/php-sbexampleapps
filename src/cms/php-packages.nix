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
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-9527224daed54be775649e5d92517ca87bb10b92";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "9527224daed54be775649e5d92517ca87bb10b92";
        sha256 = "0gkmlwnlqdxw5pc34v6p9ksjwlglpi3j0jixb9n58q4a78k51rvg";
      };
    };
    "svanderburg/php-sbgallery" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbgallery-ea73317fc612e57734c5ae6d3757fd61f00f5125";
        url = "https://github.com/svanderburg/php-sbgallery.git";
        rev = "ea73317fc612e57734c5ae6d3757fd61f00f5125";
        sha256 = "000qjg8hwnd2xsij4j8q53gr6fy74p63xy86pp9bg5lbpq08sj8h";
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
    "svanderburg/php-sbpagemanager" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbpagemanager-f743c34922df60c3b1afc9a8a7e5daab6b0cbfb0";
        url = "https://github.com/svanderburg/php-sbpagemanager.git";
        rev = "f743c34922df60c3b1afc9a8a7e5daab6b0cbfb0";
        sha256 = "1p97kw28pz4k651447z118mrxhvv5c0nr8piqrfg5npfgp57vlna";
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
