{pkgs, ...}:

{
  services = {
    disnix = {
      enable = true;
    };

    openssh = {
      enable = true;
    };

    mysql = {
      enable = true;
      package = pkgs.mysql;
    };
  };

  dysnomia.enableAuthentication = true;

  networking.firewall.allowedTCPPorts = [ 80 3306 ];

  environment = {
    systemPackages = [
      pkgs.mc
      pkgs.subversion
      pkgs.lynx
    ];
  };
}
