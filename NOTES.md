Potentially useful libraries
============================

Routing: klein, fastroute, slim

Command line
============

Substance has a command line interface, that can be extended by modules.

Modules
=======

Drupal-like modules.

* Modules MUST provide a settings/configuration.
* Modules MUST be defined in their own namespace.

Database management
===================

Database management is a key component, and anything but easy.

* Module MUST have their own migration sequence.
* Migrations MUST be ordered.
* Migrations SHOULD allow bulk data loads (e.g. from CSV).
* Migrations SHOULD allow rollback?
* Migrations MUST be defined once and only once (i.e. no hook_update_N and
  hook_schema like Drupal)