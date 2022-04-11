FROM php:7.4-fpm

RUN apt-get update -y
RUN apt-get install git unzip libpq-dev -y --no-install-recommends openssl libssl-dev libcurl4-openssl-dev \
    && docker-php-ext-install pgsql pdo_pgsql \
    && pecl install mongodb \
    && cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini \
    && echo "extension=mongodb.so" >> /usr/local/etc/php/php.ini \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* 

WORKDIR /var/www/html/

RUN git clone -b develop https://lawade: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@github.com/LAwade/NEBot.git

WORKDIR /var/www/html/NEBot/
RUN git pull
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php

RUN mv composer-setup.php /bin/composer-setup.php
RUN ln -s /bin/composer-setup.php composer
RUN php -r "unlink('composer-setup.php');"

RUN php composer.phar install

ENTRYPOINT [ "php" ]

CMD [ "app/Console/consumer.php" ]
