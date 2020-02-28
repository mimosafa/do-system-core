<?php

namespace DoSystemMock\Application\Car\Data;

use DoSystem\Application\Car\Data\UpdateCarInputInterface;

class UpdateCarInputMock implements UpdateCarInputInterface
{
    public $id;
    public $vin;
    public $status;
    public $name;

   public function getId(): int
   {
       return $this->id;
   }

   public function getVin(): ?string
   {
       return $this->vin;
   }

   public function getStatus(): ?int
   {
       return $this->status;
   }

   public function getName(): ?string
   {
       return $this->name;
   }

}
