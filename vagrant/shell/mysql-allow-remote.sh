#!/bin/sh

sed -i.bak 's/^bind-address/#bind-address/' /etc/mysql/mysql.conf.d/mysqld.cnf
mysql -uroot -proot -e "GRANT ALL PRIVILEGES ON *.* TO root@'%' IDENTIFIED BY 'root';FLUSH PRIVILEGES;"
systemctl restart mysql.service
