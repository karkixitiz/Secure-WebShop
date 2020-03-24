<?php
require_once __DIR__ . '/DB.php';

/**
 * The base model for database connections
 * Note: This model shouldn't be used directly but extended from
 * other models so they are connected to database and can access
 * the variables
 */
abstract class BaseModel
{
  /**
   * PDO database object
   * Any interactions to the DB can be
   * facilitated with this variable
   */
  protected $db;

  function __construct()
  {
    $pdo_instance = DB::instance();
    $this->db = $pdo_instance->db;
  }
}
