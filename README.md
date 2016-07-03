# README

## Requirements

- VirtualBox
- Vagrant

Install Vagrant and Virtualbox (Mac)

    $ brew cask install virtualbox
    $ brew cask install vagrant

Install helpful Vagrant plugins

    $ vagrant plugin install vagrant-hostsupdater

## Install

### Check out the code

    $ git clone https://github.com/chrisyallop/vending-machine.git

### Install dependencies (from inside the cloned project folder)

    $ composer install

This should work from both the VM and the host machine

### Launch Vagrant VM

    $ vagrant up

This should start to download the Laravel Homestead VM the app will run on. If this does not then run the following:

    $ vagrant box add laravel/homestead
    $ vagrant up

### Update hosts file

If you installed the vagrant-hostsupdater plugin you will be asked for your sudo password to add the relevant entry into your hosts file to view the app in your browser.

Otherwise you will need to manually add to your hosts file the following entry:

    192.168.10.10  vending-machine

### View the app

In a browser go to the URL [http://vending-machine/](http://vending-machine/)

## Testing / Dev

### BDD tests

#### From host machine

    $ ./vendor/bin/behat

#### From Vagrant VM

    $ vagrant ssh
    $ cd vending-machine/
    $ ./vendor/bin/behat

### Unit tests

#### From host machine

    $ ./vendor/bin/phpunit

#### From Vagrant VM

    $ vagrant ssh
    $ cd vending-machine/
    $ ./vendor/bin/phpunit

To add code coverage reports within the VM, XDebug needs to be enabled, todo run:

    $ echo "zend_extension=xdebug.so" | sudo tee /etc/php/7.0/cli/conf.d/20-xdebug.ini
    $ sudo service php7.0-fpm restart

Then re-run phpunit above.

