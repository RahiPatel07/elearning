<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$page_title = "Courses";
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Courses - E-Learning Platform</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../FRONT END/css/modern.css">
   <style>
:root {
   --primary-color: #4338ca;
   --secondary-color: #3730a3;
   --accent-color: #f72585;
   --background-light: var(--gray-50);
   --background-dark: var(--bg-primary);
   --card-bg-light: var(--white);
   --card-bg-dark: var(--bg-surface);
   --text-light: rgba(255, 255, 255, 0.9);
   --text-lighter: rgba(255, 255, 255, 0.7);
   --border-color: rgba(0, 0, 0, 0.08);
   --shadow-color: rgba(0, 0, 0, 0.2);
   --page-bg: #fff;
   --card-bg: #fff;
}

body {
   background: var(--page-bg) !important;
   transition: background 0.3s;
}
body.dark {
   --page-bg: #181c24;
   --card-bg: #232b3b;
   background: var(--page-bg) !important;
}

.main-content, .courses-section {
   background: transparent !important;
}

/* Hero Section */
.hero-section {
   position: relative;
   padding: var(--spacing-xxl) 0;
   background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
   overflow: hidden;
   margin-bottom: 4rem;
}

.hero-content {
   position: relative;
   z-index: 2;
   max-width: 1200px;
   margin: 0 auto;
   text-align: center;
}

.hero-text {
   margin-bottom: 3rem;
}

.hero-text h1 {
   font-size: 4.5rem;
   color: #fff;
   margin-bottom: 1.5rem;
   font-weight: 700;
   line-height: 1.2;
}

.hero-text p {
   font-size: 1.5rem;
   color: var(--text-light);
   line-height: 1.6;
}

.hero-overlay {
   position: absolute;
   top: 0;
   left: 0;
   right: 0;
   bottom: 0;
   background: url('path/to/pattern.svg');
   opacity: 0.1;
   pointer-events: none;
}

/* Search Section */
.search-container {
   max-width: 800px;
   margin: 0 auto;
}

.advanced-search {
   background: rgba(255, 255, 255, 0.1);
   backdrop-filter: blur(10px);
   border-radius: 1rem;
   padding: 1.5rem;
   border: 1px solid rgba(255, 255, 255, 0.2);
}

.search-wrapper {
   display: flex;
   gap: 1rem;
}

.search-wrapper input {
   flex: 1;
   padding: 1.25rem 1.5rem;
   border: none;
   background: rgba(255, 255, 255, 0.1);
   color: #fff;
   border-radius: 0.75rem;
   font-size: 1.25rem;
}

.search-wrapper input::placeholder {
   color: rgba(255, 255, 255, 0.6);
}

.search-wrapper button {
   padding: 1.25rem 2rem;
   background: var(--accent-color);
   color: #fff;
   border: none;
   border-radius: 0.75rem;
   font-size: 1.25rem;
   display: flex;
   align-items: center;
   gap: 0.75rem;
   cursor: pointer;
   transition: all 0.3s ease;
}

.search-wrapper button:hover {
   background: #d91a75;
   transform: translateY(-2px);
}

/* Courses Section */
.courses-section {
   max-width: 1400px;
   margin: 0 auto;
   padding: 0 2rem 4rem;
}

.courses-container {
   display: grid;
   grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
   gap: 2rem;
}

/* Course Card */
.course-card {
   background: var(--card-bg) !important;
   border-radius: 1.25rem;
   overflow: hidden;
   border: 1px solid var(--border-color);
   transition: all 0.3s ease;
}

.course-card:hover {
   transform: translateY(-5px);
   box-shadow: 0 10px 20px var(--shadow-color);
}

.card-header {
   position: relative;
   padding-top: 60%;
}

.course-thumb {
   position: absolute;
   top: 0;
   left: 0;
   width: 100%;
   height: 100%;
   object-fit: cover;
}

.card-overlay {
   position: absolute;
   top: 0;
   left: 0;
   right: 0;
   bottom: 0;
   background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
   padding: 1.5rem;
   display: flex;
   justify-content: space-between;
   align-items: flex-start;
}

