<?php
session_start();
include 'query.php';
include 'validate.php';

$obj = new Query();
$val = new Validate();
$artisans = $obj->selectAlltypeQ('users', 'user_type', 'artisan');
if (!empty($_POST)) {
    if (isset($_POST['contact'])) {
        $_SESSION['success'] = ['value' => '✅Message Sent Successfully!', 'timestamp' => time()];
    }

    if (isset($_POST['addProduct'])) {
        $error = $val->vProduct($_POST['product_name'], $_POST['price'], $_POST['description'], $_POST['category']);
        if (!isset($_FILES['main_img']) || $_FILES['main_img']['error'] != 0) {
            $error = "No image was selected.";
        }
        switch ($error) {
            case "":
                $obj->insertQ('products', $_POST);
                $_SESSION['success'] = ['value' => '✅Product Added Successfully', 'timestamp' => time()];
                break;
            default:
                $_SESSION['invalid'] = ['value' => $error, 'timestamp' => time()];
        }
    }
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $product) {
            if (isset($_POST['rem' . $key])) {
                $_SESSION['success'] = ['value' => '✅Product removed to Cart', 'timestamp' => time()];
                unset($_SESSION['cart'][$key]);
                if (count($_SESSION['cart']) == 0) {
                    unset($_SESSION['cart']);
                }
            }
        }
    }
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="./STATIC/CSS/design.css">
    <link rel="stylesheet" href="./STATIC/CSS/navbar.css">
    <link rel="stylesheet" href="./STATIC/CSS/toast.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=SUSE:wght@500&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    include 'notifications.php';
    include 'cart.php';
    include 'newProduct.php';
    ?>
    <div class="navbar">
        <div class="logo">
            <img class="navbar-brand" src="./STATIC/Images/Logo.png" />
        </div>
        <div class="mid">
            <ul class="top-row">
                <div></div>
                <div class="cent">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link"><span class="inner-link">Home</span></a>
                    </li>
                    <li class="nav-item active">
                        <a href="about.php" class="nav-link"><span class="inner-link">About</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="products.php" class="nav-link"><span class="inner-link">Products</span></a>
                    </li>
                </div>
                <div></div>
            </ul>
        </div>
        <div class="signup_logout">
            <button class="btn" onclick="window.location.href='products.php?trigger=true'">
                <span class="button_top">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
            </button>
            <?php
            if (isset($_SESSION['user_id'])) {
                $userType = $obj->getRecordById('users', 'user_id', $_SESSION['user_id']);

                switch ($userType['user_type']) {
                    case 'artisan':
                        echo "
                        <button class='btn' onclick='showProductModal()'>
                            <span class='button_top'><i class='fa-solid fa-plus'></i></span>
                        </button>
                    ";
                        break;
                    default:
                        echo "
                        <button onclick='showCartModal()' class='btn btn-fw'>
                            <span class='button_top'><i class='fa-solid fa-cart-shopping'></i> Cart</span>
                        </button>
                    ";
                }
            } else {
                echo "
                <button onclick='showCartModal()' class='btn'>
                    <span class='button_top'><i class='fa-solid fa-cart-shopping'></i></span>
                </button>
            ";
            }
            ?>
            <div class="dropdown2">
                <button class="dropdown-btn btn" id="dropdownMenuBtn">
                    <span class="button_top">
                        <i class="fa-solid fa-user"></i>
                    </span>
                </button>
                <div class="dropdown-content2" id="dropMenu">
                    <?php
                    if (isset($_SESSION['user_id'])) {
                        $userType = $obj->getRecordById('users', 'user_id', $_SESSION['user_id']);
                        if ($userType['user_type'] == 'artisan') {
                    ?>
                            <!-- <a href="addProduct.php"> -->
                            <button onclick="showCartModal()" class="btn btn-fw">
                                <span class="button_top"><i class="fa-solid fa-cart-shopping"></i> Cart
                                </span>
                            </button>
                            <!-- </a> -->
                    <?php }
                    } ?>
                    <!-- <button onclick="showCartModal()" class="btn btn-fw">
                        <span class="button_top"><i class="fa-solid fa-cart-shopping"></i> Cart
                        </span>
                    </button> -->
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <a href="">
                            <button class="btn btn-fw">
                                <span class="button_top">Profile</span>
                            </button>
                        </a>
                        <a href="logout.php" onclick="return confirm('Are You sure you want to logout?');" class="logout">
                            <!-- <i class="fas fa-sign-out-alt"></i>&ensp;Logout -->
                            <button class="btn btn-fw">
                                <span class="button_top">Logout
                                </span>
                            </button>
                        </a>
                    <?php } else { ?>
                        <a href="log_reg.php">
                            <!-- <i class="fas fa-sign-out-alt"></i>&ensp;Logout -->
                            <button class="btn btn-fw">
                                <span class="button_top">Sign in
                                </span>
                            </button>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div id="about">
        <div id="abtsec1">
            <div class="left">
                <h1>About HandCrafted</h1>
                <p style="text-align: justify;">
                    <quot>HandCrafted</quot> is a premier handicraft marketplace dedicated to offering high-quality, unique handmade products while delivering exceptional customer service. Our mission is to empower artisans and customers alike by providing a diverse range of innovative and sustainable goods that enhance everyday living. <br>
                    At the core of <quot>HandCrafted</quot> are our fundamental values: integrity, innovation, and community. We are committed to making a positive impact on the world by fostering a culture of collaboration and diversity among our artisans and customers. Through continuous improvement and support, we aim to create a vibrant community where creativity thrives, and every purchase contributes to the sustainability of the artisan economy.
                </p>
            </div>
            <div class="right">
                <img src="./STATIC/Images/market.jpeg" alt="" onerror="this.src='./STATIC/Images/notFound.gif'" srcset="" width="600px" style="margin-left:50px;">
            </div>
        </div>
        <div id="abtsec2">
            <div class="top">
                <h1>New Artists in the platform</h1>
                <p>Our popular artists from around the world providing exclusive arts and crafts.</p>
            </div>
            <div class="bottom">
                <?php foreach (array_slice($artisans, 0, 3) as $artist) { ?>
                    <div class="card">
                        <img src="./STATIC/Images/avatar.png" alt="">
                        <p class="name"><?= $artist['username'] ?></p>
                        <p class="profession"><?= $artist['profession'] == null ? "Profession Not Set" : $artist['profession'] ?></p>
                        <p class="desc"><?= $artist['description'] == null ? "Description Not Set" : $artist['description'] ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div id="abtsec3">
            <div class="top">
                <h1>Contact Us</h1>
                <p>We'd love to hear from you! Feel Free to reach out to us with any questions, feedback or inquiries.</p>
            </div>
            <div class="bottom">
                <div class="left">
                    <ul>
                        <li>
                            <h3>Headquarters</h3>
                            Putalisadak, Kathmandu, Nepal
                        </li>
                        <li>
                            <h3>Phone</h3>
                            +977 9843 123456
                        </li>
                        <li>
                            <h3>Email</h3>
                            info@handcrafted.com
                        </li>
                        <li>
                            <h3>Social Media</h3>
                            <a href="https://www.facebook.com/HandCraftedNepal/"><i class="fa-brands fa-facebook"></i></a>
                            <a href="https://www.instagram.com/handcraftednepal/"><i class="fa-brands fa-instagram"></i></a>
                            <a href="https://www.x.com/handCraftedNepal/"><i class="fa-brands fa-x-twitter"></i></a>
                            <a href="https://www.linkedin.com/handCraftedNp"><i class="fa-brands fa-linkedin"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="right">
                    <h3>Send Us a Message</h3>
                    <form action="" method="post">
                        <input type="text" name="name" id="name" placeholder="Name" required>
                        <input type="email" name="email" id="email" placeholder="Email" required>
                        <textarea name="message" id="" placeholder="Message" cols="3" required></textarea>
                        <button class="btn" name="contact" style="margin-left:100px;">
                            <span class="button_top">
                                Submit
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <p style="text-align:center;font-size:1em;color:aliceblue;">&copy;HandCrafted Np|<?php echo date('Y') ?></p>
    <script src="./STATIC/JS/functions.js">

    </script>
</body>

</html>