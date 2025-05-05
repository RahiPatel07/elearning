<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$page_title = "Teachers";
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Teachers - E-Learning Platform</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="../FRONT END/css/modern.css">
   <style>
      body, .container, section {
         background: var(--bg-main);
         color: var(--text-main);
      }

      .hero-section {
         background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
         padding: var(--spacing-xxl) 0;
         color: var(--white);
         text-align: center;
         margin-bottom: var(--spacing-xl);
      }

      .hero-section .heading {
         color: #fff !important;
         font-size: 4.8rem !important;
      }

      .hero-section .hero-subtitle {
         font-size: 1.5rem !important;
      }

      .search-tutor {
         max-width: 600px;
         margin: 0 auto var(--spacing-xl);
         position: relative;
      }

      .search-tutor input {
         width: 100%;
         padding: var(--spacing-md) var(--spacing-xl);
         border: 2px solid var(--gray-200);
         border-radius: var(--radius-full);
         font-size: 1.6rem;
         transition: all var(--transition-fast);
      }

      .search-tutor input:focus {
         border-color: var(--primary);
         box-shadow: var(--shadow-md);
      }

      .search-tutor button {
         position: absolute;
         right: var(--spacing-md);
         top: 50%;
         transform: translateY(-50%);
         background: none;
         border: none;
         color: var(--gray-600);
         font-size: 1.8rem;
         cursor: pointer;
         transition: color var(--transition-fast);
      }

      .search-tutor button:hover {
         color: var(--primary);
      }

      .teachers-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
         gap: var(--spacing-lg);
         padding: var(--spacing-lg);
      }

      .teacher-card {
         background: var(--white);
         border-radius: var(--radius-lg);
         overflow: hidden;
         transition: transform var(--transition-medium);
         box-shadow: var(--shadow-sm);
      }

      .teacher-card:hover {
         transform: translateY(-5px);
         box-shadow: var(--shadow-lg);
      }

      .teacher-header {
         display: flex;
         align-items: center;
         padding: var(--spacing-md);
         background: var(--gray-50);
      }

      .teacher-avatar {
         width: 80px;
         height: 80px;
         border-radius: var(--radius-full);
         object-fit: cover;
         margin-right: var(--spacing-md);
         border: 3px solid var(--white);
         box-shadow: var(--shadow-sm);
      }

      .teacher-info h3,
      .teacher-info span {
         color: #fff !important;
      }

      .teacher-info h3 {
         font-size: 1.8rem;
         margin: 0 0 var(--spacing-xs);
      }

      .teacher-info span {
         font-size: 1.4rem;
      }

      .teacher-stats {
         padding: var(--spacing-md);
         display: grid;
         grid-template-columns: repeat(2, 1fr);
         gap: var(--spacing-sm);
      }

      .stat-item {
         text-align: center;
         padding: var(--spacing-sm);
      }

      .stat-value {
         font-size: 2rem;
         font-weight: 600;
         color: var(--primary);
         margin-bottom: var(--spacing-xs);
      }

      .stat-label {
         font-size: 1.2rem;
         color: var(--gray-600);
      }

      .teacher-action {
         padding: var(--spacing-md);
         text-align: center;
      }

      .become-tutor {
         background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
         color: var(--white);
         padding: var(--spacing-xl);
         border-radius: var(--radius-lg);
         text-align: center;
         margin-bottom: var(--spacing-xl);
      }

      .become-tutor h2 {
         font-size: 2.4rem;
         margin-bottom: var(--spacing-md);
         color: var(--white);
      }

      .become-tutor p {
         font-size: 1.6rem;
         margin-bottom: var(--spacing-lg);
         opacity: 0.9;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Hero Section -->
<section class="hero-section">
   <h1 class="heading">Meet Our Expert Tutors</h1>
   <p class="hero-subtitle">Learn from industry professionals and subject matter experts</p>
</section>

<div class="container">
   <!-- Search Section -->
   <form action="search_tutor.php" method="post" class="search-tutor">
      <input type="text" name="search_tutor" maxlength="100" placeholder="Search for a tutor..." required>
      <button type="submit" name="search_tutor_btn" class="fas fa-search"></button>
   </form>

   <!-- Become a Tutor -->
   <div class="become-tutor">
      <h2>Share Your Knowledge</h2>
      <p>Join our community of expert tutors and help students achieve their learning goals</p>
      <a href="admin/register.php" class="btn btn-light">Become a Tutor</a>
   </div>

   <!-- Teachers Grid -->
   <div class="teachers-grid">
      <?php
         $select_tutors = $conn->prepare("SELECT * FROM `tutors`");
         $select_tutors->execute();
         if($select_tutors->rowCount() > 0){
            while($fetch_tutor = $select_tutors->fetch(PDO::FETCH_ASSOC)){
               $tutor_id = $fetch_tutor['id'];

               $count_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
               $count_playlists->execute([$tutor_id]);
               $total_playlists = $count_playlists->rowCount();

               $count_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
               $count_contents->execute([$tutor_id]);
               $total_contents = $count_contents->rowCount();

               $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
               $count_likes->execute([$tutor_id]);
               $total_likes = $count_likes->rowCount();

               $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
               $count_comments->execute([$tutor_id]);
               $total_comments = $count_comments->rowCount();
      ?>
      <div class="teacher-card">
         <div class="teacher-header">
            <img src="IMAGES/<?php 
               $tutor_img = $fetch_tutor['image'];
               $default_imgs = ['pic-2.jpg','pic-3.jpg','pic-4.jpg','pic-5.jpg','pic-6.jpg','pic-7.jpg'];
               $img_path = (!empty($tutor_img) && file_exists('IMAGES/'.$tutor_img)) ? $tutor_img : $default_imgs[array_rand($default_imgs)];
               echo $img_path;
            ?>" class="teacher-avatar" alt="">
            <div class="teacher-info">
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_tutor['profession']; ?></span>
            </div>
         </div>
         <div class="teacher-stats">
            <div class="stat-item">
               <div class="stat-value"><?= $total_playlists; ?></div>
               <div class="stat-label">Courses</div>
            </div>
            <div class="stat-item">
               <div class="stat-value"><?= $total_contents ?></div>
               <div class="stat-label">Videos</div>
            </div>
            <div class="stat-item">
               <div class="stat-value"><?= $total_likes ?></div>
               <div class="stat-label">Likes</div>
            </div>
            <div class="stat-item">
               <div class="stat-value"><?= $total_comments ?></div>
               <div class="stat-label">Comments</div>
            </div>
         </div>
         <div class="teacher-action">
            <form action="tutor_profile.php" method="post">
               <input type="hidden" name="tutor_email" value="<?= $fetch_tutor['email']; ?>">
               <button type="submit" name="tutor_fetch" class="btn btn-primary">View Profile</button>
            </form>
         </div>
      </div>
      <?php
            }
         }else{
            echo '<p class="text-center">No tutors found!</p>';
         }
      ?>
   </div>
</div>

<?php include 'components/footer.php'; ?>

<script src="../FRONT END/js/modern.js"></script>
   
</body>
</html>