<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
ob_start();
$system = $conn->query("SELECT * FROM system_settings")->fetch_array();
foreach($system as $k => $v){
    $_SESSION['system'][$k] = $v;
}
ob_end_flush();
?>
<?php 
if(isset($_SESSION['login_id']))
header("location:index.php?page=home");
?>
<?php include 'header.php' ?>
<head>
  <style>
    /* Body should fill the screen and center everything */
    body {
      font-family: Arial, sans-serif;
      background: #b8e5d1; /* pastel green background */
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh; /* Ensures the body takes up the full height of the viewport */
      overflow: hidden; /* Prevents scrolling */
    }

    .login-container {
      display: flex;
      background: white;
      border-radius: 10px;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
      width: 700px;
      height: 400px;
      overflow: hidden;
    }

    .login-container .welcome-section {
    background: linear-gradient(135deg, #4cc9c0, #3aa3a1); /* Gradient background */
    padding: 30px;
    flex: 1;
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    border-top-left-radius: 10px; /* Rounds top-left corner */
    border-bottom-left-radius: 10px; /* Rounds bottom-left corner */
    box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.2); /* Inner + outer shadow */
}


    .login-container .welcome-section h1 {
      font-size: 2.5rem;
      margin-bottom: 20px;
    }

    .login-container .welcome-section img {
      width: 200px;
      margin-bottom: 20px;
    }

    .login-container .form-section {
      padding: 40px;
      flex: 1.2;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .login-container .form-section h2 {
      font-size: 1.8rem;
      margin-bottom: 20px;
      color: #4cc9c0;
    }

    .login-container form .input-group {
      margin-bottom: 20px;
    }

    .login-container form input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }

    .login-container form button {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 5px;
      background: #4cc9c0;
      color: white;
      font-size: 1rem;
      cursor: pointer;
      margin-top: 15px;
    }

    .login-container form button:hover {
      background: #3aa3a1;
    }

    .login-container form .remember-me {
        display: flex; /* Align horizontally */
        align-items: center; /* Vertically align */
        justify-content: flex-start; /* Align to the left */
        margin-top: 10px;
        gap: 10px; /* Spacing between checkbox and label */
    }

    .login-container form .remember-me input[type="checkbox"] {
        width: 20px; /* Checkbox size */
        height: 20px;
        accent-color: #4cc9c0; /* Checkbox color */
        margin-top: -9px; /* Move the checkbox slightly upwards */
        border: 2px solid #4cc9c0; /* Add border color */
        border-radius: 5px; /* Round the corners of the checkbox */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Subtle shadow for a modern look */
    }

    .login-container form .remember-me label {
        font-size: 0.9rem;
        color: #4cc9c0; /* Matches "Login" button color */
        cursor: pointer; /* Makes label clickable */
    }

    .login-container form input {
        width: 100%;
        padding: 10px;
        border: 2px solid #4cc9c0; /* Add modern border */
        border-radius: 5px; /* Smooth rounded edges */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Add a shadow for depth */
        outline: none;
        transition: all 0.3s ease; /* Smooth transitions for focus */
    }

    .login-container form input:focus {
        border-color: #3aa3a1; /* Change border color on focus */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Enhance shadow when focused */
    }
  </style>
</head>
<body>
<div class="login-container">
  <div class="welcome-section">
    <h1><strong>Welcome</strong></h1>
    <img src="assets/uploads/task_pic.png" alt="Task Image">
    <p>Task Management System</p>
  </div>
  <div class="form-section">
    <h2><strong>LOGIN</strong></h2>
    <form action="" id="login-form">
      <div class="input-group">
        <input type="email" name="email" id="email" required placeholder="Username">
      </div>
      <div class="input-group">
        <input type="password" name="password" required placeholder="Password">
      </div>
      <div class="remember-me">
  <input type="checkbox" id="remember-me">
  <label for="remember-me">Remember me</label>
</div>

      <button type="submit">Login</button>
    </form>
  </div>
</div>

<script>
  $(document).ready(function(){
    // Load remembered email if it exists
    if (localStorage.getItem('rememberedEmail')) {
      $('#email').val(localStorage.getItem('rememberedEmail'));
      $('#remember-me').prop('checked', true);
    }

    $('#login-form').submit(function(e){
      e.preventDefault();
      start_load();
      if($(this).find('.alert-danger').length > 0 )
        $(this).find('.alert-danger').remove();

      // Save email to localStorage if "Remember Me" is checked
      const rememberMe = $('#remember-me').is(':checked');
      const email = $('#email').val();
      if (rememberMe) {
        localStorage.setItem('rememberedEmail', email);
      } else {
        localStorage.removeItem('rememberedEmail');
      }

      $.ajax({
        url: 'ajax.php?action=login',
        method: 'POST',
        data: $(this).serialize(),
        error: err => {
          console.log(err);
          end_load();
        },
        success: function(resp) {
          if (resp == 1) {
            location.href = 'index.php?page=home';
          } else {
            $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>');
            end_load();
          }
        }
      });
    });
  });
</script>

<?php include 'footer.php' ?>
</body>
</html>
