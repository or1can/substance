class { 'apache':
}

class { 'apache::mod::php':
}

apache::vhost { 'substance.vm':
  port => '80',
  docroot => '/vagrant/public',
  serveradmin => 'admin@substance.vm',
}
