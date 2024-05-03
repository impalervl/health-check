#!/bin/bash

mysql -uroot -p$MYSQL_PASSWORD -e "CREATE DATABASE $TEST_DB CHARACTER SET utf8 COLLATE utf8_general_ci";
mysql -uroot -p$MYSQL_PASSWORD -e "CREATE USER $TEST_USER@'%' IDENTIFIED BY '$MYSQL_PASSWORD'";
mysql -uroot -p$MYSQL_PASSWORD -e "GRANT CREATE, DROP, SELECT, INSERT, UPDATE ON $TEST_DB.* TO '$TEST_USER'@'%'";
