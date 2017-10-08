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
      documentRoot = "/var/www";
      adminAddr = "admin@localhost";
      enablePHP = true;
      extraConfig = ''
        DirectoryIndex index.php
      '';
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
