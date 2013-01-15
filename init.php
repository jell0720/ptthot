<?php
require(__DIR__ . '/webdata/pixcore/Pix/Loader.php');
set_include_path(__DIR__ . '/webdata/pixcore/'
    . PATH_SEPARATOR . __DIR__ . '/webdata/models'
);

Pix_Loader::registerAutoload();

if (file_exists(__DIR__ . '/webdata/setting.php')) {
    include(__DIR__ . '/webdata/setting.php');
}

date_default_timezone_set('Asia/Taipei');

if (!getenv('DATABASE_URL')) {
    die('need DATABASE_URL');
}

if (preg_match('#postgres://([^:]*):([^@]*)@([^/]*)/(.*)#', strval(getenv('DATABASE_URL')), $matches)) {
    $user = $matches[1];
    $pass = $matches[2];
    $host = $matches[3];
    $dbname = $matches[4];
    Pix_Table::setDefaultDb(new Pix_Table_Db_Adapter_PgSQL(array('user' => $user, 'password' => $pass, 'host' => $host, 'dbname' => $dbname)));
} else if (preg_match('#mysql://([^:]*):([^@]*)@([^/]*)/(.*)#', strval(getenv('DATABASE_URL')), $matches)) {
    $link = new mysqli;
    $link->connect($matches[3], $matches[1], $matches[2]);
    $link->select_db($matches[4]);
    $link->set_charset('utf8');
    Pix_Table::setDefaultDb(new Pix_Table_Db_Adapter_Mysqli($link));
}

Pix_Controller::addCommonHelpers();

