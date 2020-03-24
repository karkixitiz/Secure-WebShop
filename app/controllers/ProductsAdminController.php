<?php

  require_once __DIR__ . '/../models/Product.php';

  /**
   * Controller for handling functionalities regarding
   * products management for administrators
   */
  class ProductsAdminController {
    protected $productModel;

    public function __construct()
    {
      // Display a generic 404 message for non-admins
      if (!isAdmin()) {
        $pageTitle = 'Page Not Found';
        require_once __DIR__ . '/../views/404.php';
        die;
      }

      $this->productModel = new Product;
    }

    /**
     * Display view for adding a new product
     */
    public function create()
    {
      $pageTitle = 'Add a new product';

      require_once __DIR__ . '/../views/products/createEdit.php';
    }

    // Test file extension, MIME-Header Check etc

    /**
     * Logic for adding a new product
     */
    public function store()
    {
      if (!$this->isInputValidated()) {
        $_SESSION['success'] = 'Validation error';
        redirect('/admin/products/create');
      }

      try {
        $fileName = $this->uploadImage($_FILES["image"]);
      } catch (Exception $e) {
        $_SESSION['success'] = "Sorry, there was an error uploading your file.";
        redirect('/admin/products/create');
      }

      $productData = $this->sanitizeInput();
      $productData['image'] = $fileName;

      $isCreated = $this->productModel->create($productData);

      if ($isCreated) {
        $_SESSION['success'] = 'The product was successfully added';
        redirect('/products');
      } else {
        $_SESSION['success'] = 'The product couldn\'t be added';
        redirect('/admin/products/create');
      }
    }

    /**
     * Show view for existing product
     * @param $productId
     * @throws Exception
     */
    public function edit($productId)
    {
      $pageTitle = 'Edit a product';
      $product = $this->productModel->find($productId);

      if (!$product) {
        throw new Exception('Product not found');
      }

      require_once __DIR__ . '/../views/products/createEdit.php';
    }

    /**
     * Logic for updating product
     * @param $productId
     * @throws Exception
     */
    public function update($productId)
    {
      $product = $this->productModel->find($productId);

      if (!$product) {
        throw new Exception('Product not found');
      }

      if (!$this->isInputValidated()) {
        $_SESSION['success'] = 'Validation error';
        redirect("/admin/products/{$productId}/edit");
      }

      $previousImage = getcwd() . '/public/images/uploads/' . $product['image'];

      try {
        $fileName = $this->uploadImage($_FILES["image"]);
      } catch (Exception $e) {
        $_SESSION['success'] = "Sorry, there was an error uploading your file.";
        redirect('/admin/products/create');
      }

      $updatedProductData = $this->sanitizeInput();

      if ($fileName === '') {
        $isImageChanged = false;
        $updatedProductData['image'] = $product['image'];
      } else {
        $isImageChanged = true;
        $updatedProductData['image'] = $fileName;
      }

      $isUpdated = $this->productModel->update($productId, $updatedProductData);

      if ($isUpdated) {
        if ($isImageChanged && file_exists($previousImage)) {
          unlink($previousImage);
        }
        $_SESSION['success'] = 'The product was successfully updated';
        redirect('/products');
      } else {
        $_SESSION['success'] = 'The product couldn\'t be updated';
        redirect("/admin/products/{$productId}/edit");
      }
    }

    public function destroy($productId)
    {
      $product = $this->productModel->find($productId);
      $imageName = $product['image'];

      $isDeleted = $this->productModel->destroy($productId);

      $image = getcwd() . '/public/images/uploads/' . $imageName;
      if ($isDeleted) {
        file_exists($image) && unlink($image);

        $_SESSION['success'] = 'The product was successfully deleted';
      } else {
        $_SESSION['success'] = 'The product couldn\'t be deleted';
      }
      redirect('/products');
    }

    /**
     * Check if the input data provided by the user is validated
     */
    private function isInputValidated()
    {
      $validationParams = [
        'quantity' => FILTER_VALIDATE_INT,
        'price'    => FILTER_VALIDATE_FLOAT,
      ];

      $validationResults = filter_var_array($_POST, $validationParams);
      $validationResults['name'] = $_POST['name'] !== '';

      // if validation fails for any field false is set as its
      // data, so we check if any of the fields' value is false
      return !in_array(false, $validationResults);
    }

    /**
     * Sanitize user provided input before
     * updating them to database
     */
    private function sanitizeInput()
    {
      // remove whitespaces to the left and right of every values
      $_POST = array_map('trim', $_POST);

      $sanitizationParams = [
        'name'        => FILTER_SANITIZE_STRING,
        'description' => FILTER_SANITIZE_STRING,
        'image'       => FILTER_UNSAFE_RAW,
        'price'       => [
          'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
          'flags'  => FILTER_FLAG_ALLOW_FRACTION
        ],
        'quantity'    => FILTER_SANITIZE_NUMBER_INT,
      ];

      $product = filter_var_array($_POST, $sanitizationParams);

      // since keys and values are not sent if checkbox is not
      // checked, we need to manually set the value
      $product['is_featured'] = isset($_POST['is-featured']);

      return $product;
    }

    /**
     * Upload image
     * @param $imageFile
     * @return string
     * @throws Exception
     */
    private function uploadImage($imageFile)
    {
      // If no file is uploaded
      if ($imageFile['name'] === '') {
        return '';
      }

      $target_dir = getcwd() . '/public/images/uploads/';

      $target_file = $target_dir . basename($imageFile["name"]);
      $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

      //check whitelist
      if ($this->checkFileExtension($imageFileType)) {
        throw new Exception('Sorry, This file extension is not allowed');
      }

      //check file size
      if ($imageFile["size"] > 4097152) {
        throw new Exception('Sorry, Image size is  too large');
      }

      //create a new unique file name
      $name = $this->random_string();
      $new_name = $name . "." . $imageFileType;
      $target_dir_imagename = $target_dir . $new_name;

      if (!move_uploaded_file($imageFile["tmp_name"], $target_dir_imagename)) {
        throw new Exception('Sorry, there was an error uploading your file.');
      }

      return $new_name;
    }

    private function checkFileExtension($extension)
    {
      $whitelist = array("jpg", "jpeg", "gif", "png", "pdf");

      return !in_array($extension, $whitelist);
    }

    private function random_string()
    {
      if (function_exists('random_bytes')) {
        $bytes = random_bytes(16);
        $str = bin2hex($bytes);
      } else if (function_exists('openssl_random_pseudo_bytes')) {
        $bytes = openssl_random_pseudo_bytes(16);
        $str = bin2hex($bytes);
      } else if (function_exists('mcrypt_create_iv')) {
        $bytes = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
        $str = bin2hex($bytes);
      } else {
        //change secret_string to an random string with >12 chars
        $str = md5(uniqid('HZJx~37%v=I9BJG!>P_S44O{%mL>G>H9', true));
      }

      return $str;
    }
  }
