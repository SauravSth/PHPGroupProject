<nav>
      <div class="navLeft">
            <ul>
                  <li class="logo">
                        <a href="./home.php">Store Name</a>
                  </li>
                  <li><a href="./shop.php">Shop Cars</a></li>
                  <li><a href="./contact.php">Contact Us</a></li>
                  <?php if(isset($_SESSION['user_id'])): ?>
                        <li><a href="./cart.php">Cart</a></li>
                  <?php endif; ?>
            </ul>
      </div>
      <div class="navRight">
            <a href="./login.php">Login or Signup</a>
      </div>
</nav>