#!/bin/bash
#
# Starts and stops Xvfb and Selenium and run all phpunit and phpspec tests
#

# Http and https proxies can lead selenium tests to fail, so we remove them before launching all others commands
# and we reinstate then afterwards
HTTPSPROXY=$https_proxy
HTTPPROXY=$http_proxy

unset https_proxy ;
unset http_proxy ;
rm -rf app/cache/test/* ;
app/console cache:warmup --env=test ;
chmod -R 0777 app/cache ;
bin/stop_selenium.sh ;
bin/start_selenium.sh &&
php -d memory_limit=128M bin/behat ;
php -d memory_limit=128M bin/phpspec ;
bin/stop_selenium.sh ;
export https_proxy=$HTTPSPROXY ;
export http_proxy=$HTTPPROXY
