<?php

namespace App\Cart\Strategy;

use App\Cart\CartInterface;
use App\Cart\Model\Cart;
use App\Cart\Model\CartItem;

final class ApiCart implements CartInterface
{
    public function add(CartItem $item, Cart $cart): Cart
    {
        dd('API cart add strategy test', $item, $cart);
    }

    public function remove(CartItem $item, Cart $cart): Cart
    {
        dd('API cart remove strategy test', $item, $cart);
    }

    public function getCart(string $identifier): Cart
    {
        dd('API cart get strategy test', $identifier);
    }

    public function clearCart(string $identifier): void
    {
        dd('API cart clear strategy test', $identifier);
    }
}
