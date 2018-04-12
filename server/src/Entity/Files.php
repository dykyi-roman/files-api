<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition()
 *
 * @ORM\Entity
 * @ORM\Table(name="files")
 */
class Files
{
    /**
     * @SWG\Property(format="integer")
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $file_id;

    /**
     * @SWG\Property(format="string")
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @SWG\Property(format="boolean")
     * @ORM\Column(type="boolean")
     */
    private $active;

    public function getId()
    {
        return $this->file_id;
    }

    public function getName(): string
    {
        return $this->filename;
    }

    public function setName(string $value)
    {
        $this->filename = $value;
    }

    public function setActive(bool $value)
    {
        $this->filename = $value;
    }

    public function getActive(): bool
    {
        return $this->active;
    }
}