# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"
VAGRANT_BOX = "ubuntu/bionic64"
VAGRANT_BOX_MEMORY = 1024
VIRTUAL_BOX_NAME = "piggybank"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = VAGRANT_BOX
  config.vm.box_check_update = true
  config.vm.hostname = VIRTUAL_BOX_NAME
  config.vm.network :private_network, ip: "192.168.254.10"
  config.hostsupdater.aliases = ["root.piggybank", "app.piggybank"]

  config.vm.synced_folder ".", "/var/www/piggybank", type: "nfs"

  # ensure box name
  config.vm.define VIRTUAL_BOX_NAME do |t|
  end

  # configure virtual box
  config.vm.provider :virtualbox do |vb|
    vb.name = VIRTUAL_BOX_NAME
    vb.linked_clone = true
    vb.memory = "1024"
    vb.cpus = "2"
  end

  # provision vm os
  config.vm.provision :shell, inline: <<-SHELL
    export DEBIAN_FRONTEND=noninteractive
    apt-get update
    apt-get -y upgrade
    apt-get install -y apache2
    a2enmod rewrite
    sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
    sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'
    apt-get -y install mysql-server
    apt-get install unzip
    apt-get install -y php7.2
    apt-get install -y php-mbstring php-mysql php-xdebug php-xml php-zip
  SHELL

  # copy files required to provision software
  # (as recommended by https://www.vagrantup.com/docs/provisioning/file.html)
  config.vm.provision :file, source: "vagrant/files", destination: "/tmp/provision"

  # provision php
  config.vm.provision :shell, inline: <<-SHELL
    mv /tmp/provision/php/xdebug.ini /etc/php/7.2/mods-available/xdebug.ini
  SHELL

  # provision apache
  config.vm.provision :shell, inline: <<-SHELL
    mv /tmp/provision/www/index.php /var/www
    mv /tmp/provision/apache2/001-root.piggybank.conf /etc/apache2/sites-available
    mv /tmp/provision/apache2/002-app.piggybank.conf /etc/apache2/sites-available
    a2dissite 000-default.conf
    a2ensite 001-root.piggybank.conf 002-app.piggybank.conf
    systemctl reload apache2.service
  SHELL

  # provision development tools
  config.vm.provision :shell, path: "vagrant/shell/install-composer.sh"
  config.vm.provision :shell, path: "vagrant/shell/mysql-allow-remote.sh"

  # cleanup
  config.vm.provision :shell, inline: "rm -rf /tmp/provision"
end
