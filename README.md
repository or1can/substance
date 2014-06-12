Substance
=========

Substance is a Content Management System (CMS) and application framework.
Substance is intended to be a standards based, object oriented application
framework that happens to include a Web based CMS, both as example of what the
framework can do and as a driver for the development of the framework.

The framework is based on carefully chosen standards (which ideally *would
not* include PHP as a base!).

Development
===========

We use Vagrant to allow us to easily check substance against our supported
platforms. Currently, we have a single platform, Centos 6.5, Apache 2.2 and
PHP 5.3. Install Vagrant and from the project root type:

vagrant up

to get you up and running. If you are running a system that already has Apache
2.2 and PHP 5.3, set up a virtual host with your document root pointing at the
"public" folder.

References
==========

[1]: http://www.php-fig.org/psr/psr-0 "PSR-0"
[2]: http://www.php-fig.org/psr/psr-1 "PSR-1"
[3]: http://www.php-fig.org/psr/psr-2 "PSR-2"
[4]: http://www.php-fig.org/psr/psr-3 "PSR-3"
[5]: http://www.php-fig.org/psr/psr-4 "PSR-4"
[6]: https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc.md "PSR-5 (Proposed)"
