<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$page_title = "Login";

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);
   
   if($select_user->rowCount() > 0){
     setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
     header('location:home.php');
   }else{
      $message[] = 'Incorrect email, password, or role!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login | E-Learning Platform</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:700,900&display=swap">
   <style>
      html, body {
         height: 100%;
         margin: 0;
         padding: 0;
         font-family: 'Nunito', sans-serif;
         background: #f6f8fb url('https://www.transparenttextures.com/patterns/cubes.png');
         background-size: 340px 340px;
         min-height: 100vh;
         overflow: hidden !important;
      }
      .login-advanced-container {
         height: 100vh;
         display: flex;
         align-items: center;
         justify-content: center;
         width: 100vw;
         margin: 0;
         padding: 0;
      }
      .login-advanced-card {
         background: #fff;
         border-radius: 1.5rem;
         box-shadow: 0 8px 32px 0 rgba(67,97,238,0.10);
         padding: 2.5rem 2.2rem 2.2rem 2.2rem;
         max-width: 370px;
         width: 100%;
         margin: 0 auto;
         display: flex;
         flex-direction: column;
         align-items: center;
         animation: fadeInCard 1.1s cubic-bezier(.68,-0.55,.27,1.55);
      }
      @keyframes fadeInCard {
         0% { opacity: 0; transform: translateY(60px) scale(0.95); }
         100% { opacity: 1; transform: translateY(0) scale(1); }
      }
      .login-advanced-card .logo {
         color: #4361ee;
         font-size: 1.3rem;
         font-weight: 900;
         letter-spacing: 1px;
         text-align: center;
         margin-bottom: 0.2rem;
         display: flex;
         align-items: center;
         gap: 0.5rem;
      }
      .login-advanced-card h1 {
         color: #222;
         font-size: 1.7rem;
         font-weight: 900;
         text-align: center;
         margin-bottom: 1.2rem;
         letter-spacing: 1px;
      }
      .login-advanced-card form {
         display: flex;
         flex-direction: column;
         gap: 1.1rem;
         width: 100%;
         max-width: 320px;
         margin: 0 auto;
         margin-top: 0.5rem;
         align-items: center;
      }
      .form-anim {
         position: relative;
         margin-bottom: 0.2rem;
         width: 100%;
         display: flex;
         justify-content: center;
      }
      .form-anim input {
         width: 270px;
         padding: 1.15rem 1.2rem 1.15rem 2.7rem;
         border: 1.5px solid #e0e6ed;
         border-radius: 1.2rem;
         font-size: 1.13rem;
         background: #f7faff;
         transition: border 0.2s, box-shadow 0.2s;
         outline: none;
         color: #222;
         box-shadow: 0 1px 4px 0 rgba(67,97,238,0.04);
      }
      .form-anim input:focus {
         border-color: #4361ee;
         box-shadow: 0 0 0 2px #4361ee33;
         background: #fff;
      }
      .form-anim label {
         position: absolute;
         left: 2.7rem;
         top: 50%;
         transform: translateY(-50%);
         color: #888;
         font-size: 1.08rem;
         pointer-events: none;
         transition: 0.2s cubic-bezier(.68,-0.55,.27,1.55);
         background: transparent;
      }
      .form-anim input:focus + label,
      .form-anim input:not(:placeholder-shown) + label {
         top: 0.3rem;
         left: 2.7rem;
         font-size: 0.98rem;
         color: #4361ee;
         background: #fff;
         padding: 0 0.2rem;
         border-radius: 0.2rem;
         animation: labelFloat 0.3s;
      }
      @keyframes labelFloat {
         0% { top: 50%; font-size: 1.08rem; }
         100% { top: 0.3rem; font-size: 0.98rem; }
      }
      .form-anim .input-icon {
         position: absolute;
         left: 1.1rem;
         top: 50%;
         transform: translateY(-50%);
         color: #4361ee;
         font-size: 1.18rem;
         opacity: 0.8;
         transition: transform 0.2s;
      }
      .form-anim input:focus ~ .input-icon {
         transform: translateY(-50%) scale(1.2);
         color: #222;
      }
      .show-hide {
         position: absolute;
         right: 1.2rem;
         top: 50%;
         transform: translateY(-50%);
         cursor: pointer;
         color: #888;
         font-size: 1.18rem;
         opacity: 0.8;
         transition: color 0.2s;
      }
      .show-hide:hover { color: #4361ee; }
      .btn-login {
         width: 100%;
         padding: 1.1rem;
         background: #4361ee;
         color: #fff;
         border: none;
         border-radius: 1.2rem;
         font-size: 1.18rem;
         font-weight: 700;
         cursor: pointer;
         transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
         box-shadow: 0 2px 8px #4361ee22;
         margin-top: 0.5rem;
      }
      .btn-login:hover {
         background: #2336a7;
         transform: translateY(-2px) scale(1.03);
         box-shadow: 0 4px 16px #2336a722;
      }
      .links {
         margin-top: 1.5rem;
         text-align: center;
         font-size: 1.08rem;
         color: #4361ee;
         display: flex;
         flex-direction: column;
         gap: 0.3rem;
      }
      .links a {
         color: #4361ee;
         text-decoration: none;
         font-weight: 600;
         transition: color 0.2s;
      }
      .links a:hover {
         color: #2336a7;
      }
      @media (max-width: 600px) {
         .login-advanced-card {
            padding: 1.2rem 0.5rem;
            max-width: 98vw;
         }
         .login-advanced-container {
            padding: 0;
         }
         .login-advanced-card h1 {
            font-size: 1.1rem;
         }
      }
      .role-toggle-group {
         display: flex;
         align-items: center;
         justify-content: center;
         background: #f1f3fa;
         border-radius: 2rem;
         margin-bottom: 1.2rem;
         box-shadow: 0 1px 4px 0 rgba(67,97,238,0.04);
         overflow: hidden;
         width: 220px;
         height: 38px;
      }
      .role-toggle-btn {
         flex: 1;
         border: none;
         outline: none;
         background: none;
         color: #4361ee;
         font-size: 1.08rem;
         font-weight: 700;
         padding: 0.5rem 0;
         cursor: pointer;
         transition: background 0.2s, color 0.2s;
         border-radius: 2rem;
      }
      .role-toggle-btn.selected {
         background: #4361ee;
         color: #fff;
         box-shadow: 0 2px 8px #4361ee22;
      }
   </style>
</head>
<body>
  <div class="login-advanced-container">
    <div class="login-advanced-card">
      <div class="logo"><i class="fas fa-graduation-cap"></i> Educa.</div>
      <h1>Sign In to Your Account</h1>
      <div class="role-toggle-group" id="roleToggleGroup">
        <button type="button" class="role-toggle-btn selected" id="studentBtn">Student</button>
        <button type="button" class="role-toggle-btn" id="teacherBtn">Teacher</button>
      </div>
      <input type="hidden" name="role" id="roleInput" value="student">
      <form action="" method="post" autocomplete="on">
        <?php if(isset($_POST['submit']) && isset($message)){ foreach($message as $msg){ echo '<div class="alert" role="alert"><i class="fas fa-exclamation-circle"></i> <span>'.$msg.'</span></div>'; } } ?>
        <div class="form-anim">
          <input type="email" name="email" id="email" required placeholder=" " autocomplete="username" aria-label="Email Address" />
          <label for="email">Email Address</label>
          <span class="input-icon"><i class="fas fa-envelope"></i></span>
        </div>
        <div class="form-anim">
          <input type="password" name="pass" id="password" required placeholder=" " autocomplete="current-password" aria-label="Password" />
          <label for="password">Password</label>
          <span class="input-icon"><i class="fas fa-lock"></i></span>
          <span class="show-hide" onclick="togglePassword()" title="Show/Hide Password" tabindex="0" aria-label="Show or hide password"><i class="fas fa-eye" id="eyeIcon"></i></span>
        </div>
        <button type="submit" name="submit" class="btn-login">
          <span>Login Now</span> <i class="fas fa-arrow-right"></i>
        </button>
      </form>
      <div class="links">
        <a href="#">Forgot password?</a>
        <a href="register.php">Create an account</a>
        <a href="home.php">Back to Home</a>
      </div>
    </div>
  </div>
  <script>
    function togglePassword() {
      const passInput = document.getElementById('password');
      const eyeIcon = document.getElementById('eyeIcon');
      if(passInput.type === 'password') {
        passInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
      } else {
        passInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
      }
    }
    // Role toggle logic
    const studentBtn = document.getElementById('studentBtn');
    const teacherBtn = document.getElementById('teacherBtn');
    const roleInput = document.getElementById('roleInput');
    studentBtn.addEventListener('click', function() {
      studentBtn.classList.add('selected');
      teacherBtn.classList.remove('selected');
      roleInput.value = 'student';
    });
    teacherBtn.addEventListener('click', function() {
      teacherBtn.classList.add('selected');
      studentBtn.classList.remove('selected');
      roleInput.value = 'teacher';
    });
    // Floating label effect for autofill
    window.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.form-anim input').forEach(function(input) {
        if(input.value) {
          input.classList.add('filled');
        }
        input.addEventListener('input', function() {
          if(this.value) this.classList.add('filled');
          else this.classList.remove('filled');
        });
      });
    });
    // Keyboard accessibility for show/hide password
    const showHide = document.querySelector('.show-hide');
    if(showHide) {
      showHide.addEventListener('keydown', function(e) {
        if(e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          togglePassword();
        }
      });
    }
  </script>
</body>
</html>