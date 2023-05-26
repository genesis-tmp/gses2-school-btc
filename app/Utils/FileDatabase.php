<?php

namespace App\Utils;

class FileDatabase
{
    protected $path;
    protected $name;
    protected $delimiter;
    protected $partSize;

    public function __construct(string $path, string $name, string $delimiter, int $partSize)
    {
        $this->path = $path;
        $this->name = $name;
        $this->delimiter = $delimiter;
        $this->partSize = $partSize;
        if (!file_exists($path))
            mkdir($path);
    }

    public function add(string $value)
    {
        $splitCount = max(1, count(glob($this->path . str_replace("%d", "*", $this->name))));

        if ((file_exists($filename = $this->getFilePath($splitCount)))
            && (filesize($filename) + strlen($value) + strlen($this->delimiter) > $this->partSize))
            $splitCount++;

        $handle = fopen($this->getFilePath($splitCount), "a");
        fwrite($handle, $value . $this->delimiter);
        fclose($handle);
    }

    public function contains(string $value)
    {
        $splitCount = count(glob($this->path . str_replace("%d", "*", $this->name)));

        for ($i = 1; $i <= $splitCount; $i++) {
            $emails = explode($this->delimiter, file_get_contents($this->getFilePath($splitCount)));
            foreach ($emails as $email) {
                if ($value == $email)
                    return true;
            }
        }

        return false;
    }

    public function getAll()
    {
        $splitCount = count(glob($this->path . str_replace("%d", "*", $this->name)));

        $arrays = [];
        for ($i = 1; $i <= $splitCount; $i++) {
            $arrays[] = explode($this->delimiter, file_get_contents($this->getFilePath($splitCount)));
        }

        return array_merge($arrays);
    }

    protected function getFilePath($partNumber)
    {
        return sprintf($this->path . $this->name, $partNumber);
    }
}
