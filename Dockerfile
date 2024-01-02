FROM php:7.4-apache

ENV COMPOSER_ALLOW_SUPERUSER=1

ARG OPENSIGNATURE_VERSION=d736b55effd8fcf8137818603329ca522fe29313

# git, unzip & zip are for composer
RUN apt-get update -qq && \
    apt-get install -qy \
    unzip \
    wget \
    libicu-dev \
    locales \
    qrencode \
    openssl \
    libcurl4-nss-dev \
    libssl-dev \
    openjdk-11-jre-headless \
    && apt-get clean -y \
    && rm -rf /var/lib/{apt,dpkg,cache,log,tmp}/*


RUN sed -i -e 's/# en_US.UTF-8 UTF-8/en_US.UTF-8 UTF-8/' /etc/locale.gen && \
    sed -i -e 's/# fr_FR.UTF-8 UTF-8/fr_FR.UTF-8 UTF-8/' /etc/locale.gen && \
    dpkg-reconfigure --frontend=noninteractive locales && \
    update-locale LANG=fr_FR.UTF-8
ENV LC_ALL fr_FR.UTF-8
ENV LANGUAGE fr_FR:en_US:fr
ENV LANG fr_FR.UTF-8

# Téléchargement de mailsend depuis la source et installation
RUN wget https://github.com/muquit/mailsend/archive/master.zip && \
    unzip master.zip

RUN cd mailsend-master && \
    ./configure --with-openssl=/usr

RUN cd mailsend-master && \
    make install

RUN rm -rf master.zip mailsend-master

# Téléchargement et installation de incron
RUN wget https://github.com/ar-/incron/archive/refs/heads/master.zip && \
    unzip master.zip && \
    cd incron-master && \
    make -j8 && \
    make install && \
    cd .. && \
    rm -rf incron-master master.zip


#MODULE APACHE
RUN a2enmod dav
RUN a2enmod dav_fs


# PHP Extensions
RUN docker-php-ext-configure intl
RUN pecl install redis
RUN docker-php-ext-install json gettext curl intl
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

# CHOWN WWW-DATA
RUN chown -R www-data /app/opensignature \
    && chown www-data /app/opensignature/*

RUN chmod +x /app/opensignature/app/script/initincrontab

USER www-data
RUN incrontab -l
RUN  /app/opensignature/app/script/initincrontab


USER root


COPY conf/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY conf/apache.conf /etc/apache2/conf-available/opensignature.conf
RUN a2enmod rewrite remoteip && \
    a2enconf opensignature


CMD ["apache2-foreground"]