#!/bin/bash
# by sahal

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# include the config file
source $DIR/config.cfg

# play safe
# http://www.davidpashley.com/articles/writing-robust-shell-scripts/
set -e
set -u

function die {
#$1 = message
  /bin/echo "$1"
  exit 1
}

function do_check {

## checks
# check if root

if [ "$(id -u)" != "0" ]; then
   die "This script must be run as root"
fi
# check if ubuntu

if [ ! -e "/etc/debian_version" ]; then
  die "This is not a Debian based distribution."
else
  if ! lsb_release -a | grep "Ubuntu" ; then
    die "Failed to find Ubuntu..."
  fi
fi

# check for existance of files

if [ ! -e /etc/hosts ]; then
  die "no /etc/hosts file?!"
fi

if [ ! -e /etc/hostname ]; then
  die "no /etc/hostname file?!"
fi

if [ ! -e /etc/apache2/conf-available/fqdn.conf ]; then
  die "no fqdn.conf file found in /etc/apache2/conf-available/, please touch it."
fi

# check variables in config.cfg

if [ -z "$ip" ]; then
  die "no \$ip set in config.cfg"
fi

if [ -z "$hostname" ]; then
  die "no \$hostname set in config.cfg"
fi

if [ -z "$mysql_pass" ]; then
  die "no \$mysql_pass set in config.cfg"
fi

}

function do_config_update {
  # update hosts file - /etc/hosts

  #echo "$ip"" ""${hostname%%\.*}"" ""$hostname" >> /etc/hosts
  /bin/sed -i "s/^.*latitude.*$/$ip\ latitude\ ${hostname%%\.*}\ $hostname/g" /etc/hosts

  # update hostname
  /bin/hostname "${hostname%%\.*}"
  # hostname filename - /etc/hostname
  /bin/echo "${hostname%%\.*}" > /etc/hostname

  # update apache configuration
  # updating fqdn file - /etc/apache2/config-available/fqdn.conf
  /bin/echo "ServerName ""$hostname" > /etc/apache2/conf-available/fqdn.conf
  /usr/sbin/a2enconf fqdn

}


function run_mysql {

  $mysql_path --host="$mysql_host" --user="$mysql_user" --password="$mysql_pass" "$mysql_db" -e "$1"

}

function do_update_database {

  # update mysql database
  run_mysql "UPDATE wp_options SET option_value='http://$hostname' WHERE option_name = 'siteurl';"
#  run_mysql "UPDATE wp_options SET option_value=http://""$hostname"" WHERE option_name=home;"
  run_mysql "UPDATE wp_options SET option_value='http://$hostname' WHERE option_name = 'home';"
 
}

function do_restart_services {

  # restart apache2
  /usr/sbin/service apache2 restart
  # restart networking
  /sbin/ifdown "$interface" && /sbin/ifup "$interface"

}

do_check
do_config_update
do_update_database
do_restart_services
