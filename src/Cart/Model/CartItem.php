<?php

namespace App\Cart\Model;

use App\Entity\Product;

final class CartItem
{
    public function __construct(
        private int $id,
        private Product $product,
        private float $price,
        private int $quantity,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function increaseQuantity(int $quantity): void
    {
        $this->quantity += $quantity;
    }

    public function getLineTotal(): float
    {
        return $this->price * $this->quantity;
    }
}
