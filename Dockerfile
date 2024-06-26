FROM php:8.3-apache-bookworm

ENV COMPOSER_ALLOW_SUPERUSER=1

ARG OPENSIGNATURE_VERSION=698c8608288ce0d0348d43e51fd406469d747993
# ARG GID=1000
# ARG UID=1000

RUN set -eux; \
    apt-get update; \
    apt-get install -qy \
    unzip \
    zip \
    wget \
    libicu-dev \
    libcurl4-nss-dev \
    qrencode \
    openssl \
    locales \
    openjdk-17-jre-headless \
    jq\
    incron\
    coreutils\
    ghostscript\
    qpdf\
    redis-tools\
    && apt-get clean -y \
    && rm -rf /var/lib/{apt,dpkg,cache,log,tmp}/*


# RUN addgroup --gid "$GID" nonroot
# RUN adduser --uid "$UID" --gid "$GID" --disabled-password --gecos "" nonroot
# RUN echo 'nonroot ALL=(ALL) NOPASSWD: ALL' >> /etc/sudoers

RUN sed -i -e 's/# en_US.UTF-8 UTF-8/en_US.UTF-8 UTF-8/' /etc/locale.gen && \
    sed -i -e 's/# fr_FR.UTF-8 UTF-8/fr_FR.UTF-8 UTF-8/' /etc/locale.gen && \
    dpkg-reconfigure --frontend=noninteractive locales && \
    update-locale LANG=fr_FR.UTF-8

ENV LC_ALL fr_FR.UTF-8
ENV LANGUAGE fr_FR:en_US:fr
ENV LANG fr_FR.UTF-8



# PHP Extensions
RUN docker-php-ext-configure intl
RUN pecl install redis
RUN docker-php-ext-install gettext curl intl
COPY conf/php.ini /usr/local/etc/php/conf.d/opensignature.ini


EXPOSE 80
WORKDIR /app

RUN mkdir opensignature

# Apache,
RUN wget https://gitlab.girondenumerique.fr/GirNumOpenSource/opensignature/-/archive/${OPENSIGNATURE_VERSION}/opensignature-${OPENSIGNATURE_VERSION}.zip \
    && unzip opensignature-${OPENSIGNATURE_VERSION}.zip -d /tmp \
    && mv /tmp/opensignature-${OPENSIGNATURE_VERSION}/.htaccess /app/opensignature \
    && mv /tmp/opensignature-${OPENSIGNATURE_VERSION}/* /app/opensignature \
    && rm -rf /tmp/* opensignature-${OPENSIGNATURE_VERSION}.zip


# Copy Script
COPY script/send_mail.sh /app/opensignature/app/script/melsnd
RUN chmod +x /app/opensignature/app/script/melsnd

#Copy class debug SMS mail
COPY script/class.Sms_Mail.php /app/opensignature/app/src/class.Sms_Mail.php

# COPY Custom img
COPY themes /app/opensignature/pub/

# CHOWN WWW-DATA
RUN chown -R www-data /app/opensignature \
    && chown www-data /app/opensignature/*

RUN chmod +x /app/opensignature/app/script/initincrontab
RUN echo "www-data" > /etc/incron.allow

RUN ln -s /usr/local/bin/php /usr/bin/php
USER www-data
RUN /app/opensignature/app/script/initincrontab

USER root

# Purge package

RUN set -eux; \
    apt-get -y purge wget unzip

COPY conf/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY conf/apache.conf /etc/apache2/conf-available/opensignature.conf
RUN a2enmod rewrite remoteip && \
    a2enconf opensignature

COPY --chmod=760 docker-entrypoint.sh /app/docker-entrypoint.sh
ENTRYPOINT ["/app/docker-entrypoint.sh"]