.course-level {
   background: var(--accent-color);
   color: #fff;
   padding: 0.5rem 1.25rem;
   border-radius: 2rem;
   font-size: 1rem;
   font-weight: 500;
}

.card-actions {
   display: flex;
   gap: 0.75rem;
}

.action-btn {
   width: 40px;
   height: 40px;
   border-radius: 50%;
   background: rgba(255, 255, 255, 0.15);
   border: none;
   color: #fff;
   display: flex;
   align-items: center;
   justify-content: center;
   cursor: pointer;
   transition: all 0.3s ease;
   font-size: 1.1rem;
}

.action-btn:hover {
   background: var(--accent-color);
   transform: scale(1.1);
}

.card-body {
   padding: 2rem;
   background: transparent !important;
}

.card-meta {
   display: flex;
   justify-content: space-between;
   color: var(--gray-600);
   font-size: 1rem;
   margin-bottom: 1.25rem;
}

.course-title {
   font-size: 1.5rem;
   color: var(--secondary);
   margin-bottom: 1.5rem;
   line-height: 1.4;
   font-weight: 600;
}

.course-instructor {
   display: flex;
   align-items: center;
   gap: 1rem;
   padding-top: 1.25rem;
   border-top: 1px solid var(--border-color);
}

.instructor-img {
   width: 48px;
   height: 48px;
   border-radius: 50%;
   object-fit: cover;
}

.instructor-info h4 {
   color: var(--secondary);
   font-size: 1.1rem;
   margin-bottom: 0.25rem;
}

.instructor-info p {
   color: var(--gray-600);
   font-size: 0.9rem;
}

.card-footer {
   padding: 2rem;
   background: rgba(0, 0, 0, 0.03);
   border-top: 1px solid var(--border-color);
}

.btn-start {
   display: block;
   width: 100%;
   padding: 1.25rem;
   background: var(--primary-color);
   color: #fff;
   text-align: center;
   border-radius: 0.75rem;
   font-size: 1.25rem;
   font-weight: 500;
   transition: all 0.3s ease;
}

.btn-start:hover {
   background: var(--secondary-color);
   transform: translateY(-2px);
}

/* Empty State */
.no-courses {
   grid-column: 1 / -1;
   text-align: center;
   padding: 4rem 2rem;
   background: var(--card-bg) !important;
   border-radius: 1.25rem;
   border: 1px solid var(--border-color);
}

@media (prefers-color-scheme: dark) {
  body:not(.dark) {
    background: var(--background-light) !important;
  }
}

@media (max-width: 1024px) {
   .courses-container {
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
   }
}

@media (max-width: 768px) {
   .hero-section {
      padding: 4rem 1rem;
   }

   .hero-text h1 {
      font-size: 3.5rem;
   }

   .hero-text p {
      font-size: 1.25rem;
   }

   .search-wrapper {
      flex-direction: column;
   }

   .search-wrapper input,
   .search-wrapper button {
      width: 100%;
      font-size: 1.1rem;
      padding: 1rem 1.5rem;
   }

   .courses-section {
      padding: 0 1rem 3rem;
   }
}

