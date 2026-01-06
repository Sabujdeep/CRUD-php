document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const emailInput = document.getElementById("email");
    const email = emailInput.value.trim();

    const gmailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;

    // ❌ Invalid Gmail
    if (!gmailRegex.test(email)) {
    //   emailInput.classList.add("is-invalid");
    //   emailInput.classList.remove("is-valid");

      Swal.fire({
        icon: "error",
        title: "Invalid Email",
        text: "Please enter a valid Gmail address",
        confirmButtonColor: "#0d6efd",
      });

      return;
    }

    // ✅ Valid Gmail
    emailInput.classList.remove("is-invalid");
    emailInput.classList.add("is-valid");

    Swal.fire({
      icon: "success",
      title: "Login Successful",
      text: "",
      confirmButtonColor: "#0d6efd",
    }).then(() => {
      window.location.href = "index.php";
    });
  });
