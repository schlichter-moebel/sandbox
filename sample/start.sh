#!/bin/bash
hostname=localhost
port=3306
username=root
password=''
database=simple
master_database=simple

echo "<?php
\$config = array(
    'hostname' => '$hostname',
    'port' => $port,
    'username' => '$username',
    'password' => '$password',
    'database' => '$database',
);
" > config.php

echo "#Liquibase.properties

driver: com.mysql.jdbc.Driver
url: jdbc:mysql://$hostname:$port/$database
username: $username
password: $password
" > liquibase.properties

echo "#!/bin/sh

for file in ./database/*.xml;
do
	fname=\$(basename \"\$file\")
done;

liquibase --changeLogFile=./database/\$fname migrate" > .git/hooks/post-merge


echo "#!/bin/sh

for file in ./database/*.xml;
do
	fname=\$(basename \"\$file\")
done;

IFS=\".\"
set -- \$fname

liquibase --changeLogFile=./database/db.changelog-0.\$((\$3+1)).0.xml --url=jdbc:mysql://localhost:3306/$master_database --username=$username diffChangeLog  --referenceUrl=jdbc:mysql://localhost:3306/$database --referenceUsername=$username

row=0
grep changeSet database/db.changelog-0.\$((\$3+1)).0.xml

if [ \$? -ne 0 ];
    then rm -f database/db.changelog-0.\$((\$3+1)).0.xml
    else
        if [ \"\$fname\" == 'db.baseline-schema.xml' ];
            then diff database/db.baseline-schema.xml database/db.changelog-0.\$((\$3+1)).0.xml > test.txt
            else diff database/db.changelog-0.\$3.0.xml database/db.changelog-0.\$((\$3+1)).0.xml > test.txt
        fi

        while read line
        do
            if [[ \"\$line\" != *changeSet* ]] && [[ \"\$line\" != *---* ]] && [[ \"\$line\" != *3c3* ]];
                then row=1
            fi
        done < test.txt

        if [ \"\$row\" = 1 ];
            then
                git add ./database/db.changelog-0.\$((\$3+1)).0.xml
				liquibase --changeLogFile=./database/db.changelog-0.\$((\$3+1)).0.xml changelogSync
            else
                rm -f database/db.changelog-0.\$((\$3+1)).0.xml
        fi

        rm -f test.txt
fi

exit 0" > .git/hooks/pre-commit

liquibase --changeLogFile=database/db.baseline-schema.xml update
liquibase --changeLogFile=database/db.baseline-data.xml update