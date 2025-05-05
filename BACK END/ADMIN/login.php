<?php

include '../components/connect.php';

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ? AND password = ? LIMIT 1");
   $select_tutor->execute([$email, $pass]);
   $row = $select_tutor->fetch(PDO::FETCH_ASSOC);
   
   if($select_tutor->rowCount() > 0){
     setcookie('tutor_id', $row['id'], time() + 60*60*24*30, '/');
     header('location:dashboard.php');
   }else{
      $message[] = 'incorrect email or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login - E-Learning Platform</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="../../FRONT END/css/modern.css">
   <style>
      .login-advanced-container {
         min-height: 100vh;
         display: flex;
         align-items: center;
         justify-content: center;
         background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
         padding: 2rem;
         position: relative;
         overflow: hidden;
      }
      .login-advanced-container::before {
         content: '';
         position: absolute;
         top: 0;
         left: 0;
         right: 0;
         bottom: 0;
         background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%234361ee' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
         opacity: 0.5;
      }
      .login-advanced-card {
         background: rgba(255, 255, 255, 0.95);
         backdrop-filter: blur(10px);
         border-radius: 1.5rem;
         box-shadow: 0 10px 30px rgba(0,0,0,0.1);
         padding: 2.5rem;
         width: 100%;
         max-width: 500px;
         position: relative;
         overflow: hidden;
         z-index: 1;
      }
      .logo {
         font-size: 1.8rem;
         font-weight: 700;
         color: #4361ee;
         margin-bottom: 1.5rem;
         display: flex;
         align-items: center;
         gap: 0.5rem;
      }
      .logo i {
         font-size: 2rem;
      }
      h1 {
         font-size: 1.8rem;
         color: #2d3748;
         margin-bottom: 2rem;
         text-align: center;
      }
      .form-anim {
         position: relative;
         margin-bottom: 1.5rem;
      }
      .form-anim input {
         width: 100%;
         padding: 1.2rem 1rem 1.2rem 3rem;
         border: 2px solid #e0e6ed;
         border-radius: 1.2rem;
         font-size: 1.1rem;
         transition: all 0.3s;
         background: #f8fafc;
      }
      .form-anim input:focus {
         border-color: #4361ee;
         background: #fff;
         box-shadow: 0 0 0 4px rgba(67,97,238,0.1);
      }
      .form-anim label {
         position: absolute;
         left: 3rem;
         top: 50%;
         transform: translateY(-50%);
         color: #64748b;
         pointer-events: none;
         transition: all 0.3s;
      }
      .form-anim input:focus + label,
      .form-anim input:not(:placeholder-shown) + label {
         top: 0;
         left: 1rem;
         font-size: 0.9rem;
         padding: 0 0.5rem;
         background: #fff;
         color: #4361ee;
      }
      .input-icon {
         position: absolute;
         left: 1rem;
         top: 50%;
         transform: translateY(-50%);
         color: #64748b;
         font-size: 1.2rem;
      }
      .show-hide {
         position: absolute;
         right: 1rem;
         top: 50%;
         transform: translateY(-50%);
         color: #64748b;
         cursor: pointer;
         font-size: 1.2rem;
      }
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
         display: flex;
         align-items: center;
         justify-content: center;
         gap: 0.7rem;
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
      .alert {
         background: #fee2e2;
         color: #dc2626;
         padding: 1rem;
         border-radius: 1rem;
         margin-bottom: 1.5rem;
         display: flex;
         align-items: center;
         gap: 0.5rem;
         font-size: 1rem;
      }
      .alert i {
         font-size: 1.2rem;
      }
      @media (max-width: 600px) {
         .login-advanced-card {
            padding: 1.5rem;
         }
         h1 {
            font-size: 1.5rem;
         }
      }
   </style>
</head>
<body>
   <div class="login-advanced-container">
      <div class="login-advanced-card">
         <div class="logo"><i class="fas fa-graduation-cap"></i> Educa.</div>
         <h1>Welcome Back!</h1>
         <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
            <?php if(isset($message)){ foreach($message as $msg){ echo '<div class="alert" role="alert"><i class="fas fa-exclamation-circle"></i> <span>'.$msg.'</span></div>'; } } ?>
            
            <div class="form-anim">
               <input type="email" name="email" id="email" required placeholder=" " autocomplete="email" />
               <label for="email">Email Address</label>
               <span class="input-icon"><i class="fas fa-envelope"></i></span>
            </div>

            <div class="form-anim">
               <input type="password" name="pass" id="password" required placeholder=" " autocomplete="current-password" />
               <label for="password">Password</label>
               <span class="input-icon"><i class="fas fa-lock"></i></span>
               <span class="show-hide" onclick="togglePassword('password', 'eyeIcon')"><i class="fas fa-eye" id="eyeIcon"></i></span>
            </div>

            <button type="submit" name="submit" class="btn-login">
               <span>Login Now</span> <i class="fas fa-arrow-right"></i>
            </button>
         </form>

         <div class="links">
            <a href="register.php">Don't have an account? Register Now</a>
            <a href="../home.php">Back to Home</a>
         </div>
      </div>
   </div>

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