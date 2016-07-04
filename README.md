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

## Up and Running via the Web UI

- The selling price is fixed at 50p. No mention in the exercise said how this should be passed. wasn't sure if it should be done with the purchase amount or not, so I modelled the real world and had this amount pre-set, although this can be easily modified when starting up a vending machine through object instantiation or thrugh the UI with some slight tweaks.

## Retrospective

- Wasn't sure how far to go with this exercise. Enough to just show a little bit of using BDD tests and Unit tests, a web based UI and some edge cases or go for perfection.
- Should have used Mathias Verraes [Money](https://github.com/moneyphp/money) object at the start for re-usability.
- Should have built a CLI interface from the beginning to better remind myself of the UI than referring to the BeHat context class.
- Possibly should have saved each part in it's own Git repository.
- Thinking bigger, I almost added a customer object as the interacter of the Vending Machine as this is who this benefits in the BDD scenarios.

## Other Notes

- The purchase price, once set from the Money::fromAmount() method, will create an amount of money using an optimum denomination.
- Once the purchase price is set, the optimum denominations were then added to the inventory but this since been commented out. The functionality remains in the code.
- Non-functional requirements such as logging were left out as this wasn't specifically mentioned as a requirement.
- Server-side form validation was left out and simple HTML form controls were used.
- The display of exceptions were left as the default in the Slim Framework as opposed to being tidied up for user presentation.
