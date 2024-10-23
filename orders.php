<div class="modal" id="myOrders">
    <div class="modal_content">
        <div class="cart-cont" style="flex-direction:column;">
            <div style="margin-top:5px;display:flex;justify-content:space-evenly;align-items:center;">
                <a class="Cancel" style="opacity:0">X</a>
                <h2 style="color:aliceblue;">My Orders</h2>
                <a onclick="removeModal('myOrders')" class="close">X</a>
            </div>
            <div class="form-content cartProducts" style="width:100%;padding:5px;">
                <?php foreach ($fetchOrder as $order) {
                    $order_items = $obj->selectAlltypeQ('order_items', 'order_id', $order['order_id']);
                ?>
                    <details class="order">
                        <summary>
                            Order no: #<?php echo $order['order_id'] ?>
                            &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
                            Total: &ensp;Rs.<?php echo $order['total_amount'] ?>
                        </summary>
                        <summary>
                            Order Date: <?php echo date('Y-m-d', strtotime($order['order_date'])); ?>
                            <?php if ($order['status'] == 'pending' || $order['status'] == 'success') { ?>
                                <button style="float:right;" value="Submit" class="btn btn-fw" onclick="return confirm('Are you sure to cancel the Order?')">
                                    <span class="button_top" style="background:red;color:aliceblue;">Cancel
                                    </span>
                                </button>
                            <?php } ?>
                            <br>
                            Status: <?php switch ($order['status']) {
                                        case 'pending':
                                            echo 'Pending';
                                            break;
                                        case 'success':
                                            echo 'Paid';
                                            break;
                                        case 'canceled':
                                            echo 'Canceled';
                                            break;
                                        case 'shipped':
                                            echo 'Shipped';
                                            break;
                                        default:
                                            break;
                                    }
                                    ?>
                        </summary>
                        <div class="items">
                            <?php foreach ($order_items as $item) { ?>
                                <p>
                                    <img src="./uploads/<?php echo $obj->selectAlltypeQ('products', 'product_id', $item['product_id'])[0]['main_img']; ?>" width="75px" alt="" srcset="">

                                    <?php echo $obj->selectAlltypeQ('products', 'product_id', $item['product_id'])[0]['product_name']; ?>
                                    <br>
                                    <?php echo $obj->selectAlltypeQ('products', 'product_id', $item['product_id'])[0]['price']; ?>
                                </p>
                            <?php } ?>
                        </div>
                    </details>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>