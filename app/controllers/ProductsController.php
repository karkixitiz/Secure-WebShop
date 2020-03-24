<?php

  require_once __DIR__ . '/../models/Auth.php';
  require_once __DIR__ . '/../models/Product.php';
  require_once __DIR__ . '/../models/Orders.php';

  class ProductsController {
    protected $authModel;
    protected $productModel;
    protected $orderModel;

    /**
     * List of all the German states(Bundesländer)
     */
    protected $germanStates;

    public function __construct()
    {
      $this->productModel = new Product;
      $this->authModel = new Auth;
      $this->orderModel = new Orders;

      $this->germanStates = [
        'Baden-Württemberg',
        'Bayern (Bavaria)',
        'Berlin',
        'Brandenburg',
        'Bremen',
        'Hamburg',
        'Hessen',
        'Mecklenburg-Vorpommern (Mecklenburg-Western Pomerania)',
        'Niedersachsen (Lower Saxony)',
        'Nordrhein-Westfalen (North Rhine-Westphalia)',
        'Rheinland-Pfalz (Rhineland-Palantinate)',
        'Saarland',
        'Sachsen (Saxony)',
        'Sachsen-Anhalt (Saxony-Anhalt)',
        'Schleswig-Holstein',
        'Thüringen (Thuringia)',
      ];
    }

    public function viewOrders()
    {
      $pageTitle = 'Home';
      $siteName = 'Web Shop';

      $this->productModel = new Product;

      $products = $this->productModel->getAll(3);

      $featuredProducts = $this->productModel->getFeatured();
      $orderids = $this->orderModel->getAllOrderId();

      $allOrders = [];
      foreach ($orderids as $singleorderid) {
        $order_id = $singleorderid['order_id'];
        $orders = $this->orderModel->getAllbyOrderId($order_id);

        $allOrders[] = $orders;
      }

      require_once __DIR__ . '/../views/admin/orders.php';
    }

    public function index()
    {
      $allorders = array();
      $pageTitle = 'All Products';
      $products = $this->productModel->getAll();

      require_once __DIR__ . '/../views/products/list.php';
    }

    public function show($productId)
    {
      $product = $this->productModel->find($productId);
      $pageTitle = "{$product['name']} (Product)";

      require_once __DIR__ . '/../views/products/show.php';
    }

    /**
     * Show form for displaying/editing user's shipping information
     */
    public function showShippingInfoForm()
    {
      $pageTitle = 'Step 1/3 - Checkout';

      // if the user is in this step, they need to confirm
      // the address to move to next step
      $_SESSION['is_shipping_address_confirmed'] = false;

      $products = array();
      if (isset($_SESSION["shopping_cart"])) {
        $cookie_data = stripslashes($_SESSION['shopping_cart']);
        $cart_data = json_decode($cookie_data, true);

        foreach ($cart_data as $keys => $values) {
          $productid = $values["item_id"];
          $quantity = $values['item_quantity'];
          $product = $this->productModel->find($productid);
          $product['quantity'] = $quantity;
          array_push($products, $product);

        }
      }

      // Get information about the logged in user
      $user = $this->authModel->getLoggedInUser();

      $germanStates = $this->germanStates;

      require_once __DIR__ . '/../views/checkout/addressForm.php';
    }

    /**
     * Save shipping information of the user
     */
    public function processShippingInfo()
    {
      if (!$this->isShippingDataValidated()) {
        $_SESSION['error'] = 'Please check that all required fields are filled and in correct format.';
        redirect('/checkout/address');
      }

      $shippingData = $this->sanitizeShippingData();

      $isUpdated = $this->authModel->updateShippingData($shippingData);

      if ($isUpdated) {
        $_SESSION['is_shipping_address_confirmed'] = true;
        redirect('/checkout/payment');
      } else {
        $_SESSION['error'] = 'There was error updating shipping information';
        redirect('/checkout/error');
      }
    }

    public function showPaymentOptions()
    {
      if (!isset($_SESSION['is_shipping_address_confirmed']) || !$_SESSION['is_shipping_address_confirmed']) {
        $_SESSION['error'] = 'Please confirm shipping address first.';
        redirect('/checkout/address');
      }
      $products = array();
      if (isset($_SESSION["shopping_cart"])) {
        $cookie_data = stripslashes($_SESSION['shopping_cart']);
        $cart_data = json_decode($cookie_data, true);

        foreach ($cart_data as $keys => $values) {
          $productid = $values["item_id"];
          $quantity = $values['item_quantity'];
          $product = $this->productModel->find($productid);
          $product['quantity'] = $quantity;
          array_push($products, $product);
        }
      }

      $_SESSION['is_payment_confirmed'] = false;

      $pageTitle = 'Step 2/3 - Checkout';

      require_once __DIR__ . '/../views/checkout/paymentForm.php';
    }

    public function processPayment()
    {
      $paymentData = $this->sanitizePaymentData();

      // save card details just to session and not to db
      // so that it's more secure
      $_SESSION['payment'] = [];
      $_SESSION['payment']['type'] = $paymentData['payment-type'];

      if ($paymentData['payment-type'] === 'credit-card') {
        $_SESSION['payment']['card-number'] = $paymentData['card-number'];
        $_SESSION['payment']['card-expires'] = "{$paymentData['expiry-month']}/{$paymentData['expiry-year']}";
        $_SESSION['payment']['cvv'] = $paymentData['cvv'];
      }

      $_SESSION['is_payment_confirmed'] = true;

      // TODO: Validate and process payment
      redirect('/checkout/confirm');
    }

    public function showCheckoutConfirmation()
    {
      if (!isset($_SESSION['is_payment_confirmed']) || !$_SESSION['is_payment_confirmed']) {
        $_SESSION['error'] = 'Please fill in payment details first.';
        redirect('/checkout/payment');
      }

      $pageTitle = 'Step 3/3 - Checkout';

      $products = array();
      if (isset($_SESSION["shopping_cart"])) {
        $cookie_data = stripslashes($_SESSION['shopping_cart']);
        $cart_data = json_decode($cookie_data, true);

        foreach ($cart_data as $keys => $values) {
          $productid = $values["item_id"];
          $quantity = $values['item_quantity'];
          $product = $this->productModel->find($productid);
          $product['quantity'] = $quantity;
          array_push($products, $product);

        }
      }

      // Get information about the logged in user
      // for displaying shipping information
      $user = $this->authModel->getLoggedInUser();

      $paymentData = $this->getPaymentData();

      require_once __DIR__ . '/../views/checkout/confirmation.php';
    }

    public function confirmCheckout()
    {
      $pageTitle = 'Purchase Success';
      $p_id = $_POST['p_id'];
      $p_quantity = $_POST['p_quantity'];
      $u_id = $_SESSION['user_id'];

      $rand = rand(1111111, 9999999);
      foreach ($p_id as $key => $row) {
        $pro_id = $row;
        $pro_quantity = $p_quantity[$key];
        $this->orderModel->create($pro_id, $pro_quantity, $u_id, $rand);
      }

      $products = array();
      if (isset($_SESSION["shopping_cart"])) {
        $cookie_data = stripslashes($_SESSION['shopping_cart']);
        $cart_data = json_decode($cookie_data, true);
        foreach ($cart_data as $keys => $values) {
          $productid = $values["item_id"];
          $quantity = $values['item_quantity'];
          $product = $this->productModel->find($productid);
          $product['quantity'] = $quantity;
          array_push($products, $product);
        }
      }

      $this->clearShoppingCart();

      // clear payment data on successful payment
      if (isset($_SESSION['payment'])) {
        unset($_SESSION['payment']);
      }

      // Get information about the logged in user
      // for displaying shipping information
      $user = $this->authModel->getLoggedInUser();
      require_once __DIR__ . '/../views/checkout/checkoutSuccess.php';
    }

    public function clearShoppingCart()
    {
      // Remove the cart data as it is no more needed
      if (isset($_SESSION['shopping_cart'])) {
        unset($_SESSION['shopping_cart']);
      }
    }

    /**
     * Validate that the shipping data is in correct form
     */
    private function isShippingDataValidated()
    {
      $validationParams = [
        'zipCode' => FILTER_VALIDATE_INT,
      ];
      $validationResults = filter_var_array($_POST, $validationParams);
      $validationResults['firstName'] = $_POST['firstName'] !== '';
      $validationResults['lastName'] = $_POST['lastName'] !== '';
      $validationResults['address'] = $_POST['address'] !== '';
      $validationResults['state'] = $_POST['state'] !== '';

      // if validation fails for any field false is set as its
      // data, so we check if any of the fields' value is false
      return !in_array(false, $validationResults);
    }

    /**
     * Sanitize user provided input before
     * updating them to database
     */
    private function sanitizeShippingData()
    {
      // remove whitespaces to the left and right of every values
      $_POST = array_map('trim', $_POST);

      $sanitizationParams = [
        'firstName' => FILTER_SANITIZE_STRING,
        'lastName'  => FILTER_SANITIZE_STRING,
        'address'   => FILTER_SANITIZE_STRING,
        'address2'  => FILTER_SANITIZE_STRING,
        'state'     => FILTER_SANITIZE_STRING,
        'zipCode'   => FILTER_SANITIZE_NUMBER_INT,
      ];

      $shippingData = filter_var_array($_POST, $sanitizationParams);

      return $shippingData;
    }

    private function sanitizePaymentData()
    {
      // remove whitespaces to the left and right of every values
      $_POST = array_map('trim', $_POST);

      $sanitizationParams = [
        'card-number'  => FILTER_SANITIZE_STRING,
        'payment-type' => FILTER_SANITIZE_STRING,
        'expiry-month' => FILTER_SANITIZE_NUMBER_INT,
        'expiry-year'  => FILTER_SANITIZE_NUMBER_INT,
        'cvv'          => FILTER_SANITIZE_NUMBER_INT,
      ];

      $paymentData = filter_var_array($_POST, $sanitizationParams);

      return $paymentData;
    }

    private function getPaymentData()
    {
      $paymentData = $_SESSION['payment'];

      if ($paymentData['type'] === 'credit-card') {
        $paymentData['typeName'] = 'Credit Card';

        preg_match('/\d{2}$/', $paymentData['card-number'], $last2Digits);

        $paymentData['card-number-obfuscated'] = 'XXXXXXXXXXX' . $last2Digits[0];
      } else if ($paymentData['type'] === 'paypal') {
        $paymentData['typeName'] = 'Paypal';
      } else {
        $paymentData['typeName'] = 'Bank Transfer';
      }

      return $paymentData;
    }
  }
