<?php

namespace App\Cart\Strategy;

use App\Cart\CartInterface;
use App\Cart\Model\Cart;
use App\Cart\Model\CartItem;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class SessionCart implements CartInterface
{
    private const SESSION_KEY_PREFIX = 'cart.';

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ProductRepository $productRepository,
    ) {
    }

    public function add(CartItem $item, Cart $cart): Cart
    {
        $storedCart = $this->readCart($cart->getIdentifier());
        $productId = $item->getProduct()->getId();

        if (null === $productId) {
            return $this->getCart($cart->getIdentifier());
        }

        $storedCart['items'][$productId] = ($storedCart['items'][$productId] ?? 0) + $item->getQuantity();
        $this->writeCart($cart->getIdentifier(), $storedCart);

        return $this->getCart($cart->getIdentifier());
    }

    public function remove(CartItem $item, Cart $cart): Cart
    {
        $storedCart = $this->readCart($cart->getIdentifier());
        $productId = $item->getProduct()->getId();

        if (null !== $productId) {
            unset($storedCart['items'][$productId]);
            $this->writeCart($cart->getIdentifier(), $storedCart);
        }

        return $this->getCart($cart->getIdentifier());
    }

    public function getCart(string $identifier): Cart
    {
        $storedCart = $this->readCart($identifier);
        $products = $this->productRepository->findBy([
            'id' => array_map('intval', array_keys($storedCart['items'])),
        ]);

        $productsById = [];

        foreach ($products as $product) {
            $productId = $product->getId();

            if (null !== $productId) {
                $productsById[$productId] = $product;
            }
        }

        $items = [];

        foreach ($storedCart['items'] as $productId => $quantity) {
            $product = $productsById[(int) $productId] ?? null;

            if (null === $product) {
                continue;
            }

            $items[] = new CartItem(
                id: (int) $productId,
                product: $product,
                price: (float) $product->getPrice(),
                quantity: (int) $quantity,
            );
        }

        return new Cart(
            identifier: $identifier,
            createdAt: new \DateTimeImmutable($storedCart['created_at']),
            cartItems: $items,
        );
    }

    public function clearCart(string $identifier): void
    {
        $this->getSession()->remove($this->getSessionKey($identifier));
    }

    /**
     * @return array{created_at: string, items: array<int, int>}
     */
    private function readCart(string $identifier): array
    {
        /** @var array{created_at?: string, items?: array<int, int>} $storedCart */
        $storedCart = $this->getSession()->get($this->getSessionKey($identifier), [
            'created_at' => (new \DateTimeImmutable())->format(DATE_ATOM),
            'items' => [],
        ]);

        $storedCart['created_at'] ??= (new \DateTimeImmutable())->format(DATE_ATOM);
        $storedCart['items'] ??= [];

        return $storedCart;
    }

    /**
     * @param array{created_at: string, items: array<int, int>} $cart
     */
    private function writeCart(string $identifier, array $cart): void
    {
        $this->getSession()->set($this->getSessionKey($identifier), $cart);
    }

    private function getSession(): SessionInterface
    {
        $session = $this->requestStack->getSession();

        if (null === $session) {
            throw new \RuntimeException('No session is available.');
        }

        return $session;
    }

    private function getSessionKey(string $identifier): string
    {
        return self::SESSION_KEY_PREFIX.$identifier;
    }
}
