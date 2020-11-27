{pkgs, ...}:

{
  dysnomia.enableLegacyModules = false;

  services = {
    openssh = {
      enable = true;
    };

    disnix = {
      enable = true;
    };
  };

  networking.firewall.enable = false;

  environment = {
    systemPackages = [
      pkgs.mc
      pkgs.lynx
    ];
  };
}
