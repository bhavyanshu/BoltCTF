FROM nginx:1.10

ADD vhost.conf /etc/nginx/conf.d/default.conf

# Setup permissions for nginx
RUN chgrp -R www-data storage bootstrap/cache
RUN chmod -R ug+rwx storage bootstrap/cache
