<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'root';
$db['default']['password'] = '';
$db['default']['database'] = 'ym_sia';
$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;


$db['gate']['hostname'] = 'localhost';
$db['gate']['username'] = 'root';
$db['gate']['password'] = '';
$db['gate']['database'] = 'ym_gate';
$db['gate']['dbdriver'] = 'mysql';
$db['gate']['dbprefix'] = '';
$db['gate']['pconnect'] = FALSE;
$db['gate']['db_debug'] = TRUE;
$db['gate']['cache_on'] = FALSE;
$db['gate']['cachedir'] = '';
$db['gate']['char_set'] = 'utf8';
$db['gate']['dbcollat'] = 'utf8_general_ci';
$db['gate']['swap_pre'] = '';
$db['gate']['autoinit'] = TRUE;
$db['gate']['stricton'] = FALSE;

$db['donasi']['hostname'] = 'localhost';
$db['donasi']['username'] = 'root';
$db['donasi']['password'] = '';
$db['donasi']['database'] = 'ym_simdonasi';
$db['donasi']['dbdriver'] = 'mysql';
$db['donasi']['dbprefix'] = '';
$db['donasi']['pconnect'] = FALSE;
$db['donasi']['db_debug'] = TRUE;
$db['donasi']['cache_on'] = FALSE;
$db['donasi']['cachedir'] = '';
$db['donasi']['char_set'] = 'utf8';
$db['donasi']['dbcollat'] = 'utf8_general_ci';
$db['donasi']['swap_pre'] = '';
$db['donasi']['autoinit'] = TRUE;
$db['donasi']['stricton'] = FALSE;

$db['keuangan']['hostname'] = 'localhost';
$db['keuangan']['username'] = 'root';
$db['keuangan']['password'] = '';
$db['keuangan']['database'] = 'ym_finance';
$db['keuangan']['dbdriver'] = 'mysql';
$db['keuangan']['dbprefix'] = '';
$db['keuangan']['pconnect'] = FALSE;
$db['keuangan']['db_debug'] = TRUE;
$db['keuangan']['cache_on'] = FALSE;
$db['keuangan']['cachedir'] = '';
$db['keuangan']['char_set'] = 'utf8';
$db['keuangan']['dbcollat'] = 'utf8_general_ci';
$db['keuangan']['swap_pre'] = '';
$db['keuangan']['autoinit'] = TRUE;
$db['keuangan']['stricton'] = FALSE;

$db['hrd_gaji']['hostname'] = 'localhost';
$db['hrd_gaji']['username'] = 'root';
$db['hrd_gaji']['password'] = '';
$db['hrd_gaji']['database'] = 'ym_hrd';
$db['hrd_gaji']['dbdriver'] = 'mysql';
$db['hrd_gaji']['dbprefix'] = '';
$db['hrd_gaji']['pconnect'] = FALSE;
$db['hrd_gaji']['db_debug'] = TRUE;
$db['hrd_gaji']['cache_on'] = FALSE;
$db['hrd_gaji']['cachedir'] = '';
$db['hrd_gaji']['char_set'] = 'utf8';
$db['hrd_gaji']['dbcollat'] = 'utf8_general_ci';
$db['hrd_gaji']['swap_pre'] = '';
$db['hrd_gaji']['autoinit'] = TRUE;
$db['hrd_gaji']['stricton'] = FALSE;

/* End of file database.php */
/* Location: ./application/config/database.php */