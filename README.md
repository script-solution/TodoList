TodoList:
=========

This is a simple PHP application that lets you maintain your TODOs for multiple projects and
generate changelogs.

Installation:
-------------

Just perform the following steps:

1. Retrieve FrameWorkSolution: `$ git submodule init && git submodule update`.
2. Create a directory named `cache` in the root directory.
3. Ensure that the webserver has write permissions for the `cache` directory.
4. Create a MySQL database and import the `install/structure.sql`.
5. Copy the config/mysql.php.sample to config/mysql.php and adjust it accordingly.
