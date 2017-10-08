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
        name = "svanderburg-php-sbcrud-f2f6f5bac0cb3796667fa1e57ddc71321238806b";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "f2f6f5bac0cb3796667fa1e57ddc71321238806b";
        sha256 = "1scsm3765ggga7ql5bgcsg32qc39xcka4fgdhq8bc09mkp0jvq77";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-0f6422e13778bbb58fc95b75d3dc3f1ae972613d";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "0f6422e13778bbb58fc95b75d3dc3f1ae972613d";
        sha256 = "13jmh9qwqidrqlknniibbkmwn92rrbqbrajqmf4lyb131bl78b0q";
      };
    };
    "svanderburg/php-sblayout" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sblayout-32894d6b1f8280a97a0878d0615454dfbf77efc9";
        url = "https://github.com/svanderburg/php-sblayout.git";
        rev = "32894d6b1f8280a97a0878d0615454dfbf77efc9";
        sha256 = "1ia2mnkx2p6sfxgqfs4faz8ccajni5iax3hi8rxnk1ckxpkyl74p";
      };
    };
  };
  devPackages = {};
in
composerEnv.buildPackage {
  inherit packages devPackages noDev;
  name = "sbexampleapps-homework";
  src = ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "GPL-3.0";
  };
}