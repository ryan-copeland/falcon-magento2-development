#!/usr/bin/env bash
set -e
trap '>&2 echo Error: Command \`$BASH_COMMAND\` on line $LINENO failed with exit code $?' ERR

# prepare for test suite
case $TEST_SUITE in
    api-functional)
        echo "Installing Magento"
        # create database and move db config into place
        mysql -uroot -e '
            SET @@global.sql_mode = NO_ENGINE_SUBSTITUTION;
            CREATE DATABASE magento_integration_tests;'

        echo "Prepare api-functional tests for running"
        cd dev/tests/api-functional

        sed -e "s?magento.url?${MAGENTO_HOST_NAME}?g" --in-place ./phpunit.xml
        mv config/install-config-mysql.travis.php.dist config/install-config-mysql.php

        echo "==> testsuite preparation complete"

        cd ../../..
        ;;
esac
