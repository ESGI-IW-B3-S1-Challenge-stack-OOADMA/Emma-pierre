<?php

namespace App\Entity;

class Employee extends User
{

  private string $matricule;

  public function getMatricule(): string
  {
    return $this->matricule;
  }

  public function setMatricule(string $matricule): self
  {
    $this->matricule = $matricule;

    return $this;
  }
}
