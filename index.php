<?php
    require_once 'app/helpers.php';
    require_once 'app/CsrfToken.php';
    require_once 'app/Router.php';
    require_once 'app/SessionManager.php';
    class App
    {
        protected $app_config;
        /**
         * Run the app
         */
        public function run() {
            try {
                $this->loadAppConfig();
                $this->inititateSession();
                CsrfToken::verify();
                $router = new Router;
                $router->run();
            } catch (Exception $exception) {
                $this->displayErrors($exception);
            }
        }
        /**
         * Load configuration options for the application
         */
        public function loadAppConfig()
        {
            require_once __DIR__ . '/app/config/app.php';
            $this->app_config = $app_config;
            // Display php errors only on development environment
            if ($this->app_config['ENVIRONMENT'] === 'development') {
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);
            }
        }
        /**
         * Start the session
         */
        public function inititateSession()
        {
            $session = new SessionManager();
            $session->initiate();
        }
        /**
         * Display error message if exception is thrown
         */
        public function displayErrors($exception)
        {
            if ($this->app_config['ENVIRONMENT'] === 'development') {
                // Display server errors to the user only on development environment
                $pageTitle = 'Error';
                require_once __DIR__ . '/app/views/error.php';
            } else {
                // Display generic error message on production environment
                $pageTitle = 'Server Error';
                require_once __DIR__ . '/app/views/500.php';
            }
            die;
        }
    }
    $app = new App();
    $app->run();