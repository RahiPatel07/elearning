<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
}

if(isset($_POST['submit'])){

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
   $select_user->execute([$user_id]);
   $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

   $prev_pass = $fetch_user['password'];
   $prev_image = $fetch_user['image'];

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

  if(!empty($name)){
   $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
   $update_name->execute([$name, $user_id]);
   $message[] = 'username updated successfully!';
  }

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   if(!empty($email)){
      $select_email = $conn->prepare("SELECT email FROM `users` WHERE email = ?");
      $select_email->execute([$email]);
      if($select_email->rowCount() > 0){
         $message[] = 'email already taken!';
      }else{
         $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
         $update_email->execute([$email, $user_id]);
         $message[] = 'email updated successfully!';
      }
   }

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_files/'.$rename;

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'image size too large!';
      }else{
         $update_image = $conn->prepare("UPDATE `users` SET `image` = ? WHERE id = ?");
         $update_image->execute([$rename, $user_id]);
         move_uploaded_file($image_tmp_name, $image_folder);
         if($prev_image != '' AND $prev_image != $rename){
            unlink('uploaded_files/'.$prev_image);
         }
         $message[] = 'image updated successfully!';
      }
   }

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $message[] = 'old password not matched!';
      }elseif($new_pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_pass->execute([$cpass, $user_id]);
            $message[] = 'password updated successfully!';
         }else{
            $message[] = 'please enter a new password!';
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
   <title>Update Profile | E-Learning Platform</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../FRONT END/css/modern.css">
   <style>
      body { background: #f6f8fb; }
      .update-profile-card {
         background: #fff;
         border-radius: 2rem;
         box-shadow: 0 8px 32px 0 rgba(67,97,238,0.10);
         max-width: 440px;
         margin: 3rem auto 2rem auto;
         padding: 2.5rem 2rem 2rem 2rem;
         display: flex;
         flex-direction: column;
         align-items: center;
         animation: fadeInCard 1.1s cubic-bezier(.68,-0.55,.27,1.55);
      }
      @keyframes fadeInCard {
         0% { opacity: 0; transform: translateY(60px) scale(0.95); }
         100% { opacity: 1; transform: translateY(0) scale(1); }
      }
      .update-profile-avatar {
         width: 90px;
         height: 90px;
         border-radius: 50%;
         object-fit: cover;
         box-shadow: 0 2px 8px 0 rgba(67,97,238,0.08);
         margin-bottom: 1.2rem;
         background: #f5f7fa;
      }
      .update-profile-title {
         font-size: 1.7rem;
         font-weight: 900;
         color: #222;
         margin-bottom: 1.2rem;
         text-align: center;
      }
      .update-profile-form {
         width: 100%;
         display: flex;
         flex-direction: column;
         gap: 1.1rem;
         align-items: center;
      }
      .form-group {
         width: 100%;
         display: flex;
         flex-direction: column;
         gap: 0.3rem;
      }
      .form-label {
         font-weight: 600;
         color: #4361ee;
         margin-bottom: 0.2rem;
         font-size: 1.08rem;
      }
      .form-input, .form-file {
         width: 100%;
         padding: 1rem 1.2rem;
         border: 1.5px solid #e0e6ed;
         border-radius: 1.2rem;
         font-size: 1.13rem;
         background: #f7faff;
         transition: border 0.2s, box-shadow 0.2s;
         outline: none;
         color: #222;
         box-shadow: 0 1px 4px 0 rgba(67,97,238,0.04);
      }
      .form-input:focus, .form-file:focus {
         border-color: #4361ee;
         box-shadow: 0 0 0 2px #4361ee33;
         background: #fff;
      }
      .form-file {
         padding: 0.7rem 1.2rem;
         background: #fff;
      }
      .update-btn {
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
         margin-top: 0.7rem;
      }
      .update-btn:hover {
         background: #2336a7;
         transform: translateY(-2px) scale(1.03);
         box-shadow: 0 4px 16px #2336a722;
      }
      @media (max-width: 600px) {
         .update-profile-card { max-width: 98vw; padding: 1.2rem 0.5rem; }
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="update-profile-card">
   <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="Profile" class="update-profile-avatar">
   <div class="update-profile-title">Update Profile</div>
   <form action="" method="post" enctype="multipart/form-data" class="update-profile-form">
      <div class="form-group">
         <label class="form-label" for="name">Your Name</label>
         <input type="text" name="name" id="name" value="<?= $fetch_profile['name']; ?>" maxlength="100" class="form-input">
      </div>
      <div class="form-group">
         <label class="form-label" for="email">Your Email</label>
         <input type="email" name="email" id="email" value="<?= $fetch_profile['email']; ?>" maxlength="100" class="form-input">
      </div>
      <div class="form-group">
         <label class="form-label" for="image">Update Pic</label>
         <input type="file" name="image" id="image" accept="image/*" class="form-file">
      </div>
      <div class="form-group">
         <label class="form-label" for="old_pass">Old Password</label>
         <input type="password" name="old_pass" id="old_pass" placeholder="Enter your old password" maxlength="50" class="form-input">
      </div>
      <div class="form-group">
         <label class="form-label" for="new_pass">New Password</label>
         <input type="password" name="new_pass" id="new_pass" placeholder="Enter your new password" maxlength="50" class="form-input">
      </div>
      <div class="form-group">
         <label class="form-label" for="cpass">Confirm Password</label>
         <input type="password" name="cpass" id="cpass" placeholder="Confirm your new password" maxlength="50" class="form-input">
      </div>
      <button type="submit" name="submit" class="update-btn">Update Profile</button>
   </form>
</div>

<?php include 'components/footer.php'; ?>

<script src="../FRONT END/js/modern.js"></script>
</body>
</html>