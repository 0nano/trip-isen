# Based on the work of https://github.com/TrafeX/docker-php-nginx
ARG ALPINE_VERSION=3.20
FROM alpine:${ALPINE_VERSION}

# Setup document root
WORKDIR /var/www/html

# Install packages and remove default server definition
RUN apk add --no-cache \
  curl \
  nginx \
  php83 \
  php83-ctype \
  php83-curl \
  php83-dom \
  php83-fileinfo \
  php83-fpm \
  php83-gd \
  php83-intl \
  php83-mbstring \
  php83-pdo_pgsql \
  php83-opcache \
  php83-openssl \
  php83-phar \
  php83-session \
  php83-tokenizer \
  php83-xml \
  php83-xmlreader \
  php83-xmlwriter \
  postgresql \
  supervisor

# Configure nginx - http
COPY docker/nginx.conf /etc/nginx/nginx.conf
# Configure nginx - default server
COPY docker/conf.d /etc/nginx/conf.d/

# Configure PHP-FPM
ENV PHP_INI_DIR=/etc/php83
COPY docker/fpm-pool.conf ${PHP_INI_DIR}/php-fpm.d/www.conf
COPY docker/php.ini ${PHP_INI_DIR}/conf.d/custom.ini

# Configure PostgreSQL
RUN mkdir -p /var/lib/postgresql/data /run/postgresql

# Configure supervisord
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create a directory for the SSRF challenge
RUN mkdir /run/ssrf

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody:nobody /var/www/html /run /var/lib/nginx /var/log/nginx /var/lib/postgresql/ /run/postgresql /run/ssrf

# Switch to use a non-root user from here on
USER nobody

# Add application and clear unwanted files
COPY --chown=nobody . /var/www/html/
RUN rm -rf docker/ .git/ .github/ .gitignore LICENSE README.md Dockerfile

# Run postgresql initdb and create database
RUN initdb -D /var/lib/postgresql/data --username="nobody" --pwfile=<(echo "nobody") --auth=trust

# Create the flag for the RCE challenge
RUN echo "flag{rce_is_not_a_good_idea}" > /var/www/html/flag.txt

# Create the flag for the SSRF challenge
COPY uploads/flag.mp4 /run/ssrf/
RUN rm /var/www/html/uploads/flag.mp4

# Expose the port nginx is reachable on
EXPOSE 8080

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
