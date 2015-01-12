# php-kdb
KDB(Kura DataBase) is a simple database of text files.

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
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/kura-lab/php-kdb"
        }
    ],
    "require": {
        "kura-lab/php-kdb": "dev-master"
    }
}
```

Install kdb library.

```
$ php composer.phar install
```

### Useage

Require autoloader in the your program.

```
<?php
// sample.php

require_once("vendor/autoload.php");

use kuralab\kdb\KDB;

$kdb = new KDB("./users");
$kdb->set("hoge", "foo");
$kdb->set("tege", json_encode(array("bar"=>"huga", "fuga")));
var_dump($kdb->get("hoge"));
var_dump($kdb->get("tege"));
```

The following is result of implementation.

```
$ php sample.php

string(3) "foo"
string(25) "{"bar":"huga","0":"fuga"}"
```


### Command

Display usage of kdb command.

```
$ ./bin/kdb -h
Usage: ./bin/kdb [-h] [-D database [-R key] [-W -k key -v value]]
```

Write in the database.

```
$ ./bin/kdb -D user -W -k hoge -v tege
```

Read the database.

```
$ ./bin/kdb -D user -R hoge
```
