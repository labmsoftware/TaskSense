<?php

declare(strict_types = 1);

namespace App\Domain\Trait;

/**
 * Used to store the Created and Updated value for an entity.
 */

use DateTime;
use Doctrine\ORM\Mapping\Column;

trait HasCreatedUpdatedTrait
{
    #[Column(type: 'datetime', updatable: false)]
    private DateTime $created;

    #[Column(type: 'datetime')]
    private DateTime $updated;

    // Retrieve the $created value.
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    // Store a $created value.
    // NB: This can only be issued once per entity.
    public function setCreated(DateTime $created): self
    {
        $this->created = $created;

        return $this;
    }

    // Retrieve the $updated value.
    public function getUpdated(): DateTime
    {
        return $this->updated;
    }

    // Store an $updated value.
    public function setUpdated(DateTime $updated): self
    {
        $this->updated = $updated;

        return $this;
    }
}