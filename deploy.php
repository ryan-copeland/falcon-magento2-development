<?php

namespace Deployer;

require 'vendor/jalogut/magento2-deployer-plus/recipe/magento_2_2_5.php';

// Use timestamp for release name
set('release_name', function () {
    return date('YmdHis');
});

// Magento dir into the project root. Set "." if magento is installed on project root
set('magento_dir', '.');
// [Optional] Git repository. Only needed if not using build + artifact strategy
set('repository', '');
// Space separated list of languages for static-content:deploy
set('languages', 'en_US');

// OPcache configuration
/*task('cache:clear:opcache', 'sudo systemctl reload php-fpm');
after('cache:clear', 'cache:clear:opcache');*/

set('dev_modules', [
    'Magento_TestModule1',
    'Magento_TestModule2',
    'Magento_TestModule3',
    'Magento_TestModule4',
    'Magento_TestModule5',
    'Magento_TestModuleDefaultHydrator',
    'Magento_TestModuleDirectoryZipCodes',
    'Magento_TestModuleFakePaymentMethod',
    'Magento_TestModuleIntegrationFromConfig',
    'Magento_TestModuleJoinDirectives',
    'Magento_TestModuleMSC',
    'Magento_TestModuleSample'
]);

// Build host
localhost('build');

// Remote Servers
host('release')
    ->hostname('142.93.102.62')
    ->user('deployer')
    ->set('deploy_path', '/var/www/html')
    ->identityFile('~/.ssh/magento-deploy-key')
    ->stage('dev')
    ->roles('master');

