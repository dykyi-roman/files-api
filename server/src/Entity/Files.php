<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition()
 *
 * @ORM\Entity
 * @ORM\Table(name="files2")
 */
class Files
{
    /**
     * @SWG\Property(format="integer")
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
        return $this->id;
    }

    public function getName()
    {
        return $this->filename;
    }

    public function setName(string $value)
    {
        $this->filename = $value;
    }

    public function setActive(bool $value)
    {
        $this->active = $value;
    }
}