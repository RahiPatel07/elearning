<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['tutor_fetch'])){

   $tutor_email = $_POST['tutor_email'];
   $tutor_email = filter_var($tutor_email, FILTER_SANITIZE_STRING);
   $select_tutor = $conn->prepare('SELECT * FROM `tutors` WHERE email = ?');
   $select_tutor->execute([$tutor_email]);

   $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
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

}else{
   header('location:teachers.php');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tutor Profile - E-Learning Platform</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="../FRONT END/css/modern.css">
   <style>
      .profile-section {
         background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
         padding: var(--spacing-xxl) 0;
         color: var(--white);
         margin-bottom: var(--spacing-xl);
      }

      .profile-content {
         max-width: 1200px;
         margin: 0 auto;
         padding: 0 var(--spacing-md);
      }

      .profile-header {
         display: flex;
         align-items: center;
         gap: var(--spacing-xl);
         margin-bottom: var(--spacing-xl);
      }

      .profile-img {
         width: 150px;
         height: 150px;
         border-radius: var(--radius-full);
         object-fit: cover;
         border: 4px solid var(--white);
         box-shadow: var(--shadow-lg);
      }

      .profile-info {
         flex: 1;
      }

      .profile-name {
         font-size: 3.2rem;
         margin-bottom: var(--spacing-xs);
      }

      .profile-profession {
         font-size: 1.8rem;
         opacity: 0.9;
         margin-bottom: var(--spacing-md);
      }

      .profile-stats {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
         gap: var(--spacing-md);
         background: rgba(255, 255, 255, 0.1);
         padding: var(--spacing-md);
         border-radius: var(--radius-lg);
      }

      .stat-item {
         text-align: center;
      }

      .stat-number {
         font-size: 2.4rem;
         font-weight: 600;
         margin-bottom: var(--spacing-xs);
      }

      .stat-label {
         font-size: 1.4rem;
         opacity: 0.9;
      }

      .courses-section {
         padding: var(--spacing-xl) 0;
      }

      .section-title {
         font-size: 2.4rem;
         color: var(--secondary);
         margin-bottom: var(--spacing-xl);
         text-align: center;
      }

      .courses-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
         gap: var(--spacing-lg);
         padding: var(--spacing-md);
      }

      .course-card {
         background: var(--white);
         border-radius: var(--radius-lg);
         overflow: hidden;
         box-shadow: var(--shadow-md);
         transition: transform var(--transition-medium);
      }

      .course-card:hover {
         transform: translateY(-5px);
      }

      .course-thumb {
         width: 100%;
         height: 200px;
         object-fit: cover;
      }

      .course-content {
         padding: var(--spacing-md);
      }

      .course-tutor {
         display: flex;
         align-items: center;
         gap: var(--spacing-md);
         margin-bottom: var(--spacing-md);
      }

      .tutor-img {
         width: 40px;
         height: 40px;
         border-radius: var(--radius-full);
         object-fit: cover;
      }

      .tutor-info h3 {
         font-size: 1.4rem;
         margin: 0;
      }

      .tutor-info span {
         font-size: 1.2rem;
         color: var(--gray-600);
      }

      .course-title {
         font-size: 1.8rem;
         color: var(--secondary);
         margin-bottom: var(--spacing-md);
      }

      .view-btn {
         display: inline-block;
         padding: var(--spacing-sm) var(--spacing-md);
         background: var(--primary);
         color: var(--white);
         border-radius: var(--radius-md);
         text-decoration: none;
         font-weight: 600;
         transition: all var(--transition-medium);
      }

      .view-btn:hover {
         background: var(--primary-dark);
      }

      .empty {
         text-align: center;
         padding: var(--spacing-xl);
         color: var(--gray-600);
         font-size: 1.6rem;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Profile Section -->
<section class="profile-section">
   <div class="profile-content">
      <div class="profile-header">
         <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="" class="profile-img">
         <div class="profile-info">
            <h1 class="profile-name"><?= $fetch_tutor['name']; ?></h1>
            <p class="profile-profession"><?= $fetch_tutor['profession']; ?></p>
            <div class="profile-stats">
               <div class="stat-item">
                  <div class="stat-number"><?= $total_playlists; ?></div>
                  <div class="stat-label">Playlists</div>
               </div>
               <div class="stat-item">
                  <div class="stat-number"><?= $total_contents; ?></div>
                  <div class="stat-label">Videos</div>
               </div>
               <div class="stat-item">
                  <div class="stat-number"><?= $total_likes; ?></div>
                  <div class="stat-label">Likes</div>
               </div>
               <div class="stat-item">
                  <div class="stat-number"><?= $total_comments; ?></div>
                  <div class="stat-label">Comments</div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<!-- Courses Section -->
<section class="courses-section">
   <div class="container">
      <h2 class="section-title">Latest Courses</h2>
      <div class="courses-grid">
         <?php
            $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ? AND status = ?");
            $select_courses->execute([$tutor_id, 'active']);
            if($select_courses->rowCount() > 0){
               while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
                  $course_id = $fetch_course['id'];

                  $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
                  $select_tutor->execute([$fetch_course['tutor_id']]);
                  $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
         ?>
         <div class="course-card">
            <img src="../IMAGES/<?= (!empty($fetch_course['thumb']) && file_exists(__DIR__.'/../IMAGES/'.$fetch_course['thumb'])) ? $fetch_course['thumb'] : 'default-thumb.png'; ?>" class="course-thumb" alt="">
            <div class="course-content">
               <div class="course-tutor">
                  <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" class="tutor-img" alt="">
                  <div class="tutor-info">
                     <h3><?= $fetch_tutor['name']; ?></h3>
                     <span><?= $fetch_course['date']; ?></span>
                  </div>
               </div>
               <h3 class="course-title"><?= $fetch_course['title']; ?></h3>
               <a href="playlist.php?get_id=<?= $course_id; ?>" class="view-btn">View Playlist</a>
            </div>
         </div>
         <?php
            }
         }else{
            echo '<p class="empty">No courses added yet!</p>';
         }
         ?>
      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>    

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>