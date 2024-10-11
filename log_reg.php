<?php
include("query.php");
include("validate.php");
$obj = new Query();
$val = new Validate();

session_start();
$user = [
  "username" => "",
  "email" => "",
  "password" => "",
  "created_at" => date("Y-m-d H:i:s"),
  "user_type" => "buyer"
];
$userErr = [
  "username" => "",
  "email" => "",
  "password" => "",
  "cpass" => ""
];

if (!empty($_POST)) {
  if (isset($_POST['signin'])) {
    $email = $_POST['loginemail'];
    // echo $email;
    $password = md5($_POST['loginpassword']);
    // echo $password;die;
    $userlogin = $obj->login('users', $email, $password);
    if ($userlogin != false) {
      session_destroy();
      session_start();
      $_SESSION['user_id'] = $userlogin['user_id'];
      $_SESSION['logged_user'] = $userlogin['username'];
      $_SESSION['success'] = ['value' => '✅Logged in Successfully!', 'timestamp' => time()];
      if (isset($_COOKIE['return_to'])) {
        header('Location: ' . $_COOKIE['return_to']);
      } else
        header("Location:homepage.php");
    } else {
      $_SESSION['invalid'] = ['value' => '❗Invalid Email/Password', 'timestamp' => time()];
    }
  }
  if (isset($_POST['signup'])) {
    $userErr['username'] = $val->vName($_POST['username']);
    $userErr['email'] = $val->vEmail($_POST['email']);
    $userErr['password'] = $val->vPassword($_POST['password']);
    $userErr['cpass'] = $val->vCPassword($_POST['password'], $_POST['cpass']);
    $user['username'] = $_POST['username'];
    $user['email'] = $_POST['email'];
    $user['password'] = $_POST['password'];
    if ($obj->duplicateEntry('users', 'email', $user['email'])) {
      $userErr['email'] = "Email already exists";
    }
    $errorFree = ($userErr['username'] == '' && $userErr['password'] == '' && $userErr['email'] == '' && $userErr['cpass'] == '');
    if ($errorFree) {
      $obj->insertQ("users", $user);
      $_SESSION['success'] = ['value' => '✅Registration Successful', 'timestamp' => time()];
    }
  }
}
$errArr = array($userErr['username'], $userErr['email'], $userErr['password'], $userErr['cpass']);
for ($i = 0; $i < count($errArr); $i++) {
  if (!empty($errArr[$i])) {
    $_SESSION['error'] = ['value' => "❌" . $errArr[$i], 'timestamp' => time()];
    break;
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login and Signup</title>
  <style>
    *,
    *:before,
    *:after {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Nunito", sans-serif;
    }

    body {
      width: 100%;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' version='1.1' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns:svgjs='http://svgjs.dev/svgjs' width='1920' height='1080' preserveAspectRatio='none' viewBox='0 0 1920 1080'%3e%3cg mask='url(%26quot%3b%23SvgjsMask1049%26quot%3b)' fill='none'%3e%3crect width='1920' height='1080' x='0' y='0' fill='rgba(25%2c 25%2c 25%2c 1)'%3e%3c/rect%3e%3cpath d='M954.9769152435371 683.7925014052261L767.9178269498066 723.5531380420705 921.0883260519386 984.261452164244z' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float2'%3e%3c/path%3e%3cpath d='M1523.994362734805 795.6883028254086L1630.6473117635621 988.0953161138158 1823.0543250519693 881.4423670850585 1716.4013760232122 689.0353537966514z' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float1'%3e%3c/path%3e%3cpath d='M1345.208%2c388.742C1420.963%2c390.17%2c1492.467%2c353.567%2c1532.492%2c289.234C1574.952%2c220.988%2c1587.59%2c133.973%2c1546.806%2c64.713C1506.519%2c-3.705%2c1424.6%2c-27.88%2c1345.208%2c-28.818C1263.769%2c-29.78%2c1175.185%2c-11.096%2c1135.19%2c59.852C1095.678%2c129.943%2c1126.466%2c214.581%2c1169.77%2c282.395C1209.217%2c344.169%2c1271.926%2c387.36%2c1345.208%2c388.742' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float1'%3e%3c/path%3e%3cpath d='M1408.64 254.98 a211.52 211.52 0 1 0 423.04 0 a211.52 211.52 0 1 0 -423.04 0z' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float3'%3e%3c/path%3e%3cpath d='M1382.3 1065.2 a200.19 200.19 0 1 0 400.38 0 a200.19 200.19 0 1 0 -400.38 0z' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float2'%3e%3c/path%3e%3cpath d='M958.37%2c362.12C990.452%2c363.516%2c1014.466%2c336.51%2c1030.757%2c308.837C1047.358%2c280.639%2c1058.584%2c246.95%2c1043.16%2c218.091C1027.019%2c187.892%2c992.549%2c171.324%2c958.37%2c173.403C927.226%2c175.298%2c903.254%2c199.298%2c889.745%2c227.424C878.016%2c251.842%2c883.835%2c278.841%2c896.132%2c302.978C910.057%2c330.309%2c927.725%2c360.786%2c958.37%2c362.12' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float2'%3e%3c/path%3e%3cpath d='M381.8243914269127 442.14746587615167L348.55742623004096 125.63343470717626 65.3103602579373 475.4144310730234z' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float3'%3e%3c/path%3e%3cpath d='M262.8560458643218 448.20368911585365L472.41547731616066 336.7789629192938 360.9907511196008 127.21953146745494 151.43131966776195 238.6442576640148z' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float1'%3e%3c/path%3e%3cpath d='M358.6593558382539 618.4243599734248L144.1769721549657 568.6918514145854 197.22767095196548 753.7016246550959z' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float2'%3e%3c/path%3e%3cpath d='M216.0280761581866 291.0498648734865L396.23343610360365 123.3264447036511 191.56515090265617 56.82568764764932z' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float3'%3e%3c/path%3e%3cpath d='M1491.9915899642776 331.61549763694745L1678.7195863336094 467.28132816849757 1814.3854168651596 280.5533317991658 1627.6574204958276 144.88750126761568z' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float2'%3e%3c/path%3e%3cpath d='M1631.7742512605125 406.7300634124194L1333.5531683672516 292.253715200381 1219.0768201552132 590.474798093642 1517.2979030484742 704.9511463056804z' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float3'%3e%3c/path%3e%3cpath d='M1338.55 1071.54 a230.13 230.13 0 1 0 460.26 0 a230.13 230.13 0 1 0 -460.26 0z' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float2'%3e%3c/path%3e%3cpath d='M678.336902337521-33.40717255243297L599.0727894522504 243.0196396018961 875.4996016065794 322.2837524871668 954.7637144918501 45.85694033283767z' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float2'%3e%3c/path%3e%3cpath d='M999.9954485409975 926.44580842259L754.8411636159736 935.006784696371 763.4021398897546 1180.1610696213947 1008.5564248147786 1171.600093347614z' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float1'%3e%3c/path%3e%3cpath d='M319.51061503573607 860.9687065487442L-10.52919387097785 720.5037614539528 22.24562545094699 1032.3353374040419z' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float1'%3e%3c/path%3e%3cpath d='M475.006%2c925.557C548.696%2c919.543%2c592.494%2c848.698%2c623.787%2c781.712C649.607%2c726.44%2c648.946%2c665.681%2c621.967%2c610.965C590.863%2c547.883%2c545.197%2c484.454%2c475.006%2c479.972C398.743%2c475.103%2c330.779%2c524.316%2c291.498%2c589.865C250.988%2c657.466%2c239.435%2c740.843%2c277.237%2c809.995C316.49%2c881.8%2c393.443%2c932.214%2c475.006%2c925.557' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float1'%3e%3c/path%3e%3cpath d='M1964.2933762357586 4.555031426159822L1713.2946725660054-86.80102554903334 1621.9386155908123 164.19767812071981 1872.9373192605653 255.55373509591297z' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float3'%3e%3c/path%3e%3cpath d='M1711.206%2c374.64C1740.497%2c376.36%2c1770.39%2c364.765%2c1785.484%2c339.604C1800.962%2c313.803%2c1799.217%2c280.592%2c1782.718%2c255.431C1767.593%2c232.365%2c1738.782%2c225.73%2c1711.206%2c226.33C1684.889%2c226.903%2c1658.007%2c235.61%2c1644.888%2c258.431C1631.805%2c281.19%2c1637.628%2c308.886%2c1650.64%2c331.687C1663.788%2c354.726%2c1684.725%2c373.085%2c1711.206%2c374.64' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float2'%3e%3c/path%3e%3cpath d='M-21.04 308.29 a188.18 188.18 0 1 0 376.36 0 a188.18 188.18 0 1 0 -376.36 0z' fill='rgba(61%2c 61%2c 61%2c 0.56)' class='triangle-float3'%3e%3c/path%3e%3c/g%3e%3cdefs%3e%3cmask id='SvgjsMask1049'%3e%3crect width='1920' height='1080' fill='white'%3e%3c/rect%3e%3c/mask%3e%3cstyle%3e %40keyframes float1 %7b 0%25%7btransform: translate(0%2c 0)%7d 50%25%7btransform: translate(-10px%2c 0)%7d 100%25%7btransform: translate(0%2c 0)%7d %7d .triangle-float1 %7b animation: float1 5s infinite%3b %7d %40keyframes float2 %7b 0%25%7btransform: translate(0%2c 0)%7d 50%25%7btransform: translate(-5px%2c -5px)%7d 100%25%7btransform: translate(0%2c 0)%7d %7d .triangle-float2 %7b animation: float2 4s infinite%3b %7d %40keyframes float3 %7b 0%25%7btransform: translate(0%2c 0)%7d 50%25%7btransform: translate(0%2c -10px)%7d 100%25%7btransform: translate(0%2c 0)%7d %7d .triangle-float3 %7b animation: float3 6s infinite%3b %7d %3c/style%3e%3c/defs%3e%3c/svg%3e");
      font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
    }

    input,
    button {
      border: none;
      outline: none;
      background: none;
    }

    .cont {
      overflow: hidden;
      position: relative;
      width: 900px;
      height: 550px;
      background: rgba(30, 32, 34, 0.5);
      backdrop-filter: blur(7px);
      /* border-radius: 10px; */
      border: 2px solid aliceblue;
    }

    .form {
      background: rgba(30, 32, 34, 0);
      position: relative;
      width: 640px;
      height: 100%;
      padding: 50px 30px;
      color: aliceblue;
      -webkit-transition: -webkit-transform 1.2s ease-in-out;
      transition: -webkit-transform 1.2s ease-in-out;
      transition: transform 1.2s ease-in-out;
      transition: transform 1.2s ease-in-out,
        -webkit-transform 1.2s ease-in-out;
      /* border-bottom: 2px solid aliceblue; */
      /* border-radius: 10px; */
    }

    h2 {
      width: 100%;
      font-size: 30px;
      text-align: center;
    }

    label {
      display: block;
      width: 360px;
      margin: 25px auto 0;
      text-align: center;
    }

    label span {
      font-size: 14px;
      font-weight: 600;
      color: aliceblue;
      text-transform: uppercase;
    }

    input {
      display: block;
      width: 100%;
      margin-top: 5px;
      font-size: 16px;
      padding-bottom: 5px;
      color: aliceblue;
      border-bottom: 1px solid aliceblue;
      text-align: center;
      font-family: "Nunito", sans-serif;
    }

    button {
      display: block;
      margin: 0 auto;
      width: 260px;
      height: 36px;
      border-radius: 30px;
      color: #fff;
      font-size: 15px;
      cursor: pointer;
    }


    .social-media {
      width: 100%;
      text-align: center;
      margin-top: 20px;
    }

    .social-media ul {
      list-style: none;
    }

    .social-media ul li {
      display: inline-block;
      cursor: pointer;
      margin: 25px 15px;
    }

    .social-media img {
      width: 40px;
      height: 40px;
    }

    .sub-cont {
      overflow: hidden;
      position: absolute;
      left: 640px;
      top: 0;
      width: 900px;
      height: 100%;
      padding-left: 260px;
      background: rgb(30, 32, 34, 0);
      -webkit-transition: -webkit-transform 1.2s ease-in-out;
      transition: -webkit-transform 1.2s ease-in-out;
      transition: transform 1.2s ease-in-out;
    }

    .cont.s-signup .sub-cont {
      -webkit-transform: translate3d(-640px, 0, 0);
      transform: translate3d(-640px, 0, 0);
    }

    .img {
      overflow: hidden;
      z-index: 2;
      position: absolute;
      left: 0;
      top: 0;
      width: 260px;
      height: 100%;
      padding-top: 360px;

      &:before {
        content: "";
        position: absolute;
        right: 0;
        top: 0;
        width: 900px;
        height: 100%;
        background-image: url("STATIC/Images/background.jpg");
        /* background:rgb(25,25,25); */
        background-size: cover;
        transition: -webkit-transform 1.2s ease-in-out;
        transition: transform 1.2s ease-in-out,
          -webkit-transform 1.2s ease-in-out;
      }

      &:after {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.3);
      }
    }


    .Cancel {
      font-size: 1.5em;
      color: aliceblue;
      text-decoration: none;
      border-radius: 50%;
      padding: 5px 10px;
      border: 2px solid white;
      transition: 0.2s;

      &:hover {
        color: red;
        border: 2px solid red;
      }
    }

    .btn {
      margin-top: 20px;
      font-size: 17px;
      font-weight: bold;
      border: none;
      border-radius: 0.75em;
      background: #000000;
    }

    .btn .button_top {
      display: block;
      box-sizing: border-box;
      border: 2px solid #000000;
      border-radius: 0.75em;
      padding: 0.75em 1.5em;
      background: #e8e8e8;
      color: #000000;
      transform: translateY(-0.2em);
      transition: transform 0.1s ease;
    }

    .btn:hover .button_top {
      /* Pull the button upwards when hovered */
      transform: translateY(-0.33em);
    }

    .btn:active .button_top {
      /* Push the button downwards when pressed */
      transform: translateY(0);
    }

    .cont.s-signup .img:before {
      -webkit-transform: translate3d(640px, 0, 0);
      transform: translate3d(640px, 0, 0);
    }

    .img-text {
      z-index: 2;
      position: absolute;
      left: 0;
      top: 50px;
      width: 100%;
      padding: 0 20px;
      text-align: center;
      color: #fff;
      -webkit-transition: -webkit-transform 1.2s ease-in-out;
      transition: -webkit-transform 1.2s ease-in-out;
      transition: transform 1.2s ease-in-out,
        -webkit-transform 1.2s ease-in-out;
    }

    .img-text h2 {
      margin-bottom: 10px;
      font-weight: normal;
    }

    .img-text p {
      font-size: 14px;
      line-height: 1.5;
    }

    .cont.s-signup .img-text.m-up {
      -webkit-transform: translateX(520px);
      transform: translateX(520px);
    }

    .img-text.m-in {
      -webkit-transform: translateX(-520px);
      transform: translateX(-520px);
    }

    .cont.s-signup .img-text.m-in {
      -webkit-transform: translateX(0);
      transform: translateX(0);
    }

    .sign-in {
      padding-top: 65px;
      -webkit-transition-timing-function: ease-out;
      transition-timing-function: ease-out;
    }

    .cont.s-signup .sign-in {
      -webkit-transition-timing-function: ease-in-out;
      transition-timing-function: ease-in-out;
      -webkit-transition-duration: 1.2s;
      transition-duration: 1.2s;
      -webkit-transform: translate3d(7650px, 0, 0);
      transform: translate3d(765px, 0, 0);
    }

    .img-btn {
      overflow: hidden;
      z-index: 2;
      position: relative;
      width: 100px;
      height: 36px;
      margin: 0 auto;
      background: transparent;
      color: #fff;
      text-transform: uppercase;
      font-size: 15px;
      cursor: pointer;

      &:after {
        content: "";
        z-index: 2;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        border: 2px solid #fff;
        border-radius: 30px;
      }
    }

    .img-btn span {
      position: absolute;
      left: 0;
      top: 0;
      display: -webkit-box;
      display: flex;
      -webkit-box-pack: center;
      justify-content: center;
      align-items: center;
      width: 100%;
      height: 100%;
      -webkit-transition: -webkit-transform 1.2s;
      transition: -webkit-transform 1.2s;
      transition: transform 1.2s;
      transition: transform 1.2s, -webkit-transform 1.2s;
    }

    .img-btn span.m-in {
      -webkit-transform: translateY(-72px);
      transform: translateY(-72px);
    }

    .cont.s-signup .img-btn span.m-in {
      -webkit-transform: translateY(0);
      transform: translateY(0);
    }

    .cont.s-signup .img-btn span.m-up {
      -webkit-transform: translateY(72px);
      transform: translateY(72px);
    }

    .sign-up {
      -webkit-transform: translate3d(-900px, 0, 0);
      transform: translate3d(-900px, 0, 0);
    }

    .cont.s-signup .sign-up {
      -webkit-transform: translate3d(0, 0, 0);
      transform: translate3d(0, 0, 0);
    }
  </style>
  <link rel="stylesheet" href="./STATIC/CSS/toast.css">
</head>

<body>
  <?php
  include "notifications.php";
  ?>
  <div class="cont">
    <div class="form sign-in">
      <div class="headtitle" style="display:flex;justify-content:space-evenly">
        <a href="homepage.php" class="Cancel" style="opacity:0">X</a>
        <h2>Sign In</h2>
        <a href="homepage.php" class="Cancel">X</a>
      </div>
      <form action="" method="post">

        <label>
          <span>Email Address</span>
          <input type="email" name="loginemail" required />
        </label>
        <label>
          <span>Password</span>
          <input type="password" name="loginpassword" required />
        </label>
        <button class="btn" name="signin">
          <span class="button_top">
            Sign-in
          </span>
        </button>
      </form>
      <div class="social-media">
        <ul>
          <li>
            <a href="https://www.facebook.com">
              <img src="https://raw.githubusercontent.com/abo-elnoUr/public-assets/master/facebook.png" />
            </a>
          </li>
          <li>
            <a href="https://www.twitter.com">
              <img src="https://raw.githubusercontent.com/abo-elnoUr/public-assets/master/twitter.png" />
            </a>
          </li>
          <li>
            <a href="https://www.linkedin.com">
              <img src="https://raw.githubusercontent.com/abo-elnoUr/public-assets/master/linkedin.png" />
            </a>
          </li>
          <li>
            <a href="https://www.instagram.com">
              <img src="https://raw.githubusercontent.com/abo-elnoUr/public-assets/master/instagram.png" />
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="sub-cont">
      <div class="img">
        <div class="img-text m-up">
          <h2>New here?</h2>
          <p>
            Sign up and discover great amount of new
            opportunities!
          </p>
        </div>
        <div class="img-text m-in">
          <h2>One of us?</h2>
          <p>
            If you already has an account, just sign in. We've
            missed you!
          </p>
        </div>
        <div class="img-btn">
          <span class="m-up">Sign Up</span>
          <span class="m-in">Sign In</span>
        </div>
      </div>
      <div class="form sign-up">
        <div class="headtitle" style="display:flex;justify-content:space-evenly">
          <a href="homepage.php" class="Cancel" style="opacity:0">X</a>
          <h2>Sign Up</h2>
          <a href="homepage.php" class="Cancel">X</a>
        </div>
        <form action="" method="post">
          <label>
            <span>Name</span>
            <input type="text" name="username" />
          </label>
          <label>
            <span>Email</span>
            <input type="email" name="email" />
          </label>
          <label>
            <span>Password</span>
            <input type="password" name="password" />
          </label>
          <label>
            <span>Confirm Password</span>
            <input type="password" name="cpass" />
          </label>
          <button class="btn" name="signup">
            <span class="button_top">
              Sign-up
            </span>
          </button>
        </form>
      </div>
    </div>
  </div>
  <script>
    document
      .querySelector(".img-btn")
      .addEventListener("click", function() {
        document
          .querySelector(".cont")
          .classList.toggle("s-signup");
      });

    setTimeout(function() {
      var toasts = document.getElementsByClassName("toast");
      for (var i = 0; i < toasts.length; i++) {
        toasts[i].classList.add("hide");
      }
    }, 5000);
  </script>
</body>

</html>