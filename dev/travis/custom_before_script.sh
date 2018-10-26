#!/usr/bin/env bash

# Copyright © Magento, Inc. All rights reserved.
# See COPYING.txt for license details.

set -e
trap '>&2 echo Error: Command \`$BASH_COMMAND\` on line $LINENO failed with exit code $?' ERR

# prepare for test suite
case $TEST_SUITE in
    integration)
        cd dev/tests/integration

        # create database and move db config into place
        mysql -uroot -e '
            SET @@global.sql_mode = NO_ENGINE_SUBSTITUTION;
            CREATE DATABASE magento_integration_tests;
        '
        mv etc/install-config-mysql.travis.php.dist etc/install-config-mysql.php

        cd ../../..
        ;;
    api-functional)
        cd dev/tests/api-functional

        # create database and move db config into place
        mysql -uroot -e '
            SET @@global.sql_mode = NO_ENGINE_SUBSTITUTION;
            CREATE DATABASE magento_functional_tests;
        '
        mv config/install-config-mysql.travis.php.dist config/install-config-mysql.php

        cd ../../..
        ;;
    static)
        cd dev/tests/static

        echo "==> preparing changed files list"
        changed_files_ce="$TRAVIS_BUILD_DIR/dev/tests/static/testsuite/Magento/Test/_files/changed_files_ce.txt"
        php get_github_changes.php \
            --output-file="$changed_files_ce" \
            --base-path="$TRAVIS_BUILD_DIR" \
            --repo='https://github.com/magento/magento2.git' \
            --branch="$TRAVIS_BRANCH"
        cat "$changed_files_ce" | sed 's/^/  + including /'

        cd ../../..
        ;;
    js)
        cp package.json.sample package.json
        cp Gruntfile.js.sample Gruntfile.js
        yarn

        if [[ $GRUNT_COMMAND != "static" ]]; then
            echo "Installing Magento"
            mysql -uroot -e 'CREATE DATABASE magento2;'
            php bin/magento setup:install -q \
                --admin-user="admin" \
                --admin-password="123123q" \
                --admin-email="admin@example.com" \
                --admin-firstname="John" \
                --admin-lastname="Doe"

            echo "Deploying Static Content"
            php bin/magento setup:static-content:deploy -f -q -j=2 \
                --no-css --no-less --no-images --no-fonts --no-misc --no-html-minify
        fi
        ;;
    functional)
        echo "Installing Magento"
        mysql -uroot -e 'CREATE DATABASE magento2;'
        php bin/magento setup:install -q \
            --language="en_US" \
            --timezone="UTC" \
            --currency="USD" \
            --base-url="http://${MAGENTO_HOST_NAME}/" \
            --admin-firstname="John" \
            --admin-lastname="Doe" \
            --backend-frontname="backend" \
            --admin-email="admin@example.com" \
            --admin-user="admin" \
            --use-rewrites=1 \
            --admin-use-security-key=0 \
            --admin-password="123123q"

        echo "Enabling production mode"
        php bin/magento deploy:mode:set production

        echo "Prepare functional tests for running"
        cd dev/tests/functional

        composer install && composer require se/selenium-server-standalone:2.53.1
        export DISPLAY=:1.0
        sh ./vendor/se/selenium-server-standalone/bin/selenium-server-standalone -port 4444 -host 127.0.0.1 \
            -Dwebdriver.firefox.bin=$(which firefox) -trustAllSSLCertificate &> ~/selenium.log &

        cp ./phpunit.xml.dist ./phpunit.xml
        sed -e "s?127.0.0.1?${MAGENTO_HOST_NAME}?g" --in-place ./phpunit.xml
        sed -e "s?basic?travis_acceptance?g" --in-place ./phpunit.xml
        cp ./.htaccess.sample ./.htaccess
        cd ./utils
        php -f mtf troubleshooting:check-all

        cd ../../..
        ;;
esac
