#!/usr/bin/env bash
set -e
trap '>&2 echo Error: Command \`$BASH_COMMAND\` on line $LINENO failed with exit code $?' ERR

# prepare for test suite
case $TEST_SUITE in
    api-functional)
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

        echo "Prepare api-functional tests for running"
        cd dev/tests/api-functional

        sed -e "s?magento.url?${MAGENTO_HOST_NAME}?g" --in-place ./phpunit.xml
        mv config/install-config-mysql.travis.php.dist config/install-config-mysql.php

        echo "Enabling production mode"
        php bin/magento deploy:mode:set production

        echo "==> testsuite preparation complete"
        ;;
esac
