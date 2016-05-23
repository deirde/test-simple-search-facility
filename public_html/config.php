<?php

/**
 * ORM.
 * http://paris.readthedocs.io/en/latest/configuration.htmlhttp://www.redtube.com/1422564
 */
require_once 'vendor' . DIRECTORY_SEPARATOR . 'idiorm' . DIRECTORY_SEPARATOR . 'idiorm.php';
require_once 'vendor' . DIRECTORY_SEPARATOR . 'paris' . DIRECTORY_SEPARATOR . 'paris.php';

ORM::configure('mysql:host=localhost;dbname=test_sykescottages_co_uk');
ORM::configure('username', 'root');
ORM::configure('password', 'qwerty');

/**
 * Validation
 * https://github.com/Wixel/GUMP
 */
require_once 'vendor' . DIRECTORY_SEPARATOR . 'gump' . DIRECTORY_SEPARATOR . 'gump.class.php';

/**
 * Kint.
 * http://raveren.github.io/kint/
 */
require_once 'vendor' . DIRECTORY_SEPARATOR . 'kint' . DIRECTORY_SEPARATOR . 'Kint.class.php';

?>