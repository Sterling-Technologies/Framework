# Openovate's Framework
=========

# Pre Requisites

1. PHP 5.3+
2. Apache 2
3. Composer

# Install

1. Checkout with git via `git clone https://github.com/Openovate/Framework.git`
2. Point your VirtualHost to load files from `[YOUR_OPENOVATE_FRAMEWORK_DIR]/repo/Front/public`
3. `cd` into that directory and run `php [PATH_TO_COMPOSER_PHAR]/composer.phar install`
4. Open write access to config via `sudo chmod -R 777 [YOUR_OPENOVATE_FRAMEWORK_DIR]/config`
5. Open up your VirtualHost domain with your browser to test.