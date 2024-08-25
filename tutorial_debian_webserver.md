# Tutorial to setup a local webserver on Debian

Based on https://wiki.debian.org/LaMp.

This procedure was tested on WSL2 for Windows with Debian 11 "Bullseye".

## MariaDB Database

### Install

```bash
sudo apt install mariadb-server mariadb-client
mysql_secure_installation  # config password for root and authorize testing configs
sudo service mariadb restart
```

### Configure mariadb
https://cs.kenyon.edu/index.php/install-mariadb-and-phpmyadmin/

```bash
# Set root password for mariabd:
sudo mysql
```

```SQL
ALTER USER 'root'@'localhost' IDENTIFIED BY 'newpassword';
flush privileges;
exit; 
```

```bash
# Try out mysql root login:
mysql -u root -p
```

### Create test database and user

```SQL
CREATE DATABASE testDB;
CREATE USER testUser@localhost IDENTIFIED BY '1234';
GRANT ALL PRIVILEGES ON testDB.* TO testUser@localhost;
FLUSH PRIVILEGES;
```

## Apache Server
```bash
sudo apt install apache2 apache2-doc
```

To make htaccess work: https://stackoverflow.com/questions/18740419/how-to-set-allowoverride-all

```bash
sudo vim /etc/apache2/apache2.conf
    # in "<Directory /var/www/>" , set "AllowOverride All"
```

Access to project directory

```bash
sudo ln -s /path/to/project /var/www/project
sudo vim /etc/apache2/sites-available/000-default.conf
    # Modify the line to have "DocumentRoot /var/www/projet"
sudo service apache2 reload
```

## PHP
```bash
apt install php php-mysql

sudo vim /etc/php/7.4/apache2/php.ini
    # uncomment "mysqli.allow_local_infile = On" https://stackoverflow.com/a/12375162
```

If there is an install error described in https://askubuntu.com/questions/1113653/libapache2-mod-php7-2-not-working

```bash
sudo apt remove --purge libapache2-mod-php7.4
sudo apt install libapache2-mod-php7.4
```

Finally:
```bash
sudo service apache2 restart
```

## phpMyAdmin

```bash
sudo apt install phpmyadmin
    # select NO when asking for dbconfig-deamon
sudo ln -s /etc/phpmyadmin/apache.conf /etc/apache2/conf-available/phpmyadmin.conf
sudo a2enconf phpmyadmin
sudo /etc/init.d/apache2 reload
```

It can be unecessary to do:

```bash
sudo ln -s /usr/share/phpmyadmin /var/www/
```

Now accessible on http://localhost/phpmyadmin/

## Access to INDI

1. Git clone the database in /to/path/indi.
2. Make a symbolink:

```bash
sudo ln -s /to/path/indi/src /var/www/indi
```

Now accessible on http://localhost/indi/