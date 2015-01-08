<?php

require_once("../src/KDB.php");

use jp\kura\KDB;

$kdb = new KDB("./users");
$kdb->open("kura");
$kdb->set("hoge", "foo");
$kdb->set("tege", "bar");
var_dump($kdb->get("hoge"));
var_dump($kdb->get("tege"));
