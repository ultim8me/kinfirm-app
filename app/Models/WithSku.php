<?php

namespace App\Models;

/**
 * Trait WithSku
 *
 * For SKU logic
 *
 * @package App\Models
 */
trait WithSku
{
    const SKU = 'sku';
    const PREFIX = 'KF';

    protected static function bootWithSku(): void
    {
        static::creating(fn (self $model) => $model->assignSku());
    }

    public function getSku(): string
    {
        return self::PREFIX . str_pad(
            $this->id,
            max(strlen((string)$this->id), 4),
            "0",
            STR_PAD_LEFT
        );
    }

    private function assignSku(): void
    {
        if (!isset($this->{self::SKU})) {
            $this->{self::SKU} = $this->getSku();
        }
    }

    public function getRouteKeyName(): string
    {
        return self::SKU;
    }
}
