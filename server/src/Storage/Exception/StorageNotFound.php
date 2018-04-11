<?php

namespace App\Storage\Exception;

/**
 * Class StorageNotFound
 * @package App\Storage\Exception
 */
class StorageNotFound
{
    /**
     * @param $exceptionMessage
     * @return StorageNotFound
     */
    public static function forMessage(string $exceptionMessage)
    {
        return new self(
            sprintf(
                'Could not found storage for saving file in "%s" format',
                $exceptionMessage
            )
        );
    }
}