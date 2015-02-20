Potentially useful libraries
============================

Routing: klein, fastroute, slim
Frontend: http://www.polymer-project.org/, bower.io
Testing: PhantomJS, Mockery
Template engines: Twig, FigDice, H20, mars-templater, RainTPL3
Database: Paris, Idiorm

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

Debugging
=========

var_export does not work with recursive dependencies, e.g. our
connection/database objects - use var_dump instead.

Database management
===================

Database management is a key component, and anything but easy.

* Module MUST have their own migration sequence.
* Migrations MUST be ordered.
* Migrations SHOULD allow bulk data loads (e.g. from CSV).
* Migrations SHOULD allow rollback?
* Migrations MUST be defined once and only once (i.e. no hook_update_N and
  hook_schema like Drupal)

Database API
============

The database API consists of the following four types of object:

* Component
  (Analogous to an Element in the presentation API)
  A Component is a building block of any kind of database query.
* Composable
  (Analogous to an Presentable in the presentation API)
  A composable object can represent itself as a Component. This allows non-Core
  classes to be included in queries.
* Buildable
  (Analogous to an Renderable in the presentation API)
  A buildable object knows howto build itself for a given database. All
  components are buildable, but not all buildable are components.
* Database
  (Analogous to a Theme in the presentation API)
  A database shares a lot in common with a Connection, in fact both implement
  the basic __connection interface__. A database allows access to the tables it
  contains.
  
These four types work together to allow Substance applications to handle a wide
variety of queries and databases, while allowing third party modules to extend
the built-in functionality.

Alerts
======

Alerts should have links to appropriate documentation, where it exists. Some
would be specific to Substance and others would be specific to modules or the
application that has been built with Substance. We should allow for this
possibility somehow, e.g.

$alert = new Alert('Whoops.');
$alert->culprit( 'thing', 'value' );
$alert->see( '[namespace]', '[errorcode]' );

Then we allow for applications to provide a decorator or similar that converts
the errorcode into a link for the specified namespace (or class).

Presentation API
================

The presentation layer consists of the following four types of object:

* Element
  An Element is the basic building block of the presentation system. Each type
  of Element represents something that needs to be presented differently. e.g.
  an form select list, a page or a table.
* Presentable
  A presentable object can represent itself as an Element. As every object is
  not an Element, this interface allows disparate classes to transform
  themselves into the appropriate Element.
* Renderable
  A renderable object knows howto render itself in a given theme.
* Theme
  A theme allows renderable objects to be rendered in a variety of different
  ways.
  
These four types work together to allow Substance applications to present
themselves in a wide variety of ways - e.g. it is the foundation of allowing a
Substance application to work at the command line or in a web based setting.

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
