<?php
session_start();
include('query.php');
$obj = new Query();
$orderno = $obj->lastIndex('orders','order_id') + 1;
$cookie_name = "return_to";
$cookie_value = "checkout.php";
$cookie_expiration = time() + 60 * 5;
setcookie($cookie_name, $cookie_value, $cookie_expiration, '/');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['invalid'] = ['value' => '❗Please login First', 'timestamp' => time()];
    header('Location: log_reg.php');
}

$subtotal = 0;
$tax = 0;
$shipping = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $value) {
        $subtotal += $value['price'];
    }
    $tax = $subtotal * 5 / 100;
    $shipping = count(value: $_SESSION['cart']) * 90;
}
$total = $subtotal + $tax + $shipping;

function generateRandomString($length = 4): string
{
    $characters = '0123456789';
    $charactersLength = strlen(string: $characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(min: 0, max: $charactersLength - 1)];
    }
    return $randomString;
}
$t_uuid = "TXN-" . generateRandomString();
$message = "total_amount=$total,transaction_uuid=$t_uuid,product_code=EPAYTEST";
$secretKey = "8gBm/:&EnhH.1/q";
$sig = hash_hmac(algo: 'sha256', data: $message, key: $secretKey, binary: true);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HandCrafted:Payment</title>
    <link rel="stylesheet" href="./STATIC/CSS/design.css">
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            width: 100%;
        }

        .container1 {
            width: 90%;
            height: 100vh;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            color: aliceblue;

            .cont-left {
                padding-top: 50px;
                width: 78%;
                .list_view{
                    margin-top: 10px;
                    height: 80vh;
                    overflow-y: auto;
                    overflow-x: hidden;
                    /* background-color: aliceblue; */
                    display: flex;
                    flex-direction: column;
                    gap: 5px;
                    padding: 5px;
                    .list_item{
                        height: 10vh;
                        width: 100%;
                        background-color: rgba(25, 25, 25,0.7);
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        padding-left:10px;
                        img{
                            width: 50px;
                            height: 50px;
                        }
                    }
                }
            }

            .cont-right {
                padding-top: 200px;
                width: 20%;
                display: flex;
                flex-direction: column;
                gap: 10px;

                h3 {
                    display: flex;
                    justify-content: space-between;
                }
            }
        }
    </style>
</head>

<body>
    <div class="container1">

        <div class="cont-left">
            <h2>Order Details</h2>
            <h3>Order no: <?php echo $orderno;?></h3>
            <hr>
            <div class="list_view">
                <?php foreach ($_SESSION['cart'] as $key => $product) { ?>
                    <div class="list_item">
                        <img src="./uploads/<?php echo $product['main_img']?>" alt="" srcset="">
                        <h3><?php echo $product['product_name']?></h3>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="cont-right">
            <h2>Order Summary</h2>
            <br>
            <h3><span>Subtotal</span><span>Rs. <?php echo $subtotal; ?></span></h3>
            <h3><span>Tax(5%)</span><span>Rs. <?php echo $tax ?></span></h3>
            <h3><span>Shipping</span><span>Rs. <?php echo $shipping ?></span></h3>
            <hr>
            <h3><span>Total</span><span>Rs. <?php echo $total ?></span></h3>
            <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
                <input type="text" id="amount" name="amount" value="<?php echo $subtotal ?>" required hidden>
                <input type="text" id="tax_amount" name="tax_amount" value="<?php echo $tax ?>" required hidden>
                <input type="text" id="total_amount" name="total_amount" value="<?php echo $total ?>" required hidden>
                <input type="text" id="transaction_uuid" name="transaction_uuid" value="<?php echo $t_uuid ?>" required hidden>
                <input type="text" id="product_code" name="product_code" value="EPAYTEST" required hidden>
                <input type="text" id="product_service_charge" name="product_service_charge" value="0" required hidden>
                <input type="text" id="product_delivery_charge" name="product_delivery_charge" value="<?php echo $shipping ?>" required hidden>
                <input type="text" id="success_url" name="success_url" value="http://localhost/handicraftmp/status_success.php" required hidden>
                <input type="text" id="failure_url" name="failure_url" value="http://localhost/handicraftmp/status_fail.php" required hidden>
                <input type="text" id="signed_field_names" name="signed_field_names" value="total_amount,transaction_uuid,product_code" required hidden>
                <input type="text" id="signature" name="signature" value="<?php echo base64_encode($sig) ?>" required hidden>
                <!-- <input value=" Submit" type="submit"> -->
                <button style="margin-left: 75px;margin-top:10px" value="Submit" class="btn btn-fw">
                    <span class="button_top">Pay Now
                    </span>
                </button>
            </form>
        </div>
    </div>
</body>

</html>