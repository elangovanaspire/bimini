#!/bin/bash
# b** where? backup script
# by sahal ansari sansari@percipia.com

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# include the config file
source $DIR/config.cfg
mkdir "$backup_dir"

# dumps the mysql db in to a multi-insert .sql file.  
#   this is useful if/when we want to edit the sql in a text editor
#   causes the import to take longer but the benefits are great
$mysql_dump --extended-insert=FALSE --complete-insert=TRUE --add-drop-table --host="$mysql_host" --user="$mysql_user" --password="$mysql_pass" "$mysql_db" | bzip2 -c > "$backup_dir"/latitude.sql.bz2
# wordpress backup
tar cvvzf "$backup_dir"/wp_html.tar.gz /var/www/html/*
# apache configuration backup
tar cvvzf "$backup_dir"/apache_conf.tar.gz /etc/apache2/*
