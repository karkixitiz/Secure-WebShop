<?php
require_once __DIR__ . '/models/Session.php';

class SessionManager {
  private $sessionModel;
  /**
   * The time(in seconds) after which the session is expired
   */
  private $sessionTimeout;

  public function initiate()
  {
    $this->sessionModel = new Session;
    $this->sessionTimeout = 900;

    $this->setSessionHandlers();
    $this->startSession();
    $this->renewSessionIfExpired();
  }

  /**
   * Set handlers to override the default behavior of writing
   * session to file and write to database instead
   */
  public function setSessionHandlers()
  {
    session_write_close();

    session_set_save_handler(
      [$this, "_open"],
      [$this, "_close"],
      [$this, "_read"],
      [$this, "_write"],
      [$this, "_destroy"],
      [$this, "_gc"]
    );
  }

  /**
   * Start a new session if one doesn't exist
   */
  public function startSession()
  {
    if (session_status() === PHP_SESSION_NONE){
      session_start([
        'cookie_httponly' => true
      ]);

      // Make sure we have a _started_at set
      if (!isset($_SESSION['_started_at'])) {
        $this->renewSession();

        $this->setCsrfToken();
      }

      if (!isset($_SESSION['csrf_token'])) {

      }
    }
  }

  /**
   * Renew the session
   */
  public function renewSession()
  {
    session_regenerate_id(true);
    $this->setCsrfToken();

    $_SESSION['_started_at'] = time();
  }

  /**
   * Renew session if it's timed out
   */
  public function renewSessionIfExpired()
  {
    if ($_SESSION['_started_at'] < time() - $this->sessionTimeout) {
      $this->renewSession();
    }
  }

  /**
   * Completely destroy the current session
   */
  public function destroySession()
  {
    // Unset all of the session variables.
    $_SESSION = array();

    // Also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();
  }

  /**
	 * Generate and set the CSRF synchronizer token
	 */
  public function setCsrfToken()
  {
    $token = base64_encode(openssl_random_pseudo_bytes(32));

    $_SESSION['csrf_token'] = $token;
  }

  public function _open()
  {
    return $this->sessionModel->isDBConnected();
  }

  public function _close()
  {
    return true;
  }

  public function _read($id)
  {
    return $this->sessionModel->get($id);
  }

  public function _write($id, $data)
  {
    return $this->sessionModel->set($id, $data);
  }

  public function _destroy($id)
  {
    return $this->sessionModel->destroy($id);
  }

  public function _gc($max)
  {
    return $this->sessionModel->destroyOlderThan($max);
  }
}
