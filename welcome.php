<?php
include "db_connection.php";  // Connecting to the database
session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Details - User Management</title>
    <!-- Bootstrap 5 CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="./Styles/style.css">
    <style>    
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 250px;
    padding: 20px;
    z-index: 1000;
    overflow: hidden;
}

.sidebar::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: url(./Styles/bg.jpg);
    filter: blur(20px);
    transform: scale(1.1);
    z-index: -1;
}
      
      .sidebar h3 {
        color: white;
        font-size: 1.5rem;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(255,255,255,0.3);
      }
      
      .sidebar .nav-link {
        color: rgba(255,255,255,0.8);
        padding: 12px 15px;
        margin-bottom: 10px;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-weight: 500;
      }
      
      .mainContainer {
        margin-left: 250px;
      }
    </style>
  </head>
  <body>
    <!-- SIDEBAR -->
    <?php
    include "sidebar.php";
    ?>

    <div class="mainContainer">
      <div class="container mt-5 text-center">
        <h1>Welcome!</h1>
        </div>
    </div>
  </body>
</html>