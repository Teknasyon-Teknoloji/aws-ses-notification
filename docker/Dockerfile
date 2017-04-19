FROM centos:latest
MAINTAINER Teknasyon <admin@teknasyon.com>

RUN yum install -y --nogpgcheck epel-release && \
    rpm -qa | grep -q remi-release || rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm && \
    sed -i "s|enabled=1|enabled=0|" /etc/yum/pluginconf.d/fastestmirror.conf && \
    yum --enablerepo=remi-php56,remi install -y --nogpgcheck \
    gcc \
    git-core \
    make \
    nginx \
    php \
    php-opcache \
    php-apc \
    php-devel \
    pcre-devel \
    php-pear \
    php-pecl-xdebug \
    php-mysqlnd \
    php-pecl-xhprof \
    php-pecl-memcached \
    php-xml \
    php-gd \
    php-mbstring \
    php-mcrypt \
    php-fpm \
    php-gearman \
    php-soap \
    php-json \
    php-intl \
    php-bcmath \
    wget \
    telnet \
    vim && \
    rm -rf /etc/nginx/conf.d/default.conf && \
    rm -rf /etc/nginx/conf.d/ssl.conf && \
    rm -rf /etc/nginx/conf.d/virtual.conf && \
    rm -rf /etc/nginx/nginx.conf && \
    curl -sS https://getcomposer.org/installer | php && \
    mv /composer.phar /usr/local/bin/composer && \
    TMPDIR=/tmp yum clean metadata && \
    TMPDIR=/tmp yum clean all
