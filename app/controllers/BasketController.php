<?php
    class BasketController
    {
        function show_basket()
        {
            if (isset($_POST['showcart'])) {
                $rvalue = '	<div id="shop-c" class="card p-3 rounded-0">
					<table class="table table-bordered">
						<tr>
							<th width="40%">Item Name</th>
							<th width="10%">Quantity</th>
							<th width="20%">Price</th>
							<th width="15%">Total</th>
							<th width="5%">Action</th>
						</tr>';
                if (isset($_SESSION["shopping_cart"])) {
                    $total = 0;
                    $cart_data = stripslashes($_SESSION['shopping_cart']);
                    $cart_data = json_decode($cart_data, true);
                    foreach ($cart_data as $keys => $values) {
                        $rvalue .= '
									<tr>
										<td>';
                        $rvalue.= $values["item_name"];
                        $rvalue.='</td>
										<td>';
                        $rvalue.= number_format($values["item_quantity"]);
                        $rvalue.='</td>
										<td>€';
                        $rvalue.= $values["item_price"];
                        $rvalue.='
                                    </td>
										<td>€';
                        $rvalue.= number_format($values["item_quantity"] * $values["item_price"], 2);
                        $rvalue.='</td>
										<td><a href="?action=delete&id=';
                        $rvalue.= $values["item_id"];
                        $rvalue.='"><span
														class="text-danger">Remove</span></a></td>
									</tr>';
                        $total = $total + ( number_format($values["item_quantity"]) * $values["item_price"]);
                    }
                    $rvalue.='<tr>
									<td colspan="3" align="right">Total</td>
									<td align="right">€ ';
                    $rvalue.= number_format($total, 2);
                    $rvalue.='</td>
									<td></td>
								</tr>';
                } else {
                    $rvalue .='
				<tr>
					<td colspan="5" align="center">No Item in Cart</td>
				</tr>
				';
                }
                $checkoutUrl = url('/checkout');
                $rvalue .= '</table><div>';


                if (isset($cart_data) && $cart_data) {
                    $rvalue .= '<a align="right" class="btn btn-primary"
                     href="' . $checkoutUrl . '">Checkout</a>
                     <a href="' . url('/clear-cart') . '" class="btn btn-danger">Clear Cart</a>';
                }

                $rvalue .= ' </div></div>';
                echo $rvalue;
                exit();
            }
        }
        function add_to_card()
        {
            if (isset($_POST["add_to_cart"])) {
                if (isset($_SESSION["shopping_cart"])) {
                    $cart_data = stripslashes($_SESSION['shopping_cart']);
                    $cart_data = json_decode($cart_data, true);
                } else {
                    $cart_data = array();
                }
                $item_id_list = array_column($cart_data, 'item_id');
                if (in_array($_POST["hidden_id"], $item_id_list)) {
                    foreach ($cart_data as $keys => $values) {
                        if ($cart_data[$keys]["item_id"] == $_POST["hidden_id"]) {
                            $cart_data[$keys]["item_quantity"] =  number_format($cart_data[$keys]["item_quantity"]) +  number_format($_POST["quantity"]);
                        }
                    }
                } else {
                    $item_array = array(
                        'item_id' => $_POST["hidden_id"],
                        'item_name' => $_POST["hidden_name"],
                        'item_price' => $_POST["hidden_price"],
                        'item_quantity' =>  number_format($_POST["quantity"])
                    );
                    $cart_data[] = $item_array;
                }
                $item_data = json_encode($cart_data);
                $_SESSION['shopping_cart'] = $item_data;
            }
            redirect('/');
        }
    }
?>