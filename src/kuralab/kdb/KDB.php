<?php

namespace kuralab\kdb;

class KDB
{
    private $dir;
    private $path;

    public function __construct($dir)
    {
        $this->dir = $dir . ".kdb/";
    }

    public function open($key)
    {
        if (!file_exists($this->dir)) {
            if (!mkdir($this->dir, 0777, true)) {
                return false;
            }
        }
        $this->path = $this->dir . md5($key);

        return true;
    }

    public function set($key, $value)
    {
        $buffer = $this->getAllData();
        if (!$buffer) {
            $result = array();
        } else {
            $result = json_decode($buffer, true);
        }
        $result[$key] = $value;
        $fp = fopen($this->path, "w");
        if (!$fp) {
            fclose($fp);
            return false;
        }
        if (flock($fp, LOCK_EX)) {
            $json = json_encode($result);
            fwrite($fp, $json);
        } else {
            return false;
        }
        fclose($fp);

        return true;
    }

    public function get($key)
    {
        $buffer = $this->getAllData();
        if (!$buffer) {
            return false;
        }
        $result = json_decode($buffer, true);

        return $result[$key];
    }

    private function getAllData()
    {
        if (!file_exists($this->path)) {
            return false;
        }
        $fp = fopen($this->path, "r");
        if (!$fp) {
            fclose($fp);
            return false;
        }
        if (flock($fp, LOCK_SH)) {
            $buffer = "";
            while (!feof($fp)) {
                $buffer .= fgets($fp);
            }
            flock($fp, LOCK_UN);
        } else {
            return false;
        }
        fclose($fp);

        return $buffer;
    }
}
