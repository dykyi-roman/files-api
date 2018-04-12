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
     * @param int $Id
     * @return bool
     */
    public function create(string $filePath, int $Id): bool;

    /**
     * @param int $Id
     * @return bool
     */
    public function delete(int $Id): bool;
}