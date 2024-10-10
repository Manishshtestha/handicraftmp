<?php
session_start();
include 'query.php';
include 'validate.php';

$obj = new Query();
$val = new Validate();
$searchKeyword = '';
$products = $obj->selectAllQ('products');
if (!empty($_POST)) {
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
    if (isset($_POST['add_to_cart'])) {
        if (isset($_SESSION['cart'])) {
            $session_array_id = array_column($_SESSION['cart'], 'product_id');
            if (!in_array($_GET['id'], $session_array_id)) {
                $session_cart = array(
                    'product_id' => $_POST['product_id'],
                    'product_name' => $_POST['product_name'],
                    'price' => $_POST['price'],
                    'main_img' => $_POST['main_img'],
                    'created_at' => $_POST['created_at']
                );
                $_SESSION['cart'][] = $session_cart;
                $_SESSION['success'] = ['value' => '✅Product added to Cart', 'timestamp' => time()];
            } else {
                $_SESSION['invalid'] = ['value' => '❗Product already in Cart', 'timestamp' => time()];
            }
        } else {
            $session_cart = array(
                'product_id' => $_POST['product_id'],
                'product_name' => $_POST['product_name'],
                'price' => $_POST['price'],
                'main_img' => $_POST['main_img'],
                'created_at' => $_POST['created_at']
            );
            $_SESSION['cart'][] = $session_cart;
            $_SESSION['success'] = ['value' => '✅Product added to Cart', 'timestamp' => time()];
        }
        // var_dump($sessionCart);
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

if (!empty($_GET)) {
    // Check if the trigger parameter is present
    if (isset($_GET['trigger'])) {
        if ($_GET['trigger'] == true) {
            // Trigger the button programmatically
            echo "<script type='text/javascript'>
            setTimeout(function() {
                document.getElementById('dropdownButtonSearch').click();
                }, 300); 
                </script>";
        }
    }
    $keyword = $_GET['keyword'] ?? '';
    $category = $_GET['category'] ?? '';
    $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
    $rating = isset($_GET['rating']) ? intval($_GET['rating']) : 0;
    $sql = "SELECT * FROM products WHERE ";
    $searchKeyword = $keyword;
    if (!empty($keyword)) {
        $sql .= "LOWER(product_name) LIKE LOWER('%{$keyword}%') AND ";
    }
    if ($category != '') {
        $sql .= "category = '{$category}' AND ";
    }
    if ($min_price > 1) {
        $sql .= "price > {$min_price} AND ";
    }
    $sql = substr($sql, 0, -5);
    $products = $obj->executeQ($sql);
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
$noOfProducts = $obj->numQ('products');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="./STATIC/CSS/design.css">
    <link rel="stylesheet" href="./STATIC/CSS/navbar.css">
    <link rel="stylesheet" href="./STATIC/CSS/toast.css">
    <link rel="stylesheet" href="./STATIC/CSS/productCarousel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                    <li class="nav-item">
                        <a href="about.php" class="nav-link"><span class="inner-link">About</span></a>
                    </li>
                    <li class="nav-item active">
                        <a href="products.php" class="nav-link"><span class="inner-link">Products</span></a>
                    </li>
                </div>
                <div></div>
            </ul>
        </div>
        <div class="signup_logout">
            <!-- <button onclick="showLoginModal();">Login</button> -->
            <div class="dropdown">
                <button class="btn" id="dropdownButtonSearch">
                    <span class="button_top">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                </button>

                <div class="dropdown-content search" id="Search">
                    <div class="searchContent">
                        <form action="" method="GET" id="searchForm">
                            <input class="searchbar" placeholder="Search a product" name="keyword" type="text" value="<?php echo $searchKeyword; ?>" />
                            <div class="filters">
                                <div class="filter">
                                    <label>
                                        <span>Category:</span>
                                        <select name="category">
                                            <option value="" disabled selected>--Select Category--</option>
                                            <option value="Textile and Fiber Arts">Textile and Fiber Arts</option>
                                            <option value="Home and Living">Home and Living</option>
                                            <option value="Craft Supplies">Craft Supplies</option>
                                            <option value="Glass Art">Glass Art</option>
                                            <option value="Painting and Drawing">Painting and Drawing</option>
                                            <option value="Sculpture">Sculpture</option>
                                            <option value="Seasonal Items">Seasonal Items</option>
                                        </select>
                                    </label>
                                    <label class="min-price-slider" style="width: 190px;">
                                        <span style="text-align:center;">Min Price:</span>
                                        <input type="range" id="min-price-slider" name="min_price" min="0" max="100000" value="0" step="1000">
                                        <output id="min-price-output">0</output>
                                    </label>
                                    <!-- <label>
                                        <span>Minimum Rating:</span>
                                        <div class="rating">
                                            <input type="radio" id="star5" name="rate" value="5" />
                                            <label for="star5" title="text"></label>
                                            <input type="radio" id="star4" name="rate" value="4" />
                                            <label for="star4" title="text"></label>
                                            <input type="radio" id="star3" name="rate" value="3" />
                                            <label for="star3" title="text"></label>
                                            <input type="radio" id="star2" name="rate" value="2" />
                                            <label for="star2" title="text"></label>
                                            <input checked="" type="radio" id="star1" name="rate" value="1" />
                                            <label for="star1" title="text"></label>
                                        </div>
                                    </label> -->
                                </div>
                                <button style="margin-top:5px;" class="btn" type="submit">
                                    <span class="button_top">
                                        <i class="fa-solid fa-magnifying-glass"></i> Search
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
    <div class="container" style="display:flex;justify-content:center;">
        <button style="margin-top:5px;width:150px;top:25px;position:sticky;transform:translateX(550px);z-index:100000;background:none;color:aliceblue;border:1px solid aliceblue;" id="toggleView">
            <span class="button_top">
                Change View Mode
            </span>
        </button>
        <div class="carousel" style="display:none;" id="Carousel">
            <div class="list">
                <?php
                if (!empty($products)) {
                    if (count($products) >= 3) {
                        foreach (array_slice($products, 0, $noOfProducts) as $product) { ?>
                            <div class="item">
                                <img src="./uploads/<?php echo $product['main_img']; ?>">
                                <div class="introduce">
                                    <div class="title"><?php $product['product_name'] ?></div>
                                    <!-- <div class="topic">Airpod</div> -->
                                    <div class="des" style="color:aliceblue;">
                                        <!-- 20 lorem -->
                                        <?php echo substr($product['description'], 0, 200); ?>
                                    </div>
                                    <button class="btn seeMore" name="add_to_cart">
                                        <span class="button_top">
                                            View More
                                        </span>
                                    </button>
                                </div>
                                <div class="detail">
                                    <div class="title"><?php echo $product['product_name'] ?></div>
                                    <div class="des">
                                        <!-- lorem 50 -->
                                        <?php echo $product['description']; ?>
                                    </div>
                                    <div class="price">
                                    </div>
                                    <div class="checkout">
                                        <form action="products.php?id=<?php echo $product['product_id'] ?>" method="post">
                                            <p style="color:aliceblue;display:inline-flex;font-size:1.5em;">Rs.<?php echo $product['price']; ?></p>
                                            <input type="text" name="product_id" value="<?php echo $product['product_id'] ?>" hidden>
                                            <input type="text" name="product_name" value="<?php echo $product['product_name'] ?>" hidden>
                                            <input type="text" name="price" value="<?php echo $product['price'] ?>" hidden>
                                            <input type="text" name="main_img" value="<?php echo $product['main_img'] ?>" hidden>
                                            <input type="text" name="created_at" value="<?php echo $product['created_at'] ?>" hidden>
                                            <button class="btn" name="add_to_cart">
                                                <span class="button_top">
                                                    ADD TO CART
                                                </span>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        <?php }
                    } else {
                        ?>
                        <div class="noProduct">
                            <p>Cannot create a Carousel with less than 3 items</p>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <div class="noProduct">
                        <p>No Products Found! Please try different Query and Filters!</p>
                    </div>
                <?php
                } ?>
            </div>
            <div class="arrows">
                <button id="prev">
                    < </button>
                        <button id="next"> > </button>
                        <button id="back" style="display:none;">Return</button>
            </div>
        </div>
        <div class="products" id="GridView" style="display:flex;">
            <?php
            if (!empty($products)) {
                foreach (array_slice($products, 0, 10) as $product) { ?>
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
                    <p>No Products Found! Please try different Query and Filters!</p>
                </div>
            <?php }
            // var_dump($_SESSION['cart']);
            ?>
        </div>
    </div>
    <script src="./STATIC/JS/functions.js"></script>
    <script src="./STATIC/JS/Carousel.js"></script>
</body>

</html>