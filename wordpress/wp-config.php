<?php
// ** MySQL settings ** //
if ($_SERVER["SERVER_NAME"] == "localhost") {
	define('DB_NAME', 'loremipsum');    // The name of the database
	define('DB_USER', 'root');     // Your MySQL username
	define('DB_PASSWORD', 'root'); // ...and password
	define('DB_HOST', 'localhost');    // 99% chance you won't need to change this value
} else {
	define('DB_NAME', 'db239095099');    // The name of the database
	define('DB_USER', 'dbo239095099');     // Your MySQL username
	define('DB_PASSWORD', 'eVFxAuK7'); // ...and password
	define('DB_HOST', 'db1183.perfora.net');    // 99% chance you won't need to change this value
}
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

// You can have multiple installations in one database if you give each a unique prefix
$table_prefix  = 'wp_';   // Only numbers, letters, and underscores please!

// Change this to localize WordPress.  A corresponding MO file for the
// chosen language must be installed to wp-content/languages.
// For example, install de.mo to wp-content/languages and set WPLANG to 'de'
// to enable German language support.
define ('WPLANG', '');

/* That's all, stop editing! Happy blogging. */

define('ABSPATH', dirname(__FILE__).'/');
require_once(ABSPATH.'wp-settings.php');
?>
