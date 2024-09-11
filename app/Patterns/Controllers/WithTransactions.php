<?php

namespace App\Patterns\Controllers;

use Illuminate\Support\Facades\DB;

/**
 * Trait WithTransactions
 * @package App\Patterns\Controllers
 */
trait WithTransactions
{
    private bool $withTransactions = false;

    /**
     * @return bool
     */
    private function isWithTransactions(): bool
    {
        return $this->withTransactions;
    }

    /**
     * @param bool $withTransactions
     * @return $this
     */
    public function setWithTransactions(bool $withTransactions): static
    {
        $this->withTransactions = $withTransactions;
        return $this;
    }

    private function beginTransactions(): void
    {
        if ($this->isWithTransactions()) {
            DB::beginTransaction();
        }
    }

    private function commitTransactions(): void
    {
        if ($this->isWithTransactions()) {
            DB::commit();
        }
    }

    private function rollbackTransactions(): void
    {
        if ($this->isWithTransactions()) {
            DB::rollBack();
        }
    }

}
