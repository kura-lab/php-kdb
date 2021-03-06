# php-kdb
KDB(Kura DataBase) is a simple database of text files.

[![Packagist](https://img.shields.io/packagist/v/kura-lab/php-kdb.svg)](https://packagist.org/packages/kura-lab/php-kdb)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](https://github.com/kura-lab/php-kdb/blob/master/LICENSE)

### Install

At first, install composer.

```
$ mkdir workspace
$ cd workspace
$ curl -s http://getcomposer.org/installer | php
```

Create composer.json.

```
{
    "require": {
        "kura-lab/php-kdb": "1.0.0"
    }
}
```

Install kdb library.

```
$ php composer.phar install
```

### Usage

Require autoloader in the your program.

```
<?php
// sample.php

require_once("vendor/autoload.php");

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
```

The following is result of implementation.

```
$ php sample.php

string(3) "foo"
string(25) "{"bar":"huga","0":"fuga"}"
```


### Command

Install kdb command.

```
$ cd php-kdb/bin/
$ ./kdb_install 
kdb: Created ~/.kdb directory.
kdb: Created symbolic link kdb.
kdb: Added kdb path in ~/.bash_profile.
kdb: Installed kdb.(~/.kdb)
$ exec $SHELL -l
$ which kdb
~/.kdb/bin/kdb
```

Display usage of kdb command.

```
$ ./bin/kdb -h
Usage: ./bin/kdb [-h] [-D database [-R key] [-W -k key -v value]]
```

Write in the database.

```
$ ./bin/kdb -D users -W -k hoge -v foo
kdb: Write mode.
key:   hoge
value: foo
kdb: Success to write.
```

Read the database.

```
$ ./bin/kdb -D users -R hoge
kdb: Read mode.
string(4) "foo"
kdb: Success to read.
```

Uninstall kdb command.

```
$ ./kdb_uninstall 
Deleted ~/.kdb directory.
kdb: Uninstalled kdb.(~/.kdb)
```
