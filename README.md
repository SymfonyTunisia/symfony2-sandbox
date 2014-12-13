Install using Vagrant
---------------------

From the host

    git clone https://github.com/SymfonyTunisia/symfony2-sandbox
    cd symfony-sandbox
    vagrant up
    vagrant ssh


Inside the VM

    cd /var/www/symfony-sandbox
    composer update
    make install


Edit your hosts file (/etc/hosts) and add :

    192.168.56.106  symfony-sandbox.dev www.symfony-sandbox.dev

You can now access project page at

[https://symfony-sandbox.dev/](https://symfony-sandbox.dev/) (prod env)

[https://symfony-sandbox.dev/app_dev.php](https://symfony-sandbox.dev/app_dev.php) (dev env)

[http://www.symfony-sandbox.dev:1080/](http://www.symfony-sandbox.dev:1080/) (mailcatcher)

Admin Mysql : [adminer](http://192.168.56.106/adminer/)
