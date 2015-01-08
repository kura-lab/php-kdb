<?php

require_once("../src/kuralab/kdb/KDB.php");

use kuralab\kdb\KDB;

$kdb = new KDB("./users");
$kdb->open("kura");
$kdb->set("hoge", "foo");
$kdb->set("tege", json_encode(array("bar"=>"huga", "fuga")));
var_dump($kdb->get("hoge"));
var_dump($kdb->get("tege"));
