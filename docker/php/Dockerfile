FROM php:7.3.2-fpm-stretch

ARG DEBUG=1
ARG ENABLE_XDEBUG=1
ARG LOCALES=en_US.UTF-8,de_DE.UTF-8

COPY php.ini /usr/local/etc/php/php.ini

# install locales
RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends locales \
	&& for LOCALE in $(echo ${LOCALES} | sed "s/,/ /g"); do sed -i "s/# ${LOCALE}/${LOCALE}/" /etc/locale.gen; done \
	&& dpkg-reconfigure --frontend=noninteractive locales

# install php extensions and dependencies | need mysqldump for publishment process
RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
	libcurl3-dev libfreetype6-dev libicu-dev libjpeg62-turbo-dev  libmcrypt-dev libxml2-dev autoconf g++ make libssl-dev mysql-client libzip-dev zip \
	&& pecl install mcrypt-1.0.2 && docker-php-ext-enable mcrypt \
	&& docker-php-ext-configure gd \
		--with-freetype-dir=/usr/include/ \
		--with-jpeg-dir=/usr/include/ \
		--with-png-dir=/usr/include/ \
	&& docker-php-ext-configure zip --with-libzip  \
	&& docker-php-ext-configure intl --enable-intl  \
	&& docker-php-ext-configure mbstring --enable-mbstring \
	&& docker-php-ext-configure opcache --enable-opcache \
	&& docker-php-ext-install bcmath curl gd gettext iconv intl json mbstring opcache pdo_mysql soap tokenizer xml zip pcntl \
	# remove dev packages and reinstall normal packages and cleanup afterwards in order to save image size
	&& apt-get purge -y --auto-remove libcurl3-dev libfreetype6-dev libicu-dev libjpeg62-turbo-dev libmcrypt-dev libxml2-dev autoconf g++ make libssl-dev \
	&& DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends libcurl3 libfreetype6 libicu57 libjpeg62-turbo libmcrypt4 libpng16-16 libxml2 \
	&& rm -rf /var/lib/apt/lists/*

# adjust php configuration
RUN if [ ${DEBUG} -eq 1 ]; then \
		sed -i "/error_reporting = .*/c\error_reporting = E_ALL" /usr/local/etc/php/php.ini \
		&& sed -i "/display_errors = .*/c\display_errors = On" /usr/local/etc/php/php.ini \
		&& sed -i "/display_startup_errors = .*/c\display_startup_errors = On" /usr/local/etc/php/php.ini \
		&& sed -i "/track_errors = .*/c\track_errors = On" /usr/local/etc/php/php.ini; \
	fi

# add xdebug
RUN if [ ${ENABLE_XDEBUG} -eq 1 ]; then \
		apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends autoconf g++ make \
		&& pecl install xdebug-2.7.0RC2 && docker-php-ext-enable xdebug \
		&& echo -e \
			"xdebug.default_enable=1\n"\
			"xdebug.remote_enable=1\n"\
			"xdebug.remote_connect_back=1\n"\
			"xdebug.remote_autostart=0\n"\
			"xdebug.remote_handler=dbgp\n"\
			"xdebug.remote_port=9000\n"\
			"xdebug.remote_mode=req\n"\
			"xdebug.idekey=PhpStorm\n" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
		&& apt-get purge -y --auto-remove autoconf g++ make \
		&& rm -rf /var/lib/apt/lists/*; \
	fi