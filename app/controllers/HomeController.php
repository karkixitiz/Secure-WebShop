<?php

require_once __DIR__ . '/../models/Product.php';

class HomeController
{
  protected $productModel;

  /**
   * Load view for the homepage
   */
  public function index() {
    $pageTitle = 'Home';
    $siteName = 'Web Shop';

    $this->productModel = new Product;

    $products = $this->productModel->getAll(3);

    $featuredProducts = $this->productModel->getFeatured();

    require_once __DIR__ . '/../views/home.php';
  }

  /**
   * Load view for the about us page
   */
  public function aboutUs()
  {
    $pageTitle = 'About Us';

    require_once __DIR__ . '/../views/aboutUs.php';
  }

  /**
   * Load view for 404 requests
   */
  public function pageNotFound() {
    $pageTitle = 'Page Not Found';

    require_once __DIR__ . '/../views/404.php';
  }
}
