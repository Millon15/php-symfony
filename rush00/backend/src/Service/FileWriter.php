<?php
declare(strict_types=1);

namespace App\Service;

use RuntimeException;

/**
 * Class FileWriter
 * @package Service
 */
class FileWriter
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * @var bool|resource
     */
    private $resource;

    /**
     * FileWriter constructor.
     *
     * @param string $fileName
     *
     * @throws RuntimeException
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
        $this->resource = fopen($this->fileName, 'c+b');
        if ($this->resource === false) {
            throw new RuntimeException("Failed to open file: {$this->fileName}");
        }
    }

    /**
     * @param null|string $keyToRead
     *
     * @return array
     */
    public function read(?string $keyToRead = null): array
    {
        flock($this->resource, LOCK_EX | LOCK_SH);

        $filesize = filesize($this->fileName);
        $content = ($filesize === 0)
            ? []
            : json_decode(fread($this->resource, $filesize), true);

        flock($this->resource, LOCK_UN);

        return $content[$keyToRead] ?? $content;
    }

    /**
     * @param string $keyToWrite
     * @param array  $toWrite
     */
    public function write(string $keyToWrite, array $toWrite): void
    {
        flock($this->resource, LOCK_EX | LOCK_SH);

        $filesize = filesize($this->fileName);
        $content = ($filesize === 0)
            ? []
            : json_decode(fread($this->resource, $filesize), true);

        if (!empty($content[$keyToWrite])) {
            throw new RuntimeException("The key($keyToWrite) have been already set, in file: {$this->fileName}");
        }
        $content[$keyToWrite] = $toWrite;

        ftruncate($this->resource, 0);
        fseek($this->resource, 0);
        fwrite($this->resource, json_encode($content));

        flock($this->resource, LOCK_UN);
    }

    /**
     * @param string $keyToUpdate
     * @param array  $toUpdate
     */
    public function update(string $keyToUpdate, array $toUpdate): void
    {
        flock($this->resource, LOCK_EX | LOCK_SH);

        $filesize = filesize($this->fileName);
        $content = ($filesize === 0)
            ? []
            : json_decode(fread($this->resource, $filesize), true);

        $content[$keyToUpdate] = $toUpdate;

        ftruncate($this->resource, 0);
        fseek($this->resource, 0);
        fwrite($this->resource, json_encode($content));

        flock($this->resource, LOCK_UN);
    }

    /**
     * @param string $keyToDelete
     */
    public function delete(string $keyToDelete): void
    {
        flock($this->resource, LOCK_EX | LOCK_SH);

        $filesize = filesize($this->fileName);
        $content = ($filesize === 0)
            ? []
            : json_decode(fread($this->resource, $filesize), true);

        if (isset($content[$keyToDelete])) {
            unset($content[$keyToDelete]);
        }

        ftruncate($this->resource, 0);
        fseek($this->resource, 0);
        fwrite($this->resource, json_encode($content));

        flock($this->resource, LOCK_UN);
    }
}
