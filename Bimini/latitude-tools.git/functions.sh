
function die {
  echo "$1"
  exit 1
}

function run_mysql {
#requires 2 inputs
# $1 = command to run
# $2 = db name (optional)

if [ -z "${2:-}"  ]; then
  $mysql_path --host="$mysql_host" --user="$mysql_user" --password="$mysql_pass" -e "${1}"
else
  $mysql_path --host="$mysql_host" --user="$mysql_user" --password="$mysql_pass" "${2}" -e "${1}"
fi

}


