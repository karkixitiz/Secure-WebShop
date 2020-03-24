<?php
require_once __DIR__ . '/BaseModel.php';

class Session extends BaseModel
{
  protected $tableName;

  public function __construct()
  {
    parent::__construct();

    $this->tableName = 'tbl_sessions';
  }

  public function isDBConnected()
  {
    return $this->db ? true : false;
  }

  public function get($id)
  {
    $sql = "SELECT data FROM $this->tableName WHERE id = :id";
    $statement = $this->db->prepare("SELECT data FROM $this->tableName WHERE id = :id");

    // Bind the Id
    $statement->bindValue(':id', $id);

    if ($statement->execute()) {
      $row = $statement->fetch();
      return $row ? $row['data'] : '';
    } else {
      return '';
    }
  }

  public function set($id, $data)
  {
    // Create time stamp
    $created_at = date("Y-m-d H:i:s");

    $statement = $this->db->prepare("REPLACE INTO $this->tableName VALUES (:id, :data, :created_at)");

    $statement->bindValue(':id', $id);
    $statement->bindValue(':data', $data);
    $statement->bindValue(':created_at', $created_at);

    return $statement->execute();
  }

  public function destroy($id)
  {
    $statement = $this->db->prepare("DELETE FROM $this->tableName WHERE id = :id");

    $statement->bindValue(':id', $id);

    return $statement->execute();
  }

  /**
   * Destroy sessions that are older than the specified time
   * @param number $max
   */
  public function destroyOlderThan($max)
  {
    // Calculate what is to be deemed old
    $old = time() - $max;

    $statement = $this->db->prepare("DELETE * FROM $this->tableName WHERE created_at < :old");

    $statement->bindValue(':old', $old);

    return $statement->execute();
  }
}
