<?php

namespace kuralab\kdb;

require_once(dirname(__FILE__) . "/ErrorCode.php");

use kuralab\kdb\ErrorCode;

class KDB
{
    private $dir;
    private $path;
    private $errCode;

    public function __construct($dir)
    {
        $this->dir     = $dir . ".kdb/";
        $this->path    = "";
        $this->errCode = KDB_NO_OPARATION;
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
        $this->errCode = KDB_OK;

        if (!$this->createDirectory()) {
            $this->errCode = KDB_DIRECTORY_ERROR;
            return false;
        }
        $this->generatePath($key);

        $fp = fopen($this->path, "w");
        if (!$fp) {
            fclose($fp);
            $this->errCode = KDB_FILE_OPEN_ERROR;
            return false;
        }
        if (flock($fp, LOCK_EX)) {
            $json = json_encode($value);
            fwrite($fp, $json);
        } else {
            $this->errCode = KDB_FILE_LOCK_ERROR;
            return false;
        }
        fclose($fp);

        return true;
    }

    public function get($key)
    {
        $this->errCode = KDB_OK;

        if (!$this->createDirectory()) {
            $this->errCode = KDB_DIRECTORY_ERROR;
            return false;
        }
        $this->generatePath($key);

        $buffer = $this->readAll();
        if (!$buffer) {
            return false;
        }
        $result = json_decode($buffer, true);

        return $result;
    }

    private function readAll()
    {
        if (!file_exists($this->path)) {
            $this->errCode = KDB_NOT_FOUND_KEY_ERROR;
            return false;
        }
        $fp = fopen($this->path, "r");
        if (!$fp) {
            fclose($fp);
            $this->errCode = KDB_FILE_OPEN_ERROR;
            return false;
        }
        if (flock($fp, LOCK_EX)) {
            $buffer = "";
            while (!feof($fp)) {
                $buffer .= fgets($fp);
            }
        } else {
            $this->errCode = KDB_FILE_LOCK_ERROR;
            return false;
        }
        fclose($fp);

        return $buffer;
    }

    public function getErrorCode()
    {
        return $this->errCode;
    }
}
