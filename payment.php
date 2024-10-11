<?php
session_start();

$cookie_name = "return_to";
$cookie_value = "payment.php";
$cookie_expiration = time() + 60 * 5;
setcookie($cookie_name, $cookie_value, $cookie_expiration,'/');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['invalid'] = ['value' => '❗Please login First', 'timestamp' => time()];
    header('Location: log_reg.php');
} else {
    include 'cartSettings.php';
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HandCrafted:Payment</title>
</head>

<body>
    <div class="cont-left">
        <?php if (isset($_SESSION['user_id'])) { ?>
            <h2>Welcome, <?php $_SESSION['logged_user'] ?></h2>
        <?php } else { ?>
            <a href="log_reg.php">
                <button class="btn">
                    <span class="button_top">
                        Login Now!
                    </span>
                </button>
            </a>

        <?php
        } ?>
    </div>
    <div class="cont-right">
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
</body>

</html>
