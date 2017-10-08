{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-16222c117fe142d0fb2d4904ec28a86caa5ec756";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "16222c117fe142d0fb2d4904ec28a86caa5ec756";
        sha256 = "11zlbvhp4xivsl43b02h5jsnaz16pjc7gada74p862mznk8c2mvg";
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
  name = "sbexampleapps-auth";
  src = ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "GPL-3.0";
  };
}