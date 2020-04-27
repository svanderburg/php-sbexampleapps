{pkgs, ...}:

{
  services = {
    disnix = {
      enable = true;
    };

    openssh = {
      enable = true;
    };

    httpd = {
      enable = true;
      adminAddr = "admin@localhost";
      enablePHP = true;

      virtualHosts = {
        localhost = {
          documentRoot = "/var/www";
          extraConfig = ''
            DirectoryIndex index.php
          '';
        };
      };
    };
  };

  time.timeZone = "UTC";

  networking.firewall.allowedTCPPorts = [ 80 ];

  environment = {
    systemPackages = [
      pkgs.mc
      pkgs.subversion
      pkgs.lynx
    ];
  };
}
