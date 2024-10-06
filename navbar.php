<div class="navbar">
    <div class="logo">
        <img class="navbar-brand" src="./STATIC/Images/Logo.png" />
    </div>
    <div class="mid">
        <ul class="top-row">
            <div></div>
            <div class="cent">
                <li class="nav-item active">
                    <a href="index.php" class="nav-link" onclick="clickedHome();"><span class="inner-link">Home</span></a>
                </li>
                <li class="nav-item">
                    <a href="about.php" class="nav-link" onclick="clickedAbout();"><span class="inner-link">About</span></a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="clickedCategories();"><span class="inner-link">Categories</span></a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="clickedArtisans();"><span class="inner-link">View Artists</span></a>
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
                    <form action="" method="post">
                        <input class="searchbar" placeholder="Search a product" name="text" type="text" />
                        <button class="btn_inv" name="search">
                            <span class="button_top">
                                Search
                            </span>
                        </button>
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