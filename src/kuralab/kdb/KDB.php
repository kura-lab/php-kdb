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

    private function createDirectory()
    {
        if (!file_exists($this->dir)) {
            if (!mkdir($this->dir, 0777, true)) {
                return false;
            }
        }

        return true;
    }

    private function generatePath($key)
    {
        $this->path = $this->dir . $this->generateHash($key);
    }

    protected function generateHash($key)
    {
        return md5($key);
    }

    public function set($key, $value)
    {
        if (!$this->createDirectory()) {
            return false;
        }
        $this->generatePath($key);

        $buffer = $this->readAll();
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
        if (!$this->createDirectory()) {
            return false;
        }
        $this->generatePath($key);

        $buffer = $this->readAll();
        if (!$buffer) {
            return false;
        }
        $result = json_decode($buffer, true);

        return $result[$key];
    }

    private function readAll()
    {
        if (!file_exists($this->path)) {
            return false;
        }
        $fp = fopen($this->path, "r");
        if (!$fp) {
            fclose($fp);
            return false;
        }
        if (flock($fp, LOCK_EX)) {
            $buffer = "";
            while (!feof($fp)) {
                $buffer .= fgets($fp);
            }
        } else {
            return false;
        }
        fclose($fp);

        return $buffer;
    }
}
