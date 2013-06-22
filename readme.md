MySQLChecker
============

Checks a MySQL database for orphaned records or invalid foreign keys, i.e., keys pointing to missing rows.

Create [MySQLi](http://www.php.net/manual/en/mysqli.construct.php) object and then check database named `blog`:

	$mysqli = new mysqli('localhost', 'root', 'password');
	checkForeignKeys($mysqli, 'blog');

Or without parameter it checks all databases:

	checkForeignKeys($mysqli);


-----
Project at GitHub: http://github.com/dg/MySQL-check

(c) David Grudl, 2008, 2013 (http://davidgrudl.com)
