FROM php:8.1.0-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
   git \
   curl \
   libpng-dev \
   libonig-dev \
   libxml2-dev \
   zip \
   unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring gd

# Install Imagick and ghostscript
# RUN apt-get update && \
#     apt-get install -y libmagickwand-dev --no-install-recommends && \
#     apt-get install -y ghostscript

# RUN mkdir -p /usr/src/php/ext/imagick; \
#     curl -fsSL https://github.com/Imagick/imagick/archive/06116aa24b76edaf6b1693198f79e6c295eda8a9.tar.gz | tar xvz -C "/usr/src/php/ext/imagick" --strip 1; \
#     docker-php-ext-install imagick;

# Adjust Imagick policy
# ARG imagemagic_config=/etc/ImageMagick-6/policy.xml
# RUN if [ -f $imagemagic_config ] ; then sed -i 's/<policy domain="coder" rights="none" pattern="PDF" \/>/<policy domain="coder" rights="read|write" pattern="PDF" \/>/g' $imagemagic_config ; else echo did not see file $imagemagic_config ; fi

# Install composer
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node
# RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - 
# RUN apt-get install -y nodejs

# Set working directory
WORKDIR /var/www/html
COPY . .

# RUN composer install

CMD php artisan serve --host=0.0.0.0 --port=80