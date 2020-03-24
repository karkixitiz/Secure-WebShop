<?php

class CsrfToken {
	/**
	 * Get the CSRF synchronizer token
	 */
	public static function get() {
		return $_SESSION['csrf_token'];
	}

	/**
	 * Verify the CSRF synchronizer token
	 */
	public static function verify() {
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      return;
    }

    if (!isset($_SESSION['csrf_token']) || !isset($_POST['_token']) || $_POST['_token'] !== $_SESSION['csrf_token']) {
      throw new Exception('Invalid CSRF token');
		}

		return true;
	}
}
