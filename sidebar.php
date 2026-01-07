<div class="sidebar">
  <h3>Admin Dashboard</h3>
  <nav class="nav flex-column">
    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>" 
       href="index.php">
       User Details
    </a>

    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'user_details.php' ? 'active' : '' ?>" 
       href="user_details.php">
       User list
    </a>
  </nav>
</div>
