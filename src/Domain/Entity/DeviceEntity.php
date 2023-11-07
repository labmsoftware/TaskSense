<?php

declare(strict_types = 1);

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use App\Domain\Trait\HasUuidTrait;
use App\Domain\Trait\HasCreatedUpdatedTrait;

#[Entity()]
#[Table(name: 'devices')]
class DeviceEntity
{
    use HasUuidTrait, HasCreatedUpdatedTrait;

    #[Column(type: 'string', length: 50)]
    private string $manufacturer;

    #[Column(type: 'string', length: 50)]
    private string $model;

    #[Column(type: 'string', length: 32)]
    private string $serial_number;

    #[Column(type: 'integer', length: 16)]
    private int $imei;

    public function getManufacturer(): string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(string $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getSerialNumber(): string
    {
        return $this->serial_number;
    }

    public function setSerialNumber(string $serial_number): self
    {
        $this->serial_number = $serial_number;

        return $this;
    }

    public function getImei(): int
    {
        return $this->imei;
    }

    public function setImei(int $imei): self
    {
        $this->imei = $imei;

        return $this;
    }
}