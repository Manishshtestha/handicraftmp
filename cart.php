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
                    <button style="margin-left: 75px;margin-top:5px;" class="btn btn-fw"
                        onclick="<?php if ($total > 1) echo "window.location.href='checkout.php'";
                                    else echo "alert('No Items in the Cart to Checkout')" ?>">
                        <span class="button_top">
                            Checkout
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>