<?php

namespace App\Storage;

use App\Entity\Files;
use MogileFs\Client\FileClient;
use http\Exception\RuntimeException;
use MogileFs\Connection;
use MogileFs\File\LocalFile;
use Symfony\Component\HttpFoundation\Response;

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

    public function __construct(string $namespace)
    {
        $headers = apache_request_headers();
        if ($headers['env'] === 'dev')
        {
            $namespace .= '_dev';
        }

        $this->namespace = $namespace;
        $this->client = new Connection([
            [
                'host' => 'mogtracker1.ua',
                'port' => 7001
            ],
            [
                'host' => 'mogtracker2.ua',
                'port' => 7001
            ],
            [
                'host' => 'mogtracker3.ua',
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
     * @return Response
     * @throws \Exception
     */
    public function get(Files $file)
    {
        $fileClient = new FileClient($this->client, $this->namespace);
        $filePath = $fileClient->get($file->getId());
        $fileName = $filePath['path' . random_int(1, $filePath['paths'])];

        return new Response(file_get_contents($fileName), 200, [
                'X-Accel-Redirect'    => "/mogdl/?" . $fileName,
                'Content-type'        => 'application/octet-stream',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $file->getName())]
        );
    }

    /**
     * @param string $filePath
     * @param int $id
     * @return bool
     */
    public function create(string $filePath, int $id): bool
    {
        $fileClient = new FileClient($this->client, $this->namespace);
        $file = new LocalFile($filePath, self::DEFAULT_CLASS);

        return $fileClient->upload($id, $file);
    }

    /**
     * @param int $id
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function delete(int $id): bool
    {
        try {
            $fileClient = new FileClient($this->client, $this->namespace);
            return $fileClient->delete($id);
        } catch (RuntimeException $e) {
            return $e->getMessage();
        }
    }
}
