<?php

require_once __DIR__ . '/CsrfToken.php';

/**
 * Get the relative url of path
 * @param String $path
 * @returns String
 */
function url($path='')
{
  $basePath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1));

  return "{$basePath}{$path}";
}

/**
 * Return the public path for the file/path
 * @param String $path
 * @returns String
 */
function public_path($path) {
  return url("/public/{$path}");
}

/**
 * Generate a input field with CSRF synchronizer token for display in view
 */
function csrf_token() {
  $token = CsrfToken::get();

  return '<input type="hidden" name="_token" id="_token" value="' . $token . '">';
}

/**
 * Redirect to a link in the app
 * @param String $path
 * @returns String
 */
function redirect($path)
{
  $redirectTo = url($path);

  header("Location: $redirectTo");
  die();
}

/**
 * Check if the current user is administrator
 * @returns Boolean
 */
function isAdmin() {
  return isset($_SESSION['user_id']) && isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'admin';
}
