<ul class="notifications">
  <?php if (isset($_SESSION['success'])) { ?>
    <li class="toast success">
      <div class="column">
        <span>
          <?php
          echo $_SESSION['success']['value'];
          $timeout = 5;
          $age = time() - $_SESSION['success']['timestamp'];
          if ($age >= $timeout) {
            unset($_SESSION['success']);
            unset($_SESSION['error']);
            unset($_SESSION['invalid']);
          }
          ?>
        </span>
      </div>
    </li>
  <?php
  }
  if (isset($_SESSION['error'])) { ?>
    <li class="toast error">
      <div class="column">
        <span>
          <?php echo $_SESSION['error']['value'];
          $timeout = 5;
          $age = time() - $_SESSION['error']['timestamp'];
          if ($age >= $timeout) {
            unset($_SESSION['success']);
            unset($_SESSION['error']);
            unset($_SESSION['invalid']);
          }
          ?>
        </span>
      </div>
    </li>
  <?php
  }
  if (isset($_SESSION['invalid'])) { ?>
    <li class="toast warning">
      <div class="column">
        <span>
          <?php echo $_SESSION['invalid']['value'];
          $timeout = 5;
          $age = time() - $_SESSION['invalid']['timestamp'];
          if ($age >= $timeout) {
            unset($_SESSION['success']);
            unset($_SESSION['error']);
            unset($_SESSION['invalid']);
          }
          ?>
        </span>
      </div>
    </li>
  <?php
  }
  ?>
</ul>