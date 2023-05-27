<?php

namespace App\Utils;

class FileDatabase
{
    /** @var string */
    protected $path;
    /** @var string */
    protected $name;
    /** @var string */
    protected $delimiter;
    /** @var string */
    protected $partSize;

    /**
     * @param string $path Folder path where file database stores
     * @param string $name File name of database
     * @param string $delimiter Values delimiter
     * @param int $partSize Max size of the file database part (in bytes)
     */
    public function __construct(string $path, string $name, string $delimiter, int $partSize)
    {
        $this->path = $path;
        $this->name = $name;
        $this->delimiter = $delimiter;
        $this->partSize = $partSize;
        if (!file_exists($path))
            mkdir($path);
    }

    /**
     * Add value to file database
     *
     * @param string $value
     * @return void
     */
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

    /**
     * Check if file database contains some value
     *
     * @param string $value
     * @return bool true if file database contains some value else false
     */
    public function contains(string $value)
    {
        $splitCount = count(glob($this->path . str_replace("%d", "*", $this->name)));

        for ($i = 1; $i <= $splitCount; $i++) {
            $items = explode($this->delimiter, file_get_contents($this->getFilePath($i)));
            array_pop($items);
            foreach ($items as $item) {
                if ($value == $item)
                    return true;
            }
        }

        return false;
    }

    /**
     * Get all values from file database
     *
     * @return array Array that contains all values from file database
     */
    public function getAll()
    {
        $splitCount = count(glob($this->path . str_replace("%d", "*", $this->name)));

        $items = [];
        for ($i = 1; $i <= $splitCount; $i++) {
            $part = explode($this->delimiter, file_get_contents($this->getFilePath($i)));
            array_pop($part);
            foreach ($part as $item) {
                $items[] = $item;
            }
        }

        return $items;
    }

    /**
     * Get full path of some file database's part
     *
     * @param $partNumber string File databse part
     * @return string Full path of some file database's part
     */
    protected function getFilePath($partNumber)
    {
        return sprintf($this->path . $this->name, $partNumber);
    }
}
