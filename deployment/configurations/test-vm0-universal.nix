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

    mysql = {
      enable = true;
      rootPassword = ./mysqlpw;
      initialScript = ./mysqlscript;
      package = pkgs.mysql;
    };
  };

  time.timeZone = "UTC";

  networking.firewall.allowedTCPPorts = [ 80 3306 ];

  environment = {
    systemPackages = [
      pkgs.mc
      pkgs.subversion
      pkgs.lynx
    ];
  };
}
