<div class="sidebar">
  <h3>Admin Dashboard</h3>
  <nav class="nav flex-column">
    <a class="nav-link  style='display: none;' <?= basename($_SERVER['PHP_SELF']) === 'welcome.php' ? 'active' : '' ?>" 
       href="welcome.php">
    </a>
    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>" 
       href="index.php">
       Add User
    </a>

    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'user_details.php' ? 'active' : '' ?>" 
       href="user_details.php">
       Users List
    </a>

    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'myprofile.php' ? 'active' : '' ?>" 
       href="myprofile.php">
       My Profile
    </a>
  </nav>
</div>
