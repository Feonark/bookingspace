<?php

namespace App\Entity;

use App\Repository\ErgonomicCriteriaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ErgonomicCriteriaRepository::class)]
class ErgonomicCriteria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
