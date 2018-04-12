<?php

namespace App\Storage;

use App\Entity\Files;
use MogileFs\Client\FileClient;
use http\Exception\RuntimeException;
use MogileFs\Connection;
use MogileFs\File\LocalFile;

/**
 * Class MogileFileStorage
 * @package App\Storage
 */
class MogileFileStorage implements FileStorageInterface
{
    const DEFAULT_CLASS = 'assets';

    /** @var Connection */
    private $client;

    /** @var string - domain */
    private $namespace;

    public function __construct()
    {
        $headers = apache_request_headers();
        $namespace = $headers['namespace'] ?? '';
        if (isset($headers['env']) && $headers['env'] === 'dev')
        {
            $namespace .= '_dev';
        }

        $this->namespace = $namespace;
        $this->client = new Connection([
            [
                'host' => getenv('STORAGE_URL_1'),
                'port' => 7001
            ],
            [
                'host' => getenv('STORAGE_URL_2'),
                'port' => 7001
            ],
            [
                'host' => getenv('STORAGE_URL_3'),
                'port' => 7001
            ]
        ]);
        $this->client->connect();

        if (!$this->client->isConnected()){
            throw new \RuntimeException('Error connect with Mogile storage');
        }
    }

    /**
     * @param Files $file
     * @return array
     * @throws \Exception
     */
    public function get(Files $file)
    {
        $fileClient = new FileClient($this->client, $this->namespace);
        $filePath = $fileClient->get($file->getId());
        $fileName = $filePath['path' . random_int(1, $filePath['paths'])];

       return [
           'path' => $fileName,
           'name' => $file->getName()
       ];
    }

    /**
     * @param string $filePath
     * @param int $fileId
     * @return bool
     */
    public function create(string $filePath, int $fileId): bool
    {
        $fileClient = new FileClient($this->client, $this->namespace);
        $file = new LocalFile($filePath, self::DEFAULT_CLASS);

        return $fileClient->upload($fileId, $file);
    }

    /**
     * @param int $fileId
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function delete(int $fileId): bool
    {
        try {
            $fileClient = new FileClient($this->client, $this->namespace);
            return $fileClient->delete($fileId);
        } catch (RuntimeException $e) {
            return false;
        }
    }
}