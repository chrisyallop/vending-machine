# README

Ansible is used to create a reproducible configuration for the web server.

## Install

Ansible version 2.0 is required

    $ brew install ansible

Install boto

    $ easy_install boto

Install the Ansible build dependencies e.g. PHP

    $ ./ops/ansible/scripts/install_requirements

Install Vagrant and Virtualbox

    $ brew cask install virtualbox
    $ brew cask install vagrant

Install helpful Vagrant plugins

    $ vagrant plugin install vagrant-hostsupdater

Use the most up to date version of Ubuntu (if a previous version already exists)

    $ vagrant box update

To start a vagrant box

    $ vagrant up

## Maintenance

If provisioning fails or you want more control you can run Ansible manually. To provision when the code is on the host:

    $ ./ops/ansible/scripts/configure_dev
