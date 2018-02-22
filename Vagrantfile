# -*- mode: ruby -*-
# vi: set ft=ruby :

#for Vagrant >1.2.x
Vagrant.configure("2") do |config|
    config.vm.provision :shell, :inline => "echo Welcome to Campr VM"

    config.vm.define :dev do |_config|
        _config.vm.box = "trisoft/php71apache24mysql57redis-201706261411"

        _config.vm.hostname = "campr"

        _config.vm.synced_folder ".", "/var/www", :nfs => true

        _config.vm.provision "file", source: "~/.ssh/id_rsa", destination: "~/.ssh/id_rsa"
        _config.vm.provision "file", source: "~/.gitconfig", destination: "~/.gitconfig"
        _config.vm.provision :shell, :inline => "sudo service apache2 start; sudo service php7.1-fpm start", run: "always"

        _config.vm.provider :virtualbox do |vb, override|
            vb.customize ["modifyvm", :id, "--memory", 4096]
            vb.customize ["modifyvm", :id, "--cpus", "4"]
            override.vm.box_url = "http://labs.io.trisoft.ro/box/php71apache24mysql57redis-201706261411.box"
            override.vm.network :private_network, ip: "192.168.33.147"
        end
    end
end
