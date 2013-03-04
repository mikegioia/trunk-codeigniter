#!/bin/bash

. ../config

# run database update scripts and move run scripts to an archive directory
#
FILES="update/*.sql"
for f in $FILES
do
    echo "  ...running $f"
    if [ -z ${db_password} ] ; then
        mysql -h localhost -u ${db_username} ${db_database} < $f
    else
        mysql -h localhost -u ${db_username} -p${db_password} ${db_database} < $f
    fi
done
