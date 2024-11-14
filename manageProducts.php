<div class="modal" id="manageProduct">
    <div class="modal_content">
        <div class="cart-cont" style="flex-direction:column;">
            <div style="margin-top:5px;display:flex;justify-content:space-evenly;align-items:center;">
                <a class="Cancel" style="opacity:0">X</a>
                <h2 style="color:aliceblue;">Product Manage Form</h2>
                <a onclick="removeModal('manageProduct')" class="close">X</a>
            </div>
            <div class="form-content cartProducts" style="width:100%;">
                <?php if (!empty($myProducts)) {
                    foreach ($myProducts as $key => $mproduct) {
                ?>
                        <div class="cartItem" style="height:15vh;">
                            <div class="img">
                                <img src="./uploads/<?php echo $mproduct['main_img'] ?>" alt="" srcset="" width="70">
                            </div>
                            <div class="details">
                                <h3 style="margin-top:20px;"><?php echo $mproduct['product_name'] ?></h3>
                                <p>Listed on: <?php echo date('Y-m-d', strtotime($mproduct['created_at'])); ?></p>
                                <p><?php echo 'Rs.' . $mproduct['price']; ?></p>
                                <p><?php if ($mproduct['availability'] == '1') echo 'Available';
                                    else echo 'Sold' ?></p>
                            </div>
                            <div class="price">
                                <form action="" method="post">
                                    <input type="text" name="index" id="" value="<?php echo $key; ?>" hidden>
                                    <button type="submit" name="rem<?php echo $key ?>" onclick="return confirm('Are you sure to remove <?php echo $mproduct['product_name']; ?>?')"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                <?php
                    }
                }else{
                    echo '<h2 style="color:aliceblue;">No products found</h2>';
                }
                ?>
            </div>
        </div>
    </div>
</div>