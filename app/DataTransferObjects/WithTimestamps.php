<?php

namespace App\DataTransferObjects;

use DateTime;

trait WithTimestamps
{
    protected ?DateTime $created_at = null;
    protected ?DateTime $updated_at = null;
    protected ?DateTime $deleted_at = null;

    public function setCreatedAt(DateTime|string|null $created_at): static
    {
        $this->created_at = $this->getDateTime($created_at);
        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    public function setUpdatedAt(DateTime|string|null $updated_at): static
    {
        $this->updated_at = $this->getDateTime($updated_at);
        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updated_at;
    }

    public function setDeletedAt(DateTime|string|null $deleted_at): static
    {
        $this->deleted_at = $this->getDateTime($deleted_at);
        return $this;
    }

    public function getDeletedAt(): ?DateTime
    {
        return $this->deleted_at;
    }
}
