<?php

namespace App\Storage;

use App\Entity\Files;

/**
 * Interface FileStorageInterface
 * @package App\Storage
 */
interface FileStorageInterface
{
    /**
     * @param Files $file
     * @return mixed
     */
    public function get(Files $file);

    /**
     * @param string $filePath
     * @param int $id
     * @return bool
     */
    public function create(string $filePath, int $id): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}