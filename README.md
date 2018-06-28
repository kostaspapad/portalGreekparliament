## About Greek parliament Portal

## Local environment setup
Clone repository

    git clone https://gitlab.com/kostaspapad/portalGreekparliament.git

Install php7.2

Install mysql

### Install composer

    $ cd ~
    $ curl -sS https://getcomposer.org/installer -o composer-setup.php

Next, run a short PHP script to verify that the installer matches the SHA-384 hash for the latest installer found on the [Composer Public Keys / Signatures page](https://composer.github.io/pubkeys.html). You will need to make sure that you substitute the latest hash for the highlighted value below:

    $ php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

Must output:

    Installer verified

To install composer globally, use the following:

    $ sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

This will download and install Composer as a system-wide command named composer, under /usr/local/bin. The output should look like this:

    All settings correct for using Composer
    Downloading 1.1.1...

    Composer successfully installed to: /usr/local/bin/composer
    Use it: php /usr/local/bin/composer

To test your installation, run:

    composer

Now use can go to the project folder and run:

    composer install

This will install all the necessary packages

### asto gia tin wra
install npm, node

### Configure mysql
Create user "greekparliament" and grant permissions (Use same pass as production).

    CREATE USER 'greekparliament'@'localhost' IDENTIFIED BY '******';

    GRANT ALL PRIVILEGES ON * . * TO 'greeekparliament'@'localhost';

    FLUSH PRIVILEGES;

Create portal, parliament db use utf8_unicode_ci encoding.

    CREATE DATABASE parliament CHARACTER SET utf8 COLLATE utf8_unicode_ci;
    CREATE DATABASE portal CHARACTER SET utf8 COLLATE utf8_unicode_ci;

Create the table that will contain the scraper data:

    CREATE TABLE `scraper_data` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `conference_date` date DEFAULT NULL,
        `time_period` varchar(255) DEFAULT NULL,
        `session` varchar(255) NOT NULL,
        `conference_indicator` varchar(255) NOT NULL,
        `video_link` varchar(255) DEFAULT NULL,
        `pdf_loc` varchar(255) DEFAULT NULL,
        `pdf_name` text,
        `doc_location` varchar(255) NOT NULL,
        `doc_name` text,
        `date_of_crawl` date DEFAULT NULL,
        `morning_conference` int(11) NOT NULL,
        `noon_conference` int(11) NOT NULL,
        `downloaded` tinyint(1) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

Copy some sample data from the remote parliament.scraper_data to your local database.

## Laravel configuration
Create the .env if not exists and configure it in the root project.

Two database connections must be specified in the .env file.

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=portal
    DB_USERNAME=greekparliament
    DB_PASSWORD=******

    DB_CONNECTION=mysql_scraper
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE_SCRAPER=parliament
    DB_USERNAME_SCRAPER=greekparliament
    DB_PASSWORD_SCRAPER=******

Generate application key:

    php artisan key:generate 

Migrate:

    composer dump-autoload
    php artisan migrate

Run seed:

    php artisan db:seed

Done