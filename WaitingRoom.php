<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="UIStyle.css" />
    <title>Aung Bi Win Rice Trading House - Waiting Room</title>
    <link rel="shortcut icon" href="img/grain.svg" type="image/svg+xml" />
  </head>
  <body class="bg-light">
    <main class="WR d-flex justify-content-center align-items-center min-vh-100">
      <div class="waitingRoom d-flex justify-content-center align-items-center ">
        <!-- Text Section -->
        <div class="Message me-4">
          <h1 class="display-4 bold">Whoops!</h1>
          <h2 class="h4">
            Something went wrong.<br />Please try again in
          </h2>
          <h3 class="mt-3">
            <span id="countDown" class="text-primary">60</span> seconds
          </h3>
          <p class="mt-3">
            You have exceeded the maximum number of login attempts.
          </p>
        </div>

        <!-- GIF Section -->
        <img
          src="img/Cat.gif"
          alt="not found"
          class="img-fluid"
          style="max-width: 350px; height: auto;"
        />
      </div>
    </main>

    <script>
      var seconds = 60;

      function updateCountDown() {
        document.getElementById('countDown').textContent = seconds;
        seconds--;

        if (seconds < 0) {
          window.location.href = 'login.php';
        }
      }

      setInterval(updateCountDown, 1000);
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
