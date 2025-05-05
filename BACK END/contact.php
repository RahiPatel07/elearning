<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $message = $_POST['message'];
   $message = filter_var($message, FILTER_SANITIZE_STRING);

   $select_contact = $conn->prepare("SELECT * FROM `contact` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_contact->execute([$name, $email, $number, $message]);

   if($select_contact->rowCount() > 0){
      $message[] = 'Message already sent!';
   }else{
      $insert_message = $conn->prepare("INSERT INTO `contact`(name, email, number, message) VALUES(?,?,?,?)");
      $insert_message->execute([$name, $email, $number, $message]);
      $message[] = 'Message sent successfully!';
   }
}

$page_title = "Contact Us";
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact Us - E-Learning Platform</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="../FRONT END/css/modern.css">
   <style>
      .hero-section {
         background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
         padding: var(--spacing-xxl) 0;
         color: var(--white);
         text-align: center;
         margin-bottom: var(--spacing-xl);
      }

      .contact-container {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
         gap: var(--spacing-xl);
         padding: var(--spacing-xl) var(--spacing-lg);
         max-width: 1200px;
         margin: 0 auto;
      }

      .contact-info {
         background: var(--gray-50);
         padding: var(--spacing-xl);
         border-radius: var(--radius-lg);
      }

      .contact-item {
         display: flex;
         align-items: center;
         margin-bottom: var(--spacing-lg);
      }

      .contact-icon {
         width: 50px;
         height: 50px;
         background: var(--primary-light);
         border-radius: var(--radius-full);
         display: flex;
         align-items: center;
         justify-content: center;
         margin-right: var(--spacing-md);
         color: var(--primary);
         font-size: 2rem;
      }

      .contact-text h3 {
         font-size: 1.6rem;
         color: var(--secondary);
         margin-bottom: var(--spacing-xs);
      }

      .contact-text p {
         font-size: 1.4rem;
         color: var(--gray-600);
      }

      .contact-form {
         background: var(--white);
         padding: var(--spacing-xl);
         border-radius: var(--radius-lg);
         box-shadow: var(--shadow-lg);
      }

      .form-group {
         margin-bottom: var(--spacing-md);
      }

      .form-group label {
         display: block;
         font-size: 1.4rem;
         color: var(--secondary);
         margin-bottom: var(--spacing-xs);
      }

      .form-group input,
      .form-group textarea {
         width: 100%;
         padding: var(--spacing-sm) var(--spacing-md);
         border: 2px solid var(--gray-200);
         border-radius: var(--radius-md);
         font-size: 1.6rem;
         transition: all var(--transition-fast);
      }

      .form-group input:focus,
      .form-group textarea:focus {
         border-color: var(--primary);
         box-shadow: var(--shadow-sm);
      }

      .form-group textarea {
         height: 150px;
         resize: vertical;
      }

      .social-links {
         display: flex;
         gap: var(--spacing-md);
         margin-top: var(--spacing-lg);
      }

      .social-link {
         width: 40px;
         height: 40px;
         background: var(--white);
         border-radius: var(--radius-full);
         display: flex;
         align-items: center;
         justify-content: center;
         color: var(--primary);
         font-size: 1.8rem;
         transition: all var(--transition-fast);
      }

      .social-link:hover {
         background: var(--primary);
         color: var(--white);
         transform: translateY(-3px);
      }

      .heading {
         color: #fff !important;
      }

      .contact-info * {
         color: #fff !important;
      }

      .social-icon-color {
         color: var(--primary) !important;
      }

      .contact-form .heading {
         color: #fff !important;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Hero Section -->
<section class="hero-section">
   <div class="container">
      <h1 class="heading">Contact Us</h1>
      <p class="hero-subtitle">Get in touch with our support team</p>
   </div>
</section>

<div class="contact-container">
   <!-- Contact Information -->
   <div class="contact-info">
      <h2 class="heading">Get In Touch</h2>
      <p class="mb-4">Have questions? We're here to help!</p>

      <div class="contact-item">
         <div class="contact-icon">
            <i class="fas fa-map-marker-alt"></i>
         </div>
         <div class="contact-text">
            <h3>Our Location</h3>
            <p>123 Education Street, Learning City, 12345</p>
         </div>
      </div>

      <div class="contact-item">
         <div class="contact-icon">
            <i class="fas fa-phone"></i>
         </div>
         <div class="contact-text">
            <h3>Phone Number</h3>
            <p>+1 (234) 567-8900</p>
         </div>
      </div>

      <div class="contact-item">
         <div class="contact-icon">
            <i class="fas fa-envelope"></i>
         </div>
         <div class="contact-text">
            <h3>Email Address</h3>
            <p>support@elearning.com</p>
         </div>
      </div>

      <div class="social-links">
         <a href="#" class="social-link"><i class="fab fa-facebook-f social-icon-color"></i></a>
         <a href="#" class="social-link"><i class="fab fa-twitter social-icon-color"></i></a>
         <a href="#" class="social-link"><i class="fab fa-instagram social-icon-color"></i></a>
         <a href="#" class="social-link"><i class="fab fa-linkedin-in social-icon-color"></i></a>
      </div>
   </div>

   <!-- Contact Form -->
   <div class="contact-form">
      <h2 class="heading">Send us a Message</h2>
      <form action="" method="post">
         <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" name="name" placeholder="Enter your name" maxlength="50" required class="form-control">
         </div>
         <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="form-control">
         </div>
         <div class="form-group">
            <label for="number">Phone Number</label>
            <input type="number" name="number" placeholder="Enter your number" max="9999999999" min="0" maxlength="10" required class="form-control">
         </div>
         <div class="form-group">
            <label for="message">Message</label>
            <textarea name="message" class="form-control" placeholder="Enter your message" maxlength="1000" required></textarea>
         </div>
         <button type="submit" name="submit" class="btn btn-primary btn-block">Send Message</button>
      </form>
   </div>
</div>

<?php include 'components/footer.php'; ?>

<script src="../FRONT END/js/modern.js"></script>

</body>
</html>