{stdenv, lndir, pkgs, system}:
{cmsgallerydb, usersdb}:

let
  cmsgalleryPkg = (import ../../../../src/cmsgallery {
    inherit pkgs system;
  }).override {
    removeComposerArtifacts = true;
  };
in
stdenv.mkDerivation {
  name = "cmsgallery";
  buildInputs = [ lndir ];
  buildCommand = ''
    mkdir -p $out/webapps/cmsgallery
    lndir ${cmsgalleryPkg} $out/webapps/cmsgallery
    # Remove bundled configuration file
    rm $out/webapps/cmsgallery/includes/config.php
    # Generate a new configuration file from inter-dependencies
    cat > $out/webapps/cmsgallery/includes/config.php <<EOF
    <?php
    \$config = array(
        /* Connection settings for the user database */
        "usersDbDsn" => "mysql:host=${usersdb.target.properties.hostname};dbname=${usersdb.name}",
        "usersDbUsername" => "${usersdb.mysqlUsername}",
        "usersDbPassword" => "${usersdb.mysqlPassword}",

        /* Connection settings for the application database */
        "dbDsn" => "mysql:host=${cmsgallerydb.target.properties.hostname};dbname=${cmsgallerydb.name}",
        "dbUsername" => "${cmsgallerydb.mysqlUsername}",
        "dbPassword" => "${cmsgallerydb.mysqlPassword}",
    );
    ?>
    EOF

    # Create fileset deployment descriptor
    ( for i in $out/webapps/cmsgallery/*
      do
          echo "symlink $i"
          echo "target cmsgallery"
      done
    ) > $out/.dysnomia-fileset

    echo "mkdir cmsgallery/gallery" >> $out/.dysnomia-fileset
  '';
}
