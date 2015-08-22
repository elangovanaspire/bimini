#!/bin/bash
# desc: restore db backups
# by: sahal
# use: ./db_restore.sh /path/to/database.sql

# play safe
# http://www.davidpashley.com/articles/writing-robust-shell-scripts/
set -e
set -u

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

source "$DIR"/config.cfg
source "$DIR"/functions.sh

# specify a full path to the latitude sql table dump
sql_backup="${1:-unset}"

if [ "$sql_backup" == "unset" ]; then
  die "User did not specify a MySQL dump file."
fi

# check to see if backup exists
# check to see if its bzip'd
# if it is un-bzip it
if [ ! -e "$sql_backup" ]; then
  die "MySQL dump file does not exist."
else
  if file "$sql_backup" | grep bzip2 ; then
    tempfile=$(mktemp)
    bzip2 -cd "$sql_backup" > "$tempfile"
    sql_backup="$tempfile"
  fi
# quick check to verify that the file is a MySQL dump
  if ! grep "MySQL dump" "$sql_backup" ; then
    die "MySQL dump file type not found. Are you sure ""$1"" is a MySQL dump?"
  fi
fi

# choose db name to restore to
## prompt the user (default is in config.cfg)
echo -n "Enter a database name to restore to (default: ""$mysql_db""): "
read "db_name"
if [ -z "$db_name" ]; then
  db_name="$mysql_db"
fi

# if the db exists verify that the user really wants to drop tables
if run_mysql "show databases;"|grep "$db_name" ; then
  echo "it looks like that database already exists, would you like to continue? (Y/n)"
  read yn
  if [ -z "$yn" ]; then
    die "Please explicitly select either Y or n"
  fi
  if [ ! "$yn" == "Y" ]; then
    die "User did not select Y"
  fi
  # user would like to proceed -- make a backup
  "$DIR"/do_backup.sh
  # drop database
  run_mysql "drop database $db_name;"
fi

# create the db using the credentials supplied
run_mysql "create database $db_name;"

# import backup from sql file
run_mysql "source $sql_backup" "$db_name"

