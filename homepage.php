<?php
session_start();
include 'query.php';
include 'validate.php';

$cookie_name = 'return_to';
$cookie_value = 'homepage.php';
$cookie_expiration = time() + 60 * 5;
setcookie($cookie_name, $cookie_value, $cookie_expiration, '/');

$obj = new Query();
$val = new Validate();

$_SESSION['page'] = 'homepage';

$artisans = $obj->selectAlltypeQ('users', 'user_type', 'artisan');
$products = $obj->selectAllQ('products');
$noOfProducts = $obj->numQ('products');

if (isset($_SESSION['user_id'])) $userDetails = $obj->getRecordById('users', 'user_id', $_SESSION['user_id']);

$profile = [
    'phone' => '',
    'address' => '',
    'description' => '',
    'profession' => ''
];

if (!empty($_POST)) {
    if (isset($_POST['becomeArtisan'])) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['invalid'] = ['value' => '❗Please Login First', 'timestamp' => time()];
            header('Location: log_reg.php');
        } else {
            $userType = $obj->getRecordById('users', 'user_id', $_SESSION['user_id']);
            switch ($userType['user_type']) {
                case 'artisan':
                    $_SESSION['error'] = ['value' => '❌Already an Artisan', 'timestamp' => time()];
                    break;
                default:
                    $usertyp = ['user_type' => 'artisan'];
                    $obj->updateQ('users', $usertyp, 'user_id', $_SESSION['user_id']);
                    $_SESSION['success'] = ['value' => '✅You Are now an Artisan', 'timestamp' => time()];
            }
        }
    }
    if (isset($_POST['updateProfile'])) {
        $profile['phone'] = $_POST['phone'];
        $profile['address'] = $_POST['address'];
        $profile['description'] = $_POST['description'];
        $profile['profession'] = $_POST['profession'];

        $obj->updateQ('users', $profile, 'user_id', $_SESSION['user_id']);
        $_SESSION['success'] = ['value' => '✅Profile Update Successful', 'timestamp' => time()];
    }
    if (isset($_POST['addProduct'])) {
        $error = $val->vProduct($_POST['product_name'], $_POST['price'], $_POST['description'], $_POST['category']);
        if (!isset($_FILES['main_img']) || $_FILES['main_img']['error'] != 0) {
            $error = 'No image was selected.';
        }
        switch ($error) {
            case '':
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

if (isset($_SESSION['status']) && !empty($_SESSION['cart'])) {
    try {
        $orderInsertData = [
            'buyer_id' => $_SESSION['user_id'],
            'order_date' => date('Y-m-d'),
            'total_amount' => isset($total) ? $total : 0,
            'status' => $_SESSION['status'],
            '0' => 'null'
        ];
        $obj->insertQ('orders', $orderInsertData);
        $orderId = $obj->lastIndex('orders', 'order_id');
        foreach ($_SESSION['cart'] as $cartItem) {
            if ($_SESSION['status'] == 'success') {
                $obj->updateQ('products', ['availability' => 0], 'product_id', $cartItem['product_id']);
            }
            $orderItemInsertData = [
                'order_id' => $orderId,
                'product_id' => $cartItem['product_id'],
                'price' => $cartItem['price'],
                '0' => 'null'
            ];
            // var_dump($orderItemInsertData);die;
            $obj->insertQ('order_items', $orderItemInsertData);
        }
        unset($_SESSION['cart']);
        unset($_SESSION['status']);
        $_SESSION['success'] = ['value' => 'Order processed successfully', 'timestamp' => time()];
    } catch (Exception $e) {
        $_SESSION['error'] = ['value' => 'Error processing order.' . $e->getMessage(), 'timestamp' => time()];
    }
} else {
    $_SESSION['error'] = ['value' => 'Cart is empty or status is not set', 'timestamp' => time()];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HandiCraft Marketplace</title>
    <link rel="stylesheet" href="./STATIC/CSS/design.css">
    <link rel="stylesheet" href="./STATIC/CSS/navbar.css">
    <link rel="stylesheet" href="./STATIC/CSS/toast.css">
    <link rel="stylesheet" href="./STATIC/CSS/productCarousel.css">
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
    include 'updateProfile.php';
    include 'navbar.php';
    ?>
    <div class="container">
        <div id="section1">
            <div class="left">
                <h1>Discover Handmade Treasures</h1>
                <p>Explore our vibrant marketplace of unique, handcrafted products from talented artisans around the world</p>
                <span style="display:flex;gap:10px;">
                    <a href="products.php">
                        <button class="btn_inv">
                            <span class="button_top">
                                Shop Now
                            </span>
                        </button>
                    </a>
                    <form action="" method="post">
                        <button class="btn" name="becomeArtisan">
                            <span class="button_top">
                                Become an Artisan?
                            </span>
                        </button>
                    </form>
                </span>
            </div>
            <div class="right">
                <div class="slider">
                    <div class="slide one">
                        <img src="./STATIC/Images/1.jpeg" onerror="this.src='./STATIC/Images/notFound.gif'" />
                    </div>
                    <div class="slide two">
                        <img src="./STATIC/Images/2.jpeg" onerror="this.src='./STATIC/Images/notFound.gif'" />
                    </div>
                    <div class="slide three">
                        <img src="./STATIC/Images/3.jpg" onerror="this.src='./STATIC/Images/notFound.gif'" />
                    </div>
                    <div class="slide four">
                        <img src="./STATIC/Images/4.jpeg" onerror="this.src='./STATIC/Images/notFound.gif'" />
                    </div>
                    <div class="slide five">
                        <img src="./STATIC/Images/1.jpeg" onerror="this.src='./STATIC/Images/notFound.gif'" />
                    </div>
                </div>


            </div>
        </div>
        <div id="section2">
            <p>Featured Products</p>
            <h2>Handmade Treasures</h2>
            <p>Discover unique, one-of-a-kind products created wity care by our talented Artisans</p>
            <div class="grid">
                <?php
                if (!empty($products)) {
                    foreach (array_slice($products, $noOfProducts - 4, $noOfProducts) as $product) { ?>
                        <div class="card">
                            <div class="card-img">
                                <img src="./uploads/<?php echo $product['main_img'] ?>" alt="" srcset="" width="200px">
                            </div>
                            <div class="card-title"><?php echo $product['product_name'] ?></div>
                            <div class="card-subtitle"><?php echo $product['description'] ?></div>
                            <hr class="card-divider">
                            <div class="card-footer">
                                <div class="card-price"><span>Rs.</span><?php echo $product['price'] ?></div>
                                <form action="products.php?id=<?php echo $product['product_id'] ?>" method="post">
                                    <input type="text" name="product_id" value="<?php echo $product['product_id'] ?>" hidden>
                                    <input type="text" name="product_name" value="<?php echo $product['product_name'] ?>" hidden>
                                    <input type="text" name="price" value="<?php echo $product['price'] ?>" hidden>
                                    <input type="text" name="main_img" value="<?php echo $product['main_img'] ?>" hidden>
                                    <input type="text" name="created_at" value="<?php echo $product['created_at'] ?>" hidden>
                                    <button class="btn" name="add_to_cart">
                                        <span class="button_top">
                                            <i class="fa-solid fa-cart-plus"></i>
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php }
                } else { ?>
                    <div class="noProduct">
                        <p>No Products Found!</p>
                    </div>
                <?php }
                ?>
            </div>
            <a href="products.php">
                <button class="btn">
                    <span class="button_top">
                        Show all Products
                    </span>
                </button>
            </a>
        </div>
        <div id="section3" style="width:100%;">
            <p>Product Categories</p>
            <h2>Explore Our Handmade Treasures</h2>
            <p>Browse our diverse selection of handcrafted products across various categories.</p>
            <div class="pausableCarousel">
                <div class="MovingGroup">
                    <div class="movingcard" onclick="window.location.href='products.php?category=Textile and Fiber Arts'">
                        <img src="./STATIC/Images/textile.jpg" alt="" srcset="">
                        Textile and Fiber Arts
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Home and Living'">
                        <img src="./STATIC/Images/homeandliving.jpg" alt="" srcset="">
                        Home and Living
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Craft Supplies'">
                        <img src="./STATIC/Images/craftSupplies.jpg" alt="" srcset="">
                        Craft Supplies
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Glass Art'">
                        <img src="./STATIC/Images/glassArt.jpg" alt="" srcset="">
                        Glass Art
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Painting and Drawing'">
                        <img src="./STATIC/Images/painting.jpg" alt="" srcset="">
                        Painting and Drawing
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Sculpture'">
                        <img src="./STATIC/Images/sculpture.jpg" alt="" srcset="">
                        Sculpture
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Seasonal Items'">
                        <img src="./STATIC/Images/seasonal.jpg" alt="" srcset="">
                        Seasonal Items
                    </div>
                </div>
                <!-- Add `aria-hidden` to hide the duplicated cards from screen readers. -->
                <div aria-hidden class="MovingGroup">
                    <div class="movingcard" onclick="window.location.href='products.php?category=Textile and Fiber Arts'">
                        <img src="./STATIC/Images/textile.jpg" alt="" srcset="">
                        Textile and Fiber Arts
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Home and Living'">
                        <img src="./STATIC/Images/homeandliving.jpg" alt="" srcset="">
                        Home and Living
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Craft Supplies'">
                        <img src="./STATIC/Images/craftSupplies.jpg" alt="" srcset="">
                        Craft Supplies
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Glass Art'">
                        <img src="./STATIC/Images/glassArt.jpg" alt="" srcset="">
                        Glass Art
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Painting and Drawing'">
                        <img src="./STATIC/Images/painting.jpg" alt="" srcset="">
                        Painting and Drawing
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Sculpture'">
                        <img src="./STATIC/Images/sculpture.jpg" alt="" srcset="">
                        Sculpture
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Seasonal Items'">
                        <img src="./STATIC/Images/seasonal.jpg" alt="" srcset="">
                        Seasonal Items
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="categories">

    </div>
    <div class="artisans">

    </div>
    <script src="./STATIC/JS/functions"></script>
</body>

</html>