<?php

namespace App\Entity;

class Client extends User
{
  private string $accountNumber;

  public function getAccountNumber(): string
  {
    return $this->accountNumber;
  }

  public function setAccountNumber(string $accountNumber): self
  {
    $this->accountNumber = $accountNumber;

    return $this;
  }
}
