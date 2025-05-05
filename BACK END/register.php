<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$page_title = "Register";

if(isset($_POST['submit'])){

   $id = unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
   $role = $_POST['role'];

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);
   
   if($select_user->rowCount() > 0){
      $message[] = 'email already taken!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm passowrd not matched!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(id, name, email, password, role) VALUES(?,?,?,?,?)");
         $insert_user->execute([$id, $name, $email, $cpass, $role]);
         
         $verify_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
         $verify_user->execute([$email, $pass]);
         $row = $verify_user->fetch(PDO::FETCH_ASSOC);
         
         if($verify_user->rowCount() > 0){
            setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
            header('location:home.php');
         }
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register | E-Learning Platform</title>
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
      .register-advanced-container {
         height: 100vh;
         display: flex;
         align-items: center;
         justify-content: center;
         width: 100vw;
         margin: 0;
         padding: 0;
      }
      .register-advanced-card {
         background: #fff;
         border-radius: 1.5rem;
         box-shadow: 0 8px 32px 0 rgba(67,97,238,0.10);
         padding: 1.5rem 1rem;
         max-width: 340px;
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
      .register-advanced-card .logo {
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
      .register-advanced-card h1 {
         color: #222;
         font-size: 1.7rem;
         font-weight: 900;
         text-align: center;
         margin-bottom: 1.2rem;
         letter-spacing: 1px;
      }
      .register-advanced-card form {
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
      .custom-file {
         position: relative;
         margin-bottom: 0.5rem;
         width: 100%;
         display: flex;
         justify-content: center;
      }
      .custom-file-input {
         position: absolute;
         left: 0;
         top: 0;
         width: 100%;
         height: 100%;
         opacity: 0;
         cursor: pointer;
      }
      .custom-file-label {
         display: flex;
         align-items: center;
         gap: 1rem;
         padding: 1.1rem 1rem;
         border: 2px dashed #e0e6ed;
         border-radius: 0.75rem;
         font-size: 1.1rem;
         color: #888;
         background: #f8f9fa;
         transition: border 0.2s, color 0.2s;
         cursor: pointer;
         width: 270px;
         justify-content: center;
      }
      .custom-file-input:hover + .custom-file-label {
         border-color: #4361ee;
         color: #4361ee;
      }
      .form-check {
         display: flex;
         align-items: flex-start;
         gap: 1rem;
         margin-bottom: 0.5rem;
         font-size: 1rem;
         width: 100%;
         justify-content: center;
      }
      .form-check-input {
         width: 1.2rem;
         height: 1.2rem;
         margin-top: 0.2rem;
         border-radius: 6px;
         border: 2px solid #e0e6ed;
         cursor: pointer;
      }
      .form-check-label {
         color: #888;
         line-height: 1.4;
      }
      .form-check-label a {
         color: #4361ee;
         text-decoration: none;
      }
      .form-check-label a:hover {
         color: #2336a7;
      }
      .btn-register {
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
         display: flex;
         align-items: center;
         justify-content: center;
         gap: 0.7rem;
      }
      .btn-register:hover {
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
         .register-advanced-card {
            padding: 1rem 0.3rem;
            max-width: 98vw;
         }
         .register-advanced-container {
            padding: 0;
         }
         .register-advanced-card h1 {
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
  <div class="register-advanced-container">
    <div class="register-advanced-card">
      <div class="logo"><i class="fas fa-graduation-cap"></i> Educa.</div>
      <h1>Create Account</h1>
      <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
        <?php if(isset($message)){ foreach($message as $msg){ echo '<div class="alert" role="alert"><i class="fas fa-exclamation-circle"></i> <span>'.$msg.'</span></div>'; } } ?>
        <div class="role-toggle-group" id="roleToggleGroup">
          <button type="button" class="role-toggle-btn selected" id="studentBtn">Student</button>
          <button type="button" class="role-toggle-btn" id="teacherBtn">Teacher</button>
        </div>
        <input type="hidden" name="role" id="roleInput" value="student">
        <div class="form-anim">
          <input type="text" name="name" id="name" required placeholder=" " autocomplete="name" />
          <label for="name">Full Name</label>
          <span class="input-icon"><i class="fas fa-user"></i></span>
        </div>
        <div class="form-anim">
          <input type="email" name="email" id="email" required placeholder=" " autocomplete="email" />
          <label for="email">Email Address</label>
          <span class="input-icon"><i class="fas fa-envelope"></i></span>
        </div>
        <div class="form-anim">
          <input type="password" name="pass" id="password" required placeholder=" " autocomplete="new-password" />
          <label for="password">Password</label>
          <span class="input-icon"><i class="fas fa-lock"></i></span>
          <span class="show-hide" onclick="togglePassword('password', 'eyeIcon1')"><i class="fas fa-eye" id="eyeIcon1"></i></span>
        </div>
        <div class="form-anim">
          <input type="password" name="cpass" id="cpassword" required placeholder=" " autocomplete="new-password" />
          <label for="cpassword">Confirm Password</label>
          <span class="input-icon"><i class="fas fa-lock"></i></span>
          <span class="show-hide" onclick="togglePassword('cpassword', 'eyeIcon2')"><i class="fas fa-eye" id="eyeIcon2"></i></span>
        </div>
        <div class="form-check">
          <input type="checkbox" id="terms" required class="form-check-input">
          <label for="terms" class="form-check-label">
            I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
          </label>
        </div>
        <button type="submit" name="submit" class="btn-register">
          <span>Create Account</span> <i class="fas fa-arrow-right"></i>
        </button>
      </form>
      <div class="links">
        <a href="login.php">Already have an account? Login Now</a>
        <a href="home.php">Back to Home</a>
      </div>
    </div>
  </div>
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
  <script>
    function togglePassword(inputId, eyeId) {
      const passInput = document.getElementById(inputId);
      const eyeIcon = document.getElementById(eyeId);
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
  </script>
</body>
</html>