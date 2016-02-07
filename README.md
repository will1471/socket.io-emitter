# Tests

'''
curl -sS https://getcomposer.org/installer | php
php composer.phar install
vagrant up
vagrant ssh
curl -sL https://deb.nodesource.com/setup_4.x | sudo -E bash -
sudo apt-get install -y nodejs redis-server php5-cli
cd /vagrant/test/node
npm install
cd /vagrant
./vendor/bin/phpunit --bootstrap ./vendor/autoload.php ./tests/
'''

