<?php
// sample.php

require_once("vendor/autoload.php");

use kuralab\kdb\KDB;

// Initialize

$kdb = new KDB("./users");

// Set data

$result = $kdb->set("hoge", "foo");
if (!$result) {
    echo "Error: " . $kdb->getErrorCode() . "\n";
}

$value = json_encode(
    array(
        "bar"=>"huga",
        "fuga"
    )
);
$result = $kdb->set("tege", $value);
if (!$result) {
    echo "Error: " . $kdb->getErrorCode() . "\n";
}

// Get data

$result = $kdb->get("hoge");
if ($result) {
    var_dump($result);
} else {
    echo "Error: " . $kdb->getErrorCode() . "\n";
}

$result = $kdb->get("tege");
if ($result) {
    var_dump($result);
} else {
    echo "Error: " . $kdb->getErrorCode() . "\n";
}
