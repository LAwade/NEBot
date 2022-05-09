FROM centos:7

ENV container docker
RUN yum -y update; yum clean all
RUN yum -y install systemd; yum clean all; \
    (cd /lib/systemd/system/sysinit.target.wants/; for i in *; do [ $i == systemd-tmpfiles-setup.service ] || rm -f $i; done); \
    rm -f /lib/systemd/system/multi-user.target.wants/*;\
    rm -f /etc/systemd/system/*.wants/*;\
    rm -f /lib/systemd/system/local-fs.target.wants/*; \
    rm -f /lib/systemd/system/sockets.target.wants/*udev*; \
    rm -f /lib/systemd/system/sockets.target.wants/*initctl*; \
    rm -f /lib/systemd/system/basic.target.wants/*;\
    rm -f /lib/systemd/system/anaconda.target.wants/*;

RUN yum -y install https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm
RUN yum -y install https://rpms.remirepo.net/enterprise/remi-release-7.rpm
RUN yum -y install yum-utils
RUN yum-config-manager --enable remi-php74
RUN yum update -y
RUN yum install php php-cli php-fpm php-mysqlnd php-zip php-devel \
    php-gd php-mcrypt php-mbstring php-curl php-xml \
    php-pear php-bcmath php-json php-pgsql php-pdo \
    wget unzip vim git cronie -y

COPY httpd.conf /etc/httpd/conf/
COPY crontab /etc/crontab
COPY process.service /etc/systemd/system/

WORKDIR /var/www/html/
RUN git clone https://lawade:senhagithub.com/LAwade/TSManager.git

WORKDIR /var/www/html/TSManager/
RUN git pull
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --filename=composer
RUN php -r "unlink('composer-setup.php');"
RUN php composer install

RUN chmod 755 /etc/systemd/system/process.service
RUN cp /var/www/html/TSManager/scripts/shell/process /etc/init.d

RUN systemctl enable process

EXPOSE 80

ENTRYPOINT [ "/usr/sbin/init" ]

CMD [ "/usr/sbin/httpd", "-D", "FOREGROUND" ]
