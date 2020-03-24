<?php
    require_once __DIR__ . '/BaseModel.php';

    class Orders extends BaseModel
    {

        public function getAllOrderId($limit = 50)
        {
            $query = "SELECT order_id from tbl_orders
                GROUP BY order_id
                LIMIT :limit";

            $statement = $this->db->prepare($query);

            $statement->bindValue(':limit', $limit);
            $statement->execute();

            return $statement->fetchAll();
        }

        public function getAllbyOrderId($order_id)
        {
            $query = "SELECT tbl_orders.order_id, tbl_orders.price, tbl_orders.quantity, tbl_orders.total_price, tbl_orders.id_item, tbl_products.name
                from tbl_orders
                INNER JOIN tbl_products ON tbl_orders.id_item = tbl_products.id
                WHERE order_id = :order_id
            ";

            $statement = $this->db->prepare($query);

            $statement->bindValue(':order_id', $order_id);
            $statement->execute();

            return $statement->fetchAll();
        }

        public function create($pro_id, $pro_quantity, $u_id, $rand)
        {
            $order_date = date('Y-m-d H:i:s');

            $query = "INSERT INTO tbl_orders (
                id_item,
                id_user,
                order_date,
                order_id,
                price,
                quantity,
                total_price
              ) VALUES (
                :id_item,
                :id_user,
                :order_date,
                :order_id,
                :price,
                :quantity,
                :total_price
              )";
            $statement = $this->db->prepare($query);

            $this->bindCreateUpdateValues($statement, $pro_id, $pro_quantity, $u_id);
            $statement->bindValue(':order_date', $order_date);
            $statement->bindValue(':order_id', $rand);

            return $statement->execute() > 0;
        }


        private function bindCreateUpdateValues(&$statement, $pro_id, $pro_quantity, $u_id)
        {
            $product = $this->find($pro_id);

            $statement->bindValue(':id_item', $product['id']);
            $statement->bindValue(':id_user', $u_id);
            $statement->bindValue(':price', $product['price']);
            $statement->bindValue(':quantity', $pro_quantity);
            $statement->bindValue(':total_price', ($pro_quantity * $product['price']));
        }

        public function find($productId)
        {
            $query = 'SELECT * from tbl_products WHERE id = :productId';

            $statement = $this->db->prepare($query);

            $statement->bindValue(':productId', $productId);
            $statement->execute();

            return $statement->fetch();
        }

    }
