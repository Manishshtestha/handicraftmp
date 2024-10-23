<div class="navbar">
    <div class="logo">
        <img class="navbar-brand" src="./STATIC/Images/Logo.png" />
    </div>
    <div class="mid">
        <ul class="top-row">
            <div></div>
            <div class="cent">
                <li class="nav-item <?php if ($_SESSION['page'] == 'homepage') echo 'active'; ?>">
                    <a href="index.php" class="nav-link"><span class="inner-link">Home</span></a>
                </li>
                <li class="nav-item <?php if ($_SESSION['page'] == 'about') echo 'active'; ?>">
                    <a href="about.php" class="nav-link"><span class="inner-link">About</span></a>
                </li>
                <li class="nav-item <?php if ($_SESSION['page'] == 'products') echo 'active'; ?>">
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
                        <button onclick="showManageProductModal()" class="btn btn-fw" title="Manage my Products">
                            <span class="button_top">Manage Products
                            </span>
                        </button>
                        <!-- </a> -->
                        <button onclick="showCartModal()" class="btn btn-fw" title="My Cart">
                            <span class="button_top">My Cart
                            </span>
                        </button>
                <?php }
                } ?>
                <?php if (isset($_SESSION['user_id'])) { ?>


                    <button onclick="showProfileModal()" class="btn btn-fw" title="Update Profile">
                        <span class="button_top">Update Profile</i></span>
                    </button>

                    <button onclick="showOrderModal()" class="btn btn-fw" title="Update Profile">
                        <span class="button_top">My Orders</i></span>
                    </button>

                    <a href="logout.php" onclick="return confirm('Are You sure you want to logout?');" class="logout">
                        <!-- <i class="fas fa-sign-out-alt"></i>&ensp;Logout -->
                        <button class="btn btn-fw" title="Logout">
                            <span class="button_top">Logout</i>
                            </span>
                        </button>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>