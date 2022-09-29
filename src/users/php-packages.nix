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
        name = "svanderburg-php-sbcrud-e19d9e92d57847fa3ee6ed20f11f5a02e8bb4ca2";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "e19d9e92d57847fa3ee6ed20f11f5a02e8bb4ca2";
        sha256 = "0n8aqcdr48vi32pqpk7w9ikwvd6s90hgcbfnx4sf9fxicvfk9nqq";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-a303b7a75e6f5d4d62889eb99748a882dd34c073";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "a303b7a75e6f5d4d62889eb99748a882dd34c073";
        sha256 = "0xmpm1azif0cr0bnkgbw3jmqw462dcsa8k56bf6634ylc1xs2dnz";
      };
    };
    "svanderburg/php-sblayout" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sblayout-fcbba66c36af3ab258b50dc5f396b82d2170851c";
        url = "https://github.com/svanderburg/php-sblayout.git";
        rev = "fcbba66c36af3ab258b50dc5f396b82d2170851c";
        sha256 = "0wvjcgsx2g7c25krk8hckdgflczps7qsnw6wqj22glq7065c10d0";
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
