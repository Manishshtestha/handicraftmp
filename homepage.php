<?php
session_start();
include 'query.php';
include 'validate.php';

$obj = new Query();
$val = new Validate();

$artisans = $obj->selectAlltypeQ('users', 'user_type', 'artisan');
$products = $obj->selectAllQ('products');
$noOfProducts = $obj->numQ('products');
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
    ?>
    <div class="navbar">
        <div class="logo">
            <img class="navbar-brand" src="./STATIC/Images/Logo.png" />
        </div>
        <div class="mid">
            <ul class="top-row">
                <div></div>
                <div class="cent">
                    <li class="nav-item active">
                        <a href="index.php" class="nav-link"><span class="inner-link">Home</span></a>
                    </li>
                    <li class="nav-item">
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
                <?php
                if (isset($_SESSION['user_id'])) {
                ?>
                    <button class="dropdown-btn btn" id="dropdownMenuBtn">
                        <span class="button_top">
                            <i class="fa-solid fa-user"></i>
                        </span>
                    </button>
                <?php
                } else {
                ?>
                    <a href="log_reg.php">
                        <!-- <i class="fas fa-sign-out-alt"></i>&ensp;Logout -->
                        <button class="btn" title="Signin">
                            <span class="button_top"><i class="fa-solid fa-right-to-bracket"></i>
                            </span>
                        </button>
                    </a>
                <?php
                }
                ?>
                <div class="dropdown-content2" id="dropMenu">
                    <?php
                    if (isset($_SESSION['user_id'])) {
                        $userType = $obj->getRecordById('users', 'user_id', $_SESSION['user_id']);
                        if ($userType['user_type'] == 'artisan') {
                    ?>
                            <!-- <a href="addProduct.php"> -->
                            <button onclick="showCartModal()" class="btn btn-fw" title="My Cart">
                                <span class="button_top"><i class="fa-solid fa-cart-shopping"></i>
                                </span>
                            </button>
                            <!-- </a> -->
                    <?php }
                    } ?>
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <a href="">
                            <button class="btn btn-fw" title="Update Profile">
                                <span class="button_top"><i class="fa-solid fa-user-gear"></i></span>
                            </button>
                        </a>
                        <a href="logout.php" onclick="return confirm('Are You sure you want to logout?');" class="logout">
                            <!-- <i class="fas fa-sign-out-alt"></i>&ensp;Logout -->
                            <button class="btn btn-fw" title="Logout">
                                <span class="button_top"><i class="fa-solid fa-right-from-bracket"></i>
                                </span>
                            </button>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
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
                        Textile and Fiber Arts
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Home and Living'">
                        Home and Living
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Craft Supplies'">
                        Craft Supplies
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Glass Art'">
                        Glass Art
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Painting and Drawing'">
                        Painting and Drawing
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Sculpture'">
                        Sculpture
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Seasonal Items'">
                        Seasonal Items
                    </div>
                </div>
                <!-- Add `aria-hidden` to hide the duplicated cards from screen readers. -->
                <div aria-hidden class="MovingGroup">
                    <div class="movingcard" onclick="window.location.href='products.php?category=Textile and Fiber Arts'">
                        Textile and Fiber Arts
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Home and Living'">
                        Home and Living
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Craft Supplies'">
                        Craft Supplies
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Glass Art'">
                        Glass Art
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Painting and Drawing'">
                        Painting and Drawing
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Sculpture'">
                        Sculpture
                    </div>
                    <div class="movingcard" onclick="window.location.href='products.php?category=Seasonal Items'">
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
    <script src="./STATIC/JS/Carousel"></script>
</body>

</html>