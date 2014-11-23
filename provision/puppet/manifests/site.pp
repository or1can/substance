class { 'apache':
}

class { 'apache::mod::php':
}

apache::vhost { 'substance.vm':
  port => '80',
  docroot => '/vagrant/public',
  serveradmin => 'admin@substance.vm',
}

class { '::mysql::server':
}

mysql::db { 'mydb':
  user => 'myuser',
  password => 'mypass',
  host => 'localhost',
  grant => ['ALL'],
}

package { 'php-devel':
}

package { 'php-mbstring':
}

package { 'php-mysql':
}

package { 'php-pdo':
}

package { 'php-pear':
}

package { 'php-xml':
}

exec { 'pear-xdebug':
  command => '/usr/bin/pecl install xdebug',
  creates => '/usr/lib64/php/modules/xdebug.so',
  require => Package['php-devel', 'php-pear'],
}

file { 'php-xdebug-ini':
  path => '/etc/php.d/xdebug.ini',
  ensure => 'present',
  mode => 0644,
  content => 'zend_extension="/usr/lib64/php/modules/xdebug.so"',
}
