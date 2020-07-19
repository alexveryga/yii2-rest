FROM nginx

ADD ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
ADD ./docker/nginx/fastcgi.conf /etc/nginx/fastcgi.conf
ADD ./docker/nginx/server.conf /etc/nginx/conf.d/default.conf

RUN mkdir -p /var/www/html/src/
VOLUME /var/www/html/src/
RUN chown -R nginx:www-data /var/www/html/src/
RUN chmod -R 0775 /var/www/html/src/
