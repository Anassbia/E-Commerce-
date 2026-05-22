<?php

namespace App\Cart\Model;

final class Cart
{
    /**
     * @param CartItem[] $cartItems
     */
    public function __construct(
        private string $identifier,
        private \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
        private array $cartItems = [],
    ) {
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return CartItem[]
     */
    public function getCartItems(): array
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): void
    {
        foreach ($this->cartItems as $existingItem) {
            if ($existingItem->getProduct()->getId() === $cartItem->getProduct()->getId()) {
                $existingItem->increaseQuantity($cartItem->getQuantity());

                return;
            }
        }

        $this->cartItems[] = $cartItem;
    }

    public function removeCartItem(CartItem $cartItem): void
    {
        $this->cartItems = array_values(array_filter(
            $this->cartItems,
            static fn (CartItem $existingItem): bool => $existingItem->getProduct()->getId() !== $cartItem->getProduct()->getId(),
        ));
    }

    public function total(): float
    {
        $total = 0.0;

        foreach ($this->cartItems as $item) {
            $total += $item->getPrice() * $item->getQuantity();
        }

        return $total;
    }
}
