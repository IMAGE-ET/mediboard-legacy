#!/bin/sh

BASH_PATH=$(dirname $BASH_SOURCE)
. $BASH_PATH/utils.sh

########
# Configures groups and mods for Mediboard directories
########

announce_script "Mediboard directories groups and mods"

APACHEGROUP=$1

# Change group to allow Appache to access files as group
chgrp -R $APACHEGROUP

# Give write access to apache for some directories
chmod g+w lib/ tmp/ files/ includes/ modules/*/templates_c/


