#!/bin/bash
#
# Starts and stops Xvfb and Selenium and run all phpunit, behat and phpspec tests
#

# check if the script has been sourced when called from zsh in a dev container
[[ $_ != $0 ]] && echo "This script must be sourced to run correctly." && exit 1

# Http and https proxies can lead selenium tests to fail, so we remove them before launching all others commands
# and we reinstate then afterwards
HTTPSPROXY=$https_proxy
HTTPPROXY=$http_proxy

unset https_proxy ;
unset http_proxy ;
php -d memory_limit=256M app/console cache:clear --env=test ;
chmod -R 0777 app/cache app/logs ;
bin/stop_selenium.sh ;
bin/start_selenium.sh &&
php -d memory_limit=128M bin/phpspec run ;
php -d memory_limit=128M bin/phpunit -c app/ ;
php -d memory_limit=128M bin/behat --verbose ;
bin/stop_selenium.sh ;
export https_proxy=$HTTPSPROXY ;
export http_proxy=$HTTPPROXY
