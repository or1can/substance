Potentially useful libraries
============================

Routing: klein, fastroute, slim
Frontend: http://www.polymer-project.org/, bower.io
Testing: PhantomJS

Command line
============

Substance has a command line interface, that can be extended by modules.

Internationalisation / Localisation
===================================

Unicode (UTF-8) by default, not customisable at this time.

* intl PHP extension
* mb_string PHP extension

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
  
Presentation arrays
===================

Would it be useful to allow an array based presentation system, kind of like a
serialised Element structure? e.g. the following table strucutre:

$table_array = array(
  '#type' => 'table',
  '#rows' => array(
    array(
      '#type' => 'table_row',
      '#cells' => array(
        array(
          '#type' => 'table_cell',
          '#value' => array(
            '#type' => 'markup',
            '#markup' => 'Message : ',
          ),
        ),
        array(
          '#type' => 'table_cell',
          '#value' => array(
            '#type' => 'markup',
            '#markup' => $this->getMessage(),
          ),
        ),
      ),
    ),
  )
);
