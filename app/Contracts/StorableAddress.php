<?php

namespace App\Contracts;

interface StorableAddress
{
    /**
     * Save the address for the model.
     *
     * @return bool
     */
    public function storeAddress(array $data): bool;
}
