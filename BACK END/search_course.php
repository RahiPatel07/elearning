<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search Courses - E-Learning Platform</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="../FRONT END/css/modern.css">
   <style>
      .search-section {
         background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
         padding: var(--spacing-xxl) 0;
         color: var(--white);
         text-align: center;
         margin-bottom: var(--spacing-xl);
      }

      .search-content {
         max-width: 800px;
         margin: 0 auto;
         padding: 0 var(--spacing-md);
      }

      .search-title {
         font-size: 3.2rem;
         color: var(--white);
         margin-bottom: var(--spacing-md);
      }

      .courses-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
         gap: var(--spacing-lg);
         padding: var(--spacing-lg);
      }

      .course-card {
         background: var(--white);
         border-radius: var(--radius-lg);
         overflow: hidden;
         transition: transform var(--transition-medium);
         box-shadow: var(--shadow-md);
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
         margin-bottom: var(--spacing-sm);
      }

      .tutor-img {
         width: 40px;
         height: 40px;
         border-radius: var(--radius-full);
         margin-right: var(--spacing-sm);
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
         margin: var(--spacing-sm) 0;
      }

      .video-embed {
         width: 100%;
         height: 200px;
         margin-bottom: var(--spacing-sm);
         border-radius: var(--radius-md);
         overflow: hidden;
      }

      .empty {
         text-align: center;
         padding: var(--spacing-xl);
         color: var(--gray-600);
         font-size: 1.6rem;
      }

      .hero-section .heading {
         color: #fff !important;
         font-size: 4.8rem !important;
      }
      .hero-section .hero-subtitle {
         font-size: 1.5rem !important;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Search Section -->
<section class="search-section">
   <div class="search-content">
      <h1 class="search-title">Search Results</h1>
   </div>
</section>

<!-- Courses Section -->
<section class="container">
   <div class="courses-grid">
      <?php
         if(isset($_POST['search_course']) or isset($_POST['search_course_btn'])){
         $search_course = $_POST['search_course'];
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE title LIKE ? AND status = ?");
         $search_term = "%{$search_course}%";
         $select_courses->execute([$search_term, 'active']);
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
            <a href="playlist.php?get_id=<?= $course_id; ?>" class="btn">View Playlist</a>
         </div>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">No courses found!</p>';
      }
      // Show videos (content) matching the search
      $select_videos = $conn->prepare("SELECT * FROM `content` WHERE (title LIKE ? OR description LIKE ?) AND status = ?");
      $select_videos->execute([$search_term, $search_term, 'active']);
      if($select_videos->rowCount() > 0){
         while($fetch_video = $select_videos->fetch(PDO::FETCH_ASSOC)){
            $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
            $select_tutor->execute([$fetch_video['tutor_id']]);
            $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
            // Extract YouTube video ID
            $video_url = $fetch_video['video'];
            $video_id = '';
            if (preg_match('/(?:youtu.be\/|youtube.com\/(?:watch\?v=|embed\/))([\w-]{11})/', $video_url, $matches)) {
               $video_id = $matches[1];
            }
      ?>
      <div class="course-card">
         <div class="video-embed">
            <?php if($video_id): ?>
            <iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?= $video_id; ?>" frameborder="0" allowfullscreen></iframe>
            <?php else: ?>
            <p>Video unavailable</p>
            <?php endif; ?>
         </div>
         <div class="course-content">
            <div class="course-tutor">
               <img src="../IMAGES/<?= $fetch_tutor['image']; ?>" class="tutor-img" alt="">
               <div class="tutor-info">
                  <h3><?= $fetch_tutor['name']; ?></h3>
                  <span><?= $fetch_video['date']; ?></span>
               </div>
            </div>
            <h3 class="course-title"><?= $fetch_video['title']; ?></h3>
            <p><?= $fetch_video['description']; ?></p>
         </div>
      </div>
      <?php
         }
      } else {
         echo '<p class="empty">No videos found!</p>';
      }
      }else{
         echo '<p class="empty">Please search something!</p>';
      }
      ?>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>