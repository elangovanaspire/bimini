I wrote some scripts to make cloning/managing latitude installs easier.

Scrimps
-------

* ./do_backup.sh - initiates a backup
* ./update_config.sh - updates configuration on cloned latitude vSphere machine based on config.cfg


Step by Step
------------

Once you've cloned a latitude install in vSphere

1. modify config.cfg to match the settings at the new site
2. run ./do_backup.sh to backup the current configuration
3. run the ./update_config.sh script to update the server's config with config.cfg's values

These scripts need to be run as root! :o!


Note
----

* You might want to chown config.cfg
