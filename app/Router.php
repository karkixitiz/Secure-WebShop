<?php

  class Router {
    /**
     * The current requested URI
     */
    protected $currentUri;
    /**
     * Indication of whether current request is POST or not
     */
    protected $isPostRequest;
    protected $authController;
    protected $basketController;
    protected $homeController;
    protected $productsController;
    protected $productsAdminController;

    /**
     * Run the router
     */
    public function run()
    {
      $this->loadControllers();
      $this->currentUri = $this->getCurrentUri();
      $this->isPostRequest = $_SERVER['REQUEST_METHOD'] == 'POST';
      $this->mapRouteToController();
    }

    /**
     * Get the current URI user has browsed to
     */
    public function getCurrentUri()
    {
      $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
      $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
      if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
      $uri = '/' . trim($uri, '/');

      return $uri;
    }

    /**
     * Load and register all the required controllers
     *
     * @return void
     */
    public function loadControllers()
    {
      // Load all php files from the controllers directory whose filename end with Controller.php
      foreach (glob('app/controllers/*Controller.php') as $controller) {
        require_once $controller;
      }
      $this->authController = new AuthController();
      $this->homeController = new HomeController();
      $this->productsController = new ProductsController();

      $this->basketController = new BasketController();
    }

    /**
     * Run the appropriate controller method based on the current uri
     * @return void
     */
    public function mapRouteToController()
    {
      $this->handlePlainUrls();
      $this->handleDynamicUrls();
      $this->homeController->pageNotFound();
    }

    /**
     * check user is and give access
     */
    function isUserLoggedIn()
    {
      return isset($_SESSION['user_id']);
    }

    /**
     * Handle URLs that contain no dynamic parts
     */
    public function handlePlainUrls()
    {
      switch ($this->currentUri) {
        case '/':
          $this->homeController->index();
          die;

        case '/about-us':
          $this->homeController->aboutUs();
          die;

        case '/login':
          if (!$this->isUserLoggedIn()) {
            if ($this->isPostRequest) {
              $this->authController->userLoginPost();
            } else {
              $this->authController->userLogin();
            }
          } else {
            redirect('/');
          }
          die;

        case '/register':
          if (!$this->isUserLoggedIn()) {
            if ($this->isPostRequest) {
              $this->authController->userRegisterPost();
            } else {
              $this->authController->userRegister();
            }
          } else {
            redirect('/');
          }
          die;

        case '/changepassword':
          if ($this->isUserLoggedIn()) {
            if ($this->isPostRequest) {
              $this->authController->changePasswordUpdate();
            } else {
              $this->authController->changePassword();
            }
          } else {
            redirect('/');
          }
          die;

        case '/forgetpassword':
          if ($this->isPostRequest) {
            $this->authController->ForgetPasswordUpdate();
          } else {
            $this->authController->ForgetPassword();
          }
          die;

        case '/resetpassword':
          if ($this->isPostRequest) {
            $this->authController->ResetPasswordUpdate();
          } else {
            $this->authController->ResetPassword();
          }
          die;

        case '/logout':
          if ($this->isPostRequest) {
            $this->authController->userLogout();
          } else {
            $this->homeController->pageNotFound();
          }
          die;

        case '/add_to_card':
          $this->basketController->add_to_card();
          die;

        case '/home':
          $this->homeController->index();
          die;

        case '/checkout':
          if ($this->isUserLoggedIn()) {
            redirect('/checkout/address');
          } else {
            $_SESSION['success'] = 'Please login to perform checkout';
            redirect('/login');
          }
          die;

        case '/checkout/address':
          if ($this->isPostRequest) {
            $this->productsController->processShippingInfo();
          } else {
            $this->productsController->showShippingInfoForm();
          }
          die;

        case '/checkout/payment':
          if ($this->isPostRequest) {
            $this->productsController->processPayment();
          } else {
            $this->productsController->showPaymentOptions();
          }
          die;

        case '/checkout/confirm':
          if ($this->isPostRequest) {
            $this->productsController->confirmCheckout();
          } else {
            $this->productsController->showCheckoutConfirmation();
          }
          die;

        case '/products':
          $this->productsController->index();
          die;

        case '/clear-cart':
          $this->productsController->clearShoppingCart();
          redirect('/');
          die;

        case '/admin/vieworders':
          if (isAdmin()) {
            $this->productsController->viewOrders();
          } else {
            require_once __DIR__ . '/views/404.php';
          }
          die;

        case '/admin/products/create':
          $this->productsAdminController = new ProductsAdminController();

          if ($this->isPostRequest) {
            $this->productsAdminController->store();
          } else {
            $this->productsAdminController->create();
          }
          die;
      }
    }

    /**
     * Handle URLs that contain dynamic parts such as
     * /admin/products/:productId/edit
     */
    public function handleDynamicUrls()
    {
      if (preg_match('/show_basket/', $this->currentUri, $matches)) {

        $this->basketController->show_basket();
        die;
      }
      // if URL is of format /products/:productId
      if (preg_match('/^\/products\/(?<productId>\d+)\/?$/', $this->currentUri, $matches)) {
        $this->productsController->show($matches['productId']);
      } else {
        // For dynamic admin URLs
        $this->productsAdminController = new ProductsAdminController();

        if (preg_match('/^\/admin\/products\/(?<productId>\d+)\/edit\/?$/', $this->currentUri, $matches)) {
          // if URL is of format /admin/products/:productId/edit
          if ($this->isPostRequest) {
            $this->productsAdminController->update($matches['productId']);
          } else {
            $this->productsAdminController->edit($matches['productId']);
          }
        } else if (preg_match('/^\/admin\/products\/(?<productId>\d+)\/delete\/?$/', $this->currentUri, $matches)) {
          // if URL is of format /admin/products/:productId/delete
          if ($this->isPostRequest) {
            $this->productsAdminController->destroy($matches['productId']);
          }
        }

      }
      die;
    }
  }
