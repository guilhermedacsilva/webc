WebC
====

Very simple web editor for C language.

Requirements:
- PHP 5
- MySQL 5.5
- Apache 2
- GCC

Installation
====

- Copy it to "www" folder and run SQL scripts (in that order).
- Folder "user" must be readable/writable. It will contain source code (.c) and executables!
- Edit "configs.php".

Done!

Folders
====

- css: bootstrap, codemirror, custom CSS;
- fonts: bootstrap fonts;
- js: bootstrap, codemirror, jquery, custom JS
- libs: codemirror;
- project: database model and SQL;
- user: user code and executables;

PHPs
====

- index.php: login page.
- ide.php: ide page.
- include_me.php: functions (no OO).
- run.php: called by ajax; run gcc; verify some stuff; run the program; save the data in database.
- configs.php: database connection configuration.
