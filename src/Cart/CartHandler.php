<?php

namespace App\Cart;

use App\Cart\Model\Cart;
use App\Cart\Model\CartItem;
use App\Entity\Product;
use App\Cart\Strategy\SessionCart;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class CartHandler
{
    private const DEFAULT_IDENTIFIER = 'store-cart';

    public function __construct(
        #[Autowire(service: SessionCart::class)]
        private readonly CartInterface $strategy,
    ) {
    }

    public function addProduct(Product $product, int $quantity): Cart
    {
        return $this->strategy->add($this->createCartItem($product, $quantity), $this->getCart());
    }

    public function removeProduct(Product $product): Cart
    {
        return $this->strategy->remove($this->createCartItem($product, 1), $this->getCart());
    }

    public function getCart(): Cart
    {
        return $this->strategy->getCart(self::DEFAULT_IDENTIFIER);
    }

    public function clearCart(): void
    {
        $this->strategy->clearCart(self::DEFAULT_IDENTIFIER);
    }

    private function createCartItem(Product $product, int $quantity): CartItem
    {
        return new CartItem(
            id: $product->getId() ?? 0,
            product: $product,
            price: (float) $product->getPrice(),
            quantity: $quantity,
        );
    }
}
