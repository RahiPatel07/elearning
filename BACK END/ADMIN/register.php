<?php

include '../components/connect.php';

if(isset($_POST['submit'])){

   $id = unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $profession = $_POST['profession'];
   $profession = filter_var($profession, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_files/'.$rename;

   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ?");
   $select_tutor->execute([$email]);
   
   if($select_tutor->rowCount() > 0){
      $message[] = 'email already taken!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm passowrd not matched!';
      }else{
         $insert_tutor = $conn->prepare("INSERT INTO `tutors`(id, name, profession, email, password, image) VALUES(?,?,?,?,?,?)");
         $insert_tutor->execute([$id, $name, $profession, $email, $cpass, $rename]);
         move_uploaded_file($image_tmp_name, $image_folder);
         $message[] = 'new tutor registered! please login now';
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
   <title>Register - E-Learning Platform</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="../../FRONT END/css/modern.css">
   <style>
      .register-advanced-container {
         min-height: 100vh;
         display: flex;
         align-items: center;
         justify-content: center;
         background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
         padding: 2rem;
         position: relative;
         overflow: hidden;
      }
      .register-advanced-container::before {
         content: '';
         position: absolute;
         top: 0;
         left: 0;
         right: 0;
         bottom: 0;
         background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%234361ee' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
         opacity: 0.5;
      }
      .register-advanced-card {
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
      .form-check {
         display: flex;
         align-items: flex-start;
         gap: 0.8rem;
         margin-bottom: 1.5rem;
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
      .profession-select {
         width: 100%;
         padding: 1.2rem 1rem 1.2rem 3rem;
         border: 2px solid #e0e6ed;
         border-radius: 1.2rem;
         font-size: 1.1rem;
         transition: all 0.3s;
         background: #f8fafc;
         appearance: none;
         cursor: pointer;
      }
      .profession-select:focus {
         border-color: #4361ee;
         background: #fff;
         box-shadow: 0 0 0 4px rgba(67,97,238,0.1);
      }
      .profession-icon {
         position: absolute;
         left: 1rem;
         top: 50%;
         transform: translateY(-50%);
         color: #64748b;
         font-size: 1.2rem;
         pointer-events: none;
      }
      .file-input-container {
         position: relative;
         margin-bottom: 1.5rem;
      }
      .file-input-label {
         display: block;
         padding: 1.2rem 1rem;
         border: 2px solid #e0e6ed;
         border-radius: 1.2rem;
         font-size: 1.1rem;
         color: #64748b;
         background: #f8fafc;
         cursor: pointer;
         transition: all 0.3s;
         text-align: center;
      }
      .file-input-label:hover {
         border-color: #4361ee;
         background: #fff;
      }
      .file-input {
         position: absolute;
         width: 0;
         height: 0;
         opacity: 0;
      }
      @media (max-width: 600px) {
         .register-advanced-card {
            padding: 1.5rem;
         }
         h1 {
            font-size: 1.5rem;
         }
      }
   </style>
</head>
<body>
   <div class="register-advanced-container">
      <div class="register-advanced-card">
         <div class="logo"><i class="fas fa-graduation-cap"></i> Educa.</div>
         <h1>Register as Tutor</h1>
         <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
            <?php if(isset($message)){ foreach($message as $msg){ echo '<div class="alert" role="alert"><i class="fas fa-exclamation-circle"></i> <span>'.$msg.'</span></div>'; } } ?>
            
            <div class="form-anim">
               <input type="text" name="name" id="name" required placeholder=" " autocomplete="name" />
               <label for="name">Full Name</label>
               <span class="input-icon"><i class="fas fa-user"></i></span>
            </div>

            <div class="form-anim">
               <select name="profession" id="profession" class="profession-select" required>
                  <option value="" disabled selected>Select your profession</option>
                  <option value="developer">Developer</option>
                  <option value="designer">Designer</option>
                  <option value="musician">Musician</option>
                  <option value="biologist">Biologist</option>
                  <option value="teacher">Teacher</option>
                  <option value="engineer">Engineer</option>
                  <option value="lawyer">Lawyer</option>
                  <option value="accountant">Accountant</option>
                  <option value="doctor">Doctor</option>
                  <option value="journalist">Journalist</option>
                  <option value="photographer">Photographer</option>
               </select>
               <span class="profession-icon"><i class="fas fa-briefcase"></i></span>
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

            <div class="file-input-container">
               <label for="image" class="file-input-label">
                  <i class="fas fa-upload"></i> Select Profile Picture
               </label>
               <input type="file" name="image" id="image" accept="image/*" required class="file-input">
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