<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StoreController extends AbstractController
{
    #[Route('/', name: 'store_home')]
    #[Route('/index.html', name: 'store_home_legacy')]
    public function index(): Response
    {
        return $this->render('store/index.html.twig');
    }

    #[Route('/login', name: 'store_login')]
    #[Route('/login.html', name: 'store_login_legacy')]
    public function login(): Response
    {
        return $this->render('store/login.html.twig');
    }

    #[Route('/profile', name: 'store_profile')]
    #[Route('/profile.html', name: 'store_profile_legacy')]
    public function profile(): Response
    {
        return $this->render('store/profile.html.twig');
    }

    #[Route('/product-details', name: 'store_product_details')]
    #[Route('/product_details.html', name: 'store_product_details_legacy')]
    #[Route('/details.html', name: 'store_product_details_source_legacy')]
    public function productDetails(Request $request, ProductRepository $productRepository): Response
    {
        $slug = $request->query->getString('slug');
        $product = $slug !== ''
            ? $productRepository->findOneBy(['slug' => $slug])
            : null;

        if (null === $product) {
            $legacyId = $request->query->getInt('id');
            $product = $legacyId > 0
                ? $productRepository->find($legacyId)
                : $productRepository->findFirstProduct();
        }

        if (null === $product) {
            throw $this->createNotFoundException('Product not found.');
        }

        return $this->render('store/product_details.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/categories', name: 'store_categories')]
    #[Route('/browse_categories.html', name: 'store_categories_legacy')]
    #[Route('/home.html', name: 'store_categories_source_legacy')]
    public function categories(CategoryRepository $categoryRepository): Response
    {
        return $this->render('store/browse_categories.html.twig', [
            'categories' => $categoryRepository->findAllOrdered(),
        ]);
    }

    #[Route('/cart', name: 'store_cart')]
    #[Route('/cart.html', name: 'store_cart_legacy')]
    public function cart(): Response
    {
        return $this->render('store/cart.html.twig');
    }

    #[Route('/products-by-category', name: 'store_products_by_category')]
    #[Route('/products_by_category.html', name: 'store_products_by_category_legacy')]
    #[Route('/categories.html', name: 'store_products_by_category_source_legacy')]
    public function productsByCategory(
        Request $request,
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
    ): Response {
        $slug = $request->query->getString('slug');

        if ('' === $slug) {
            $slug = $request->query->getString('category');
        }

        $category = '' !== $slug
            ? $categoryRepository->findOneBy(['slug' => $slug])
            : $categoryRepository->findFirstCategory();

        if (null === $category) {
            throw $this->createNotFoundException('Category not found.');
        }

        return $this->render('store/products_by_category.html.twig', [
            'category' => $category,
            'products' => $productRepository->findByCategoryOrdered($category),
        ]);
    }
}
