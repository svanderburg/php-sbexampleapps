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
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-ff082a75714cd7fe3468479806e23b43cb7c7c68";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "ff082a75714cd7fe3468479806e23b43cb7c7c68";
        sha256 = "0hkncp2fs5zax0kl0dxl3b9smb41ngb0fpv3jndq1c4p5ayf8a02";
      };
    };
    "svanderburg/php-sbgallery" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbgallery-62f0b5c260c3eeea857acd4fe187c50a6f0b3ee9";
        url = "https://github.com/svanderburg/php-sbgallery.git";
        rev = "62f0b5c260c3eeea857acd4fe187c50a6f0b3ee9";
        sha256 = "0902kc2ppidc54alllx4nii3wpnbi27l5y976dfb8bqd7kb5b4r7";
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
    "svanderburg/php-sbpagemanager" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbpagemanager-7b05db9e457593eb31242685c1ffff5bdaee0844";
        url = "https://github.com/svanderburg/php-sbpagemanager.git";
        rev = "7b05db9e457593eb31242685c1ffff5bdaee0844";
        sha256 = "0zx0lc187byrwaa5awfza73gnkaivyza60v3ykhq1d13367f1f7f";
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
