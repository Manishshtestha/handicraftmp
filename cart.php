<div class="modal" id="myCart">
    <div class="modal_content">
        <div class="cart-cont">
            <div id="cartProducts">
                <?php if (isset($_SESSION['cart'])) {
                ?>
                    <h1 style="display:flex;justify-content:space-around;color:aliceblue;">Your product list <span><?php echo count($_SESSION['cart']) . " items" ?></span></h1>
                    <?php
                    foreach ($_SESSION['cart'] as $key => $product) { ?>
                        <div class="cartItem">

                            <div class="img">
                                <img src="./uploads/<?php echo $product['main_img'] ?>" alt="" srcset="" width="100">
                            </div>
                            <div class="details">
                                <h3 style="margin-top:20px;"><?php echo $product['product_name'] ?></h3>
                                <p>Listed on: <?php echo date('Y-m-d', strtotime($product['created_at'])); ?></p>

                            </div>
                            <div class="price">
                                <form action="" method="post">
                                    <input type="text" name="index" id="" value="<?php echo $key; ?>" hidden>
                                    <button type="submit" name="rem<?php echo $key ?>" onclick="return confirm('Are you sure to remove <?php echo $product['product_name']; ?>?')"><i class="fa-solid fa-trash"></i></button>
                                </form>
                                <h3><?php echo 'Rs.' . $product['price']; ?></h3>
                            </div>
                        </div>
                    <?php }
                } else {
                    ?>
                    <div class="noProduct">
                        <p>No Products in your Cart!</p>
                    </div>
                <?php } ?>
            </div>
            <div id="summary">
                <div id="promocode">
                    <p>Promo code</p>
                    <form action="" method="post">
                        <input type="text" name="promocode" placeholder="Enter your promocode">
                        <button type="submit" name="applyPromo">Apply</button>
                    </form>
                </div>
                <div id="cartSummary">
                    <h2>Order Summary</h2>
                    <h3><span></span><span></span></h3>
                    <h3><span>Subtotal</span><span>Rs. <?php echo $subtotal; ?></span></h3>
                    <h3><span>Tax(5%)</span><span>Rs. <?php echo $tax ?></span></h3>
                    <h3><span>Shipping</span><span>Rs. <?php echo $shipping ?></span></h3>

                    <hr>
                    <p><span>Total</span><span>Rs. <?php echo $total ?></span></p>
                    <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
                        <input type="text" id="amount" name="amount" value="<?php echo $subtotal ?>" required hidden>
                        <input type="text" id="tax_amount" name="tax_amount" value="<?php echo $tax ?>" required hidden>
                        <input type="text" id="total_amount" name="total_amount" value="<?php echo $total ?>" required hidden>
                        <input type="text" id="transaction_uuid" name="transaction_uuid" value="<?php echo $t_uuid ?>" required hidden>
                        <input type="text" id="product_code" name="product_code" value="EPAYTEST" required hidden>
                        <input type="text" id="product_service_charge" name="product_service_charge" value="0" required hidden>
                        <input type="text" id="product_delivery_charge" name="product_delivery_charge" value="<?php echo $shipping ?>" required hidden>
                        <input type="text" id="success_url" name="success_url" value="https://esewa.com.np" required hidden>
                        <input type="text" id="failure_url" name="failure_url" value="https://google.com" required hidden>
                        <input type="text" id="signed_field_names" name="signed_field_names" value="total_amount,transaction_uuid,product_code" required hidden>
                        <input type="text" id="signature" name="signature" value="<?php echo base64_encode($sig) ?>" required hidden>
                        <!-- <input value=" Submit" type="submit"> -->
                        <button style="margin-left: 75px;" value="Submit" class="btn btn-fw">
                            <span class="button_top">Checkout
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>