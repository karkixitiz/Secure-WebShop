<?php
require_once __DIR__ . '/BaseModel.php';

class Product extends BaseModel
{
  /**
   * Get all products in the database
   */
  public function getAll($limit=15) {
    $query = "SELECT * from tbl_products
                ORDER BY created_at DESC
                LIMIT :limit";

    $statement = $this->db->prepare($query);

    $statement->bindValue(':limit', $limit);
    $statement->execute();

    return $statement->fetchAll();
  }

  /**
   * Get the product with specified id in database
   * @param Number
   * @returns Array
   */
  public function find($productId)
  {
    $query = 'SELECT * from tbl_products WHERE id = :productId';

    $statement = $this->db->prepare($query);

    $statement->bindValue(':productId', $productId);
    $statement->execute();

    return $statement->fetch();
  }

  /**
   * Get featured products
   * @returns Array
   */
  public function getFeatured()
  {
    $query = "SELECT * from tbl_products
                WHERE is_featured = TRUE
                ORDER BY created_at DESC";

    $statement = $this->db->query($query);

    return $statement->fetchAll();
  }

  /**
   * Create a new product in database
   * @param Array $product
   * @returns Boolean
   */
  public function create($product)
  {
    $created_at = date('Y-m-d H:i:s');

    $query = "INSERT INTO tbl_products (
                name,
                description,
                image,
                price,
                quantity,
                is_featured,
                created_at
              ) VALUES (
                :name,
                :description,
                :image,
                :price,
                :quantity,
                :is_featured,
                :created_at
              )";

    $statement = $this->db->prepare($query);

    $this->bindCreateUpdateValues($statement, $product);
    $statement->bindValue(':created_at', $created_at);

    return $statement->execute() > 0;
  }

  /**
   * Update an existing product
   * @param String $productId  id of the product
   * @param Array $product  data to update
   */
  public function update($productId, $product)
  {
    $updated_at = date('Y-m-d H:i:s');

    $query = "UPDATE tbl_products
                SET name = :name,
                    description = :description,
                    image = :image,
                    price = :price,
                    quantity = :quantity,
                    is_featured = :is_featured,
                    updated_at = :updated_at
                WHERE id = :productId";

    $statement = $this->db->prepare($query);

    $statement->bindValue(':productId', $productId);
    $this->bindCreateUpdateValues($statement, $product);
    $statement->bindValue(':updated_at', $updated_at);

    return $statement->execute() > 0;
  }

  /**
   * Delete an existing product
   * @param Number $productId
   * @returns Boolean
   */
  public function destroy($productId)
  {
    $query = "DELETE FROM tbl_products WHERE id = :productId";

    $statement = $this->db->prepare($query);

    $statement->bindValue(':productId', $productId);

    return $statement->execute() > 0;
  }

  /**
   * Method to bind values that are common for both create
   * and update operations
   */
  private function bindCreateUpdateValues(&$statement, $product)
  {
    $statement->bindValue(':name', $product['name']);
    $statement->bindValue(':description', $product['description']);
    $statement->bindValue(':image', $product['image']);
    $statement->bindValue(':price', $product['price']);
    $statement->bindValue(':quantity', $product['quantity']);
    $statement->bindValue(':is_featured', $product['is_featured']);
  }
}
