<?php

class DB {
    public $name;
    private $conn;

    public function __construct($name) {
        $this->name = $name;
        $this->conn = "DB connection open";
    }

    public function __sleep() {
        echo __FUNCTION__." => this method happen when use ( serialize ) helper function <br>". PHP_EOL;
        return ['name']; // only serialize $name
    }

    public function __wakeup() {
        echo __FUNCTION__." => this method happpen when use ( unserialize ) helper function <br>". PHP_EOL;
        $this->conn = "DB connection re-opened";
    }
}

$obj = new DB("sqlite");
echo '<h1>Normal Object</h1>'. PHP_EOL;
var_dump($obj);

echo '<h1>After Serialize</h1>'. PHP_EOL;
// When you call serialize(), PHP will call __sleep() internally
$str = serialize($obj);
echo($str);

echo '<h1>After Unserialize</h1>'. PHP_EOL;
// When you call unserialize(), PHP will call __wakeup() internally
$newObj = unserialize($str);
print_r($newObj);
