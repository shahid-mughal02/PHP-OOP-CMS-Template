<?php

define('WEBSITE_TITLE', 'Site Title');
define('DASHBOARD_TITLE', 'Dashboard Title');
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_name');
define('DB_USER', 'root');
define('DB_PASS', '');

define('THEME', 'theme/');
define('DASHBOARD', 'dashboard/');

define('DEBUG', true);

if (DEBUG) {
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
}
