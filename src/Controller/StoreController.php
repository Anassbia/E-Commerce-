<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StoreController extends AbstractController
{
    //am supporting both legacy and modern Urls for this step (etapee 1 )
    //but this is temporpary till i turn em dynammic in step 2 -> legacy removed after
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
    public function productDetails(): Response
    {
        return $this->render('store/product_details.html.twig');
    }

    #[Route('/categories', name: 'store_categories')]
    #[Route('/browse_categories.html', name: 'store_categories_legacy')]
    #[Route('/home.html', name: 'store_categories_source_legacy')]
    public function categories(): Response
    {
        return $this->render('store/browse_categories.html.twig');
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
    public function productsByCategory(): Response
    {
        return $this->render('store/products_by_category.html.twig');
    }
}
