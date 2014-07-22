#!/bin/sh

# This file is a slightly modified version of that from
# https://github.com/purple52/librarian-puppet-vagrant
# and
# http://rshestakov.wordpress.com/2014/02/02/how-to-manage-public-and-private-puppet-modules-with-vagrant/

# Directory in which librarian-puppet should manage its modules directory
PUPPET_DIR=/vagrant/provision/puppet

# NB: librarian-puppet might need git installed. If it is not already installed
# in your basebox, this will manually install it at this point using apt or yum

$(which git > /dev/null 2>&1)
FOUND_GIT=$?
if [ "$FOUND_GIT" -ne '0' ]; then
  echo 'Attempting to install git.'
  $(which apt-get > /dev/null 2>&1)
  FOUND_APT=$?
  $(which yum > /dev/null 2>&1)
  FOUND_YUM=$?

  if [ "${FOUND_YUM}" -eq '0' ]; then
    yum -q -y makecache
    yum -q -y install git
    echo 'git installed.'
  elif [ "${FOUND_APT}" -eq '0' ]; then
    apt-get -q -y update
    apt-get -q -y install git
    echo 'git installed.'
  else
    echo 'No package installer available. You may need to install git manually.'
  fi
else
  echo 'git found.'
fi

if [ "$(gem list -i '^librarian-puppet$')" = "false" ]; then
  gem install librarian-puppet -v 1.0.4
  cd $PUPPET_DIR && librarian-puppet install --path modules
else
  cd $PUPPET_DIR && librarian-puppet update
fi

