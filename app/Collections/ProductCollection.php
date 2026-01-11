<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class ProductCollection extends Collection
{
    /**
     * Get the total value of all products in the collection.
     */
    public function totalValue(): float
    {
        return $this->sum(fn ($product) => $product->price * $product->stock);
    }
}
