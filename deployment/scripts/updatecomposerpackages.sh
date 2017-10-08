#!/bin/sh -e

# This script regenerates the Nix expressions for all composer packages

cd ../../src

cd layout
composer2nix --composer-env ../../deployment/pkgs/composer-env.nix
cd ..

for i in $(ls | grep -v layout)
do
    cd $i
    composer2nix --composer-env ../../deployment/pkgs/composer-env.nix --no-copy-composer-env
    cd ..
done
