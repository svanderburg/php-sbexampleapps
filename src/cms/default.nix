{pkgs ? import <nixpkgs> {
    inherit system;
  }, system ? builtins.currentSystem, noDev ? false}:

let
  composerEnv = import ../../deployment/pkgs/composer-env.nix {
    inherit (pkgs) stdenv lib writeTextFile fetchurl php unzip phpPackages;
  };
in
import ./php-packages.nix {
  inherit composerEnv noDev;
  inherit (pkgs) fetchurl fetchgit fetchhg fetchsvn;
}
