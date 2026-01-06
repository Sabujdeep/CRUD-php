<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login In</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="./Styles/style.css" />
  </head>
  <body>
    <div class="mainContainer">
      <div class="container mt-15 w-25">
        <h2 class="mb-4 txtDes">Login</h2>
            <form id="loginForm">
  <div class="form-group">
    <label for="email">Email address</label>
    <input
      type="email"
      class="form-control"
      id="email"
      placeholder="Enter email"
    />
  </div>

  <div class="form-group mt-2">
    <label for="password">Password</label>
    <input
      type="password"
      class="form-control"
      id="password"
      placeholder="Password"
    />
  </div>

  <button type="submit" class="mt-3 btn btn-primary">Submit</button>
</form>

      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="./loginVald.js"></script>
  </body>
</html>
