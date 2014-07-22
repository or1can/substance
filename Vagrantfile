# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  # Every Vagrant virtual environment requires a box to build off of.
  config.vm.box = "centos-65-x64-puppet"

  # The url from where the 'config.vm.box' box will be fetched if it
  # doesn't already exist on the user's system.
  config.vm.box_url = "http://puppet-vagrant-boxes.puppetlabs.com/centos-65-x64-virtualbox-puppet.box"

  config.vm.hostname = "substance.vm"

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  config.vm.network :private_network, ip: "192.168.33.101"

  # Provider-specific configuration so you can fine-tune various
  # backing providers for Vagrant. These expose provider-specific options.
  # Example for VirtualBox:
  #
  config.vm.provider :virtualbox do |vb|
    # Use VBoxManage to customize the VM. For example to change memory:
    vb.customize ["modifyvm", :id, "--memory", "512"]
  end
  
  # Before provisioning with Puppet, ensure that modules required by our Puppet
  # manifest are installed.
  config.vm.provision :shell, :path => "provision/shell/main.sh"
  
  # Enable provisioning with Puppet stand alone.  Puppet manifests
  # are contained in a directory path relative to this Vagrantfile.
  # You will need to create the manifests directory and a manifest in
  # the file centos6.5.pp in the manifests_path directory.
  #
  config.vm.provision :puppet do |puppet|
    puppet.manifests_path = 'provision/puppet/manifests'
    puppet.manifest_file = 'site.pp'
    puppet.module_path = [ 'provision/puppet/modules' ]
    # puppet.options = "--verbose --debug"
  end
end
