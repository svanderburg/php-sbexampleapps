php-sbexampleapps
=================
This package contains a number of example applications built around my own
custom PHP libraries. The example applications share a number of common
components, such as a common login system and layout/style.

The applications can be combined into a single system through the `portal`
application.

Applications
============
Currently, this repository provides the following example applications:

* `homework`. A toy-application that can be used to create test exams and assess
   your skills.
* `literature`. A toy-application that can be used to manage scientific
  literature, such as papers, conferences, authors and publishers. It also
  provides a feature to generate citations, including BibTeX citations.
* `users`. A front-end for the authentication system to manage users and their
  write permissions.
* `cms`. An example application for the page manager library allowing end-users
  to dynamically add pages to the page layout and managing the contents of
  the pages.
* `cmsgallery`. An example application demonstrating how the gallery sub system
  can be used to dynamically construct sub pages and manage their contents.

The above applications can be integrated into and made uniformly accessible from
the `portal` application. In addition, the portal has an embedded news page,
changelog and gallery.

The above applications use the following common application specific modules:

* `auth` is the authentication system that checks whether a user has write
  permissions to a sub system.
* `layout` contains all common layout properties that all applications
  implement.

Prerequisites
=============
The example applications require the following packages as dependencies:

* [php-sblayout](https://github.com/svanderburg/php-sblayout)
* [php-sbdata](https://github.com/svanderburg/php-sbdata)
* [php-sbeditor](https://github.com/svanderburg/php-sbeditor)
* [php-sbcrud](https://github.com/svanderburg/php-sbcrud)
* [php-sbgallery](https://github.com/svanderburg/php-sbgallery)
* [php-sbpagemanager](https://github.com/svanderburg/php-sbpagemanager)
* [php-sbbiblio](https://github.com/svanderburg/php-sbbiblio)

Disnix deployment example
=========================
The `deployment/` folder contains deployment configuration files making it
possible to deploy the examples as integrated system in a network of virtual
machines by using a combination of the
[NixOps](http://github.com/nixos/nixops) and
[Disnix](http://github.com/svanderburg/disnix) tools.

License
=======
Copyright (C) 2017  Sander van der Burg

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
