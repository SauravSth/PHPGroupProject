<nav>
      <div class="navLeft">
            <ul>
                  <li class="logo">
                        <a href="./home.php">Store Name</a>
                  </li>
                  <li><a href="./shop.php">Shop Cars</a></li>
                  <li><a href="./contact.php">Contact Us</a></li>
                  <?php if(isset($_SESSION['user_id']) && $_SESSION['user_type'] !== 'admin'): ?>
                  <li><a href="./cart.php">Cart</a></li>
                  <?php endif; ?>
            </ul>
      </div>
      <div class="navRight">
      <?php if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'admin'): ?>
            <a href="./admin_dashboard.php">Admin Dashboard</a>
      <?php endif; ?>

      <?php if (isset($_SESSION['user_id'])): ?>
            <a href="./logout.php">Logout</a>
      <?php else: ?>
            <a href="./login.php">Login or Signup</a>
      <?php endif; ?>
      </div>
</nav>
