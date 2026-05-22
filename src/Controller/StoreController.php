<?php

namespace App\Controller;

use App\Cart\CartHandler;
use App\Entity\Category;
use App\Entity\Product;
use App\Form\AddToCartType;
use App\Form\Model\AddToCartData;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StoreController extends AbstractController
{
    #[Route('/', name: 'store_home')]
    public function index(): Response
    {
        return $this->render('store/index.html.twig');
    }

    #[Route('/profile', name: 'store_profile')]
    public function profile(): Response
    {
        return $this->render('store/profile.html.twig');
    }

    #[Route('/product-details/{slug}', name: 'store_product_details')]
    public function productDetails(
        #[MapEntity(mapping: ['slug' => 'slug'])] Product $product,
    ): Response
    {
        $form = $this->createForm(AddToCartType::class, new AddToCartData(), [
            'action' => $this->generateUrl('store_cart_add', ['id' => $product->getId()]),
            'method' => 'POST',
        ]);

        return $this->render('store/product_details.html.twig', [
            'product' => $product,
            'addToCartForm' => $form->createView(),
        ]);
    }

    #[Route('/cart/add/{id}', name: 'store_cart_add', methods: ['POST'])]
    public function addToCart(Product $product, Request $request, CartHandler $cartHandler): RedirectResponse
    {
        $data = new AddToCartData();
        $form = $this->createForm(AddToCartType::class, $data);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addFlash('error', 'Please choose a valid quantity between 1 and 10.');

            return $this->redirectToRoute('store_product_details', ['slug' => $product->getSlug()]);
        }

        $cartHandler->addProduct($product, $data->quantity);
        $this->addFlash('success', sprintf('%s was added to your cart.', $product->getName()));

        return $this->redirectToRoute('store_cart');
    }

    #[Route('/categories', name: 'store_categories')] 
    public function categories(CategoryRepository $categoryRepository): Response
    {
        return $this->render('store/browse_categories.html.twig', [
            'categories' => $categoryRepository->findAllOrdered(),
        ]);
    }

    #[Route('/cart', name: 'store_cart')]
    public function cart(CartHandler $cartHandler): Response
    {
        return $this->render('store/cart.html.twig', [
            'cart' => $cartHandler->getCart(),
        ]);
    }

    #[Route('/cart/remove/{id}', name: 'store_cart_remove', methods: ['POST'])]
    public function removeFromCart(Product $product, CartHandler $cartHandler): RedirectResponse
    {
        $cartHandler->removeProduct($product);
        $this->addFlash('success', sprintf('%s was removed from your cart.', $product->getName()));

        return $this->redirectToRoute('store_cart');
    }

    #[Route('/cart/clear', name: 'store_cart_clear', methods: ['POST'])]
    public function clearCart(CartHandler $cartHandler): RedirectResponse
    {
        $cartHandler->clearCart();
        $this->addFlash('success', 'Your cart has been cleared.');

        return $this->redirectToRoute('store_cart');
    }

    #[Route('/products-by-category/{slug}', name: 'store_products_by_category')]
    public function productsByCategory(
        #[MapEntity(mapping: ['slug' => 'slug'])] Category $category,
        ProductRepository $productRepository,
    ): Response
    {
        return $this->render('store/products_by_category.html.twig', [
            'category' => $category,
            'products' => $productRepository->findByCategoryOrdered($category),
        ]);
    }
}
