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
        name = "svanderburg-php-sbbiblio-642a8025de5731bc3a11a918891a0c38552d445b";
        url = "https://github.com/svanderburg/php-sbbiblio.git";
        rev = "642a8025de5731bc3a11a918891a0c38552d445b";
        sha256 = "0p7m2svhn0wwq0y211vrahwd1m6ar9h32fwhicnnsb65glkxpgh9";
      };
    };
    "svanderburg/php-sbcrud" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbcrud-09d15dd986a1739a35f9290a280af23b4e1d7b03";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "09d15dd986a1739a35f9290a280af23b4e1d7b03";
        sha256 = "06yp73sycf8yqqq4d8rbv7qvy8dj80llqzxz65cqpb7wkaj18pjl";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-2532ede5954d15690cf6e00b6280a3ecd01aff4e";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "2532ede5954d15690cf6e00b6280a3ecd01aff4e";
        sha256 = "0l30w61pvd3c42l28g81z3ninj5hcv8q8kci8yaw57bkgvwfr447";
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
  name = "sbexampleapps-literature";
  src = composerEnv.filterSrc ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "GPL-3.0";
  };
}