@media (max-width: 480px) {
   .hero-text h1 {
      font-size: 2.5rem;
   }

   .courses-container {
      grid-template-columns: 1fr;
   }

   .card-body,
   .card-footer {
      padding: 1.5rem;
   }
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

<div class="main-content">
   <!-- Hero Section -->
   <section class="hero-section">
      <div class="hero-content">
         <div class="hero-text">
            <h1>Explore Our Courses</h1>
            <p>Discover a world of knowledge with our expert-led courses</p>
         </div>
         <div class="search-container">
            <form action="" method="post" class="advanced-search">
               <div class="search-wrapper">
                  <input type="text" name="search_course" placeholder="What do you want to learn today?" maxlength="100">
                  <button type="submit" name="search_course_btn">
                     <i class="fas fa-search"></i>
                     <span>Search</span>
                  </button>
               </div>
            </form>
         </div>
      </div>
      <div class="hero-overlay"></div>
   </section>

   <!-- Course Grid Section -->
   <section class="courses-section">
      <div class="courses-container">
         <?php
         if(isset($_POST['search_course']) or isset($_POST['search_course_btn'])){
            $search_course = $_POST['search_course'];
            $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE title LIKE '%{$search_course}%' AND status = ?");
            $select_courses->execute(['active']);
            if($select_courses->rowCount() > 0){
               while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
                  $course_id = $fetch_course['id'];
                  $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
                  $select_tutor->execute([$fetch_course['tutor_id']]);
                  $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
         ?>
         <div class="course-card">
            <div class="card-header">
               <img src="../IMAGES/<?= (!empty($fetch_course['thumb']) && file_exists(__DIR__.'/../IMAGES/'.$fetch_course['thumb'])) ? $fetch_course['thumb'] : 'default-thumb.png'; ?>" alt="" class="course-thumb">
               <div class="card-overlay">
                  <span class="course-level">Beginner</span>
                  <div class="card-actions">
                     <button class="action-btn" title="Add to Wishlist">
                        <i class="fas fa-heart"></i>
                     </button>
                     <button class="action-btn" title="Share Course">
                        <i class="fas fa-share"></i>
                     </button>
                  </div>
               </div>
            </div>
            <div class="card-body">
               <div class="card-meta">
                  <span class="course-category">
                     <i class="fas fa-folder"></i> Web Development
                  </span>
                  <span class="course-duration">
                     <i class="fas fa-clock"></i> <?= $fetch_course['total_videos']; ?> videos
                  </span>
               </div>
               <h3 class="course-title"><?= $fetch_course['title']; ?></h3>
               <div class="course-instructor">
                  <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="" class="instructor-img">
                  <div class="instructor-info">
                     <h4><?= $fetch_tutor['name']; ?></h4>
                     <p>Senior Instructor</p>
                  </div>
               </div>
            </div>
            <div class="card-footer">
               <a href="playlist.php?get_id=<?= $course_id; ?>" class="btn-start">Start Learning</a>
            </div>
         </div>
         <?php
               }
            }else{
               echo '<div class="no-courses">
                        <i class="fas fa-search"></i>
                        <h2>No courses found</h2>
                        <p>Try adjusting your search to find what you\'re looking for.</p>
                     </div>';
            }
         }else{
            $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ?");
            $select_courses->execute(['active']);
            if($select_courses->rowCount() > 0){
               while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
                  $course_id = $fetch_course['id'];
                  $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
                  $select_tutor->execute([$fetch_course['tutor_id']]);
                  $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
         ?>
         <div class="course-card">
            <div class="card-header">
               <img src="../IMAGES/<?= (!empty($fetch_course['thumb']) && file_exists(__DIR__.'/../IMAGES/'.$fetch_course['thumb'])) ? $fetch_course['thumb'] : 'default-thumb.png'; ?>" alt="" class="course-thumb">
               <div class="card-overlay">
                  <span class="course-level">Beginner</span>
                  <div class="card-actions">
                     <button class="action-btn" title="Add to Wishlist">
                        <i class="fas fa-heart"></i>
                     </button>
                     <button class="action-btn" title="Share Course">
                        <i class="fas fa-share"></i>
                     </button>
                  </div>
               </div>
            </div>
            <div class="card-body">
               <div class="card-meta">
                  <span class="course-category">
                     <i class="fas fa-folder"></i> Web Development
                  </span>
                  <span class="course-duration">
                     <i class="fas fa-clock"></i> <?= $fetch_course['total_videos']; ?> videos
                  </span>
               </div>
               <h3 class="course-title"><?= $fetch_course['title']; ?></h3>
               <div class="course-instructor">
                  <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="" class="instructor-img">
                  <div class="instructor-info">
                     <h4><?= $fetch_tutor['name']; ?></h4>
                     <p>Senior Instructor</p>
                  </div>
               </div>
            </div>
            <div class="card-footer">
               <a href="playlist.php?get_id=<?= $course_id; ?>" class="btn-start">Start Learning</a>
            </div>
         </div>
         <?php
               }
            }else{
               echo '<div class="no-courses">
                        <i class="fas fa-graduation-cap"></i>
                        <h2>No courses available yet</h2>
                        <p>Check back later for new courses.</p>
                     </div>';
            }
         }
         ?>
      </div>
   </section>
</div>

<script src="../FRONT END/js/modern.js"></script>
</body>
</html>