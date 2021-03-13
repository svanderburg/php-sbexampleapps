{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-6452dfc654e8a58c867ffe59cd06d734b6ea9286";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "6452dfc654e8a58c867ffe59cd06d734b6ea9286";
        sha256 = "0cp1l56n1lpp3hg5iy7dxbz63mnjspwycmyipfpkn3ipj5wgdj3l";
      };
    };
    "svanderburg/php-sblayout" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sblayout-44493847ebf631b52cf81607e2eb227c65390acb";
        url = "https://github.com/svanderburg/php-sblayout.git";
        rev = "44493847ebf631b52cf81607e2eb227c65390acb";
        sha256 = "0rj5hf85fck4bjpyn645k082nricrm13fpq7s84nahwb61m1lk44";
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
