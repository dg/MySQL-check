<?php

/**
 * Checks a MySQL database for invalid foreign keys, i.e., a keys pointing to missing rows.
 *
 * @author     David Grudl (http://davidgrudl.com)
 * @copyright  Copyright (c) 2008 David Grudl
 * @license    New BSD License
 * @version    1.0
 */
function checkForeignKeys(mysqli $db, $database = NULL)
{
	$keys = $db->query('
		SELECT TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
		FROM information_schema.KEY_COLUMN_USAGE
		WHERE REFERENCED_TABLE_SCHEMA IS NOT NULL'
		. ($database ? " AND TABLE_SCHEMA='{$db->escape_string($database)}'" : '')
	);

	foreach ($keys as $key) {
		echo "$key[TABLE_SCHEMA] $key[TABLE_NAME].$key[COLUMN_NAME]: ";
		foreach ($key as & $identifier) {
			$identifier = '`' . str_replace('`', '``', $identifier) . '`';
		}
		$row = $db->query("
			SELECT COUNT($key[COLUMN_NAME])
			FROM $key[TABLE_SCHEMA].$key[TABLE_NAME]
			WHERE $key[COLUMN_NAME] NOT IN (SELECT $key[REFERENCED_COLUMN_NAME] FROM $key[TABLE_SCHEMA].$key[REFERENCED_TABLE_NAME])
		")->fetch_array();
		echo $row[0] ? "found $row[0] invalid foreign keys!\n" : "OK\n";
	}
}


// example
checkForeignKeys(new mysqli('localhost', 'root', '***'), 'blog');
