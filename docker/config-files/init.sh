#!/bin/bash
/usr/sbin/php-fpm -R
/usr/sbin/nginx -g "daemon off;"
