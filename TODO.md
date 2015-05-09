TODO
====

* Database
  * Record
    * More AbstractRecord tests.
  * Schema
    * Use SCHEMA as the basis for MySQL multiple database support, as we can
      map this across SQLite and PostgreSQL in a consistent way.
    * Ensure data definitions are always applied:
      * Before shutdown
      * Before any other queries are run
  * Select
    * Alterations [HIGH]
    * Extensions [HIGH]
    * Expressions
      * Functions [HIGH]
      * 
    * Unions [MEDIUM]
    * Count queries [MEDIUM]
    * FOR UPDATE [MEDIUM]
    * Shorthand for adding multiple simple columns [MEDIUM]
  * Insert
  * Update
  * Delete
  * Transactions
  * Consider using the presentation layer as a base, rather than mirroring it