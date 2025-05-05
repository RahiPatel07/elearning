<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home - E-Learning Platform</title>

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

      .hero-content {
         max-width: 800px;
         margin: 0 auto;
         padding: 0 var(--spacing-md);
      }

      .hero-title {
         font-size: 4.8rem;
         color: var(--white);
         margin-bottom: var(--spacing-md);
      }

      .hero-subtitle {
         font-size: 2rem;
         opacity: 0.9;
         margin-bottom: var(--spacing-lg);
      }

      .stats-container {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
         gap: var(--spacing-md);
         padding: var(--spacing-lg);
      }

      .stat-card {
         background: #fff !important;
         color: #222 !important;
         border-radius: var(--radius-lg);
         padding: var(--spacing-lg);
         text-align: center;
         box-shadow: none;
         border: none;
         transition: transform var(--transition-medium);
      }

      .stat-card:hover {
         transform: translateY(-5px);
      }

      .stat-icon {
         font-size: 3rem;
         color: var(--primary);
         margin-bottom: var(--spacing-sm);
      }

      .stat-number, .stat-label {
         color: #222 !important;
      }

      .stat-number {
         font-size: 2.4rem;
         font-weight: 600;
         margin-bottom: var(--spacing-xs);
      }

      .stat-label {
         font-size: 1.4rem;
      }

      .categories-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
         gap: 2rem;
         padding: 2rem 0;
      }

      .category-card {
         background: #fff;
         border-radius: 1.5rem;
         padding: 2rem;
         text-align: center;
         transition: all 0.3s ease;
         cursor: pointer;
         border: 2px solid transparent;
      }

      .category-card:hover, .category-card.active {
         transform: translateY(-5px);
         box-shadow: 0 10px 20px rgba(0,0,0,0.1);
         border-color: #4361ee;
      }

      .category-icon {
         font-size: 2.4rem;
         color: #4361ee;
         margin-bottom: 1rem;
      }

      .category-title {
         font-size: 1.6rem;
         color: #2d3748;
         margin: 0;
      }

      .category-content {
         opacity: 0;
         transform: translateY(20px);
         transition: all 0.3s ease;
      }

      .category-content.visible {
         opacity: 1;
         transform: translateY(0);
      }

      .playlists-container {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
         gap: 2rem;
         padding: 2rem 0;
      }

      .playlist-card {
         display: flex;
         align-items: stretch;
         background: #fff;
         border-radius: 1rem;
         overflow: hidden;
         box-shadow: 0 4px 16px rgba(0,0,0,0.08);
         transition: box-shadow 0.3s, transform 0.3s;
         min-height: 180px;
         position: relative;
      }

      .playlist-card:hover {
         transform: translateY(-6px) scale(1.01);
         box-shadow: 0 10px 32px rgba(67,97,238,0.10);
      }

      .playlist-thumb {
         width: 140px;
         height: 100%;
         object-fit: cover;
         border-top-left-radius: 1rem;
         border-bottom-left-radius: 1rem;
         background: #f1f3fa;
         flex-shrink: 0;
         display: block;
      }

      .playlist-content {
         flex: 1;
         display: flex;
         flex-direction: column;
         justify-content: center;
         padding: 2rem 2.5rem 2rem 2rem;
      }

      .playlist-title {
         font-size: 1.5rem;
         color: #222b45;
         font-weight: 700;
         margin-bottom: 0.5rem;
      }

      .playlist-description {
         font-size: 1.08rem;
         color: #718096;
         margin-bottom: 1.2rem;
      }

      .playlist-meta {
         display: flex;
         justify-content: space-between;
         align-items: flex-end;
         margin-top: auto;
      }

      .playlist-meta span {
         color: #a0aec0;
         font-size: 1rem;
         display: flex;
         align-items: center;
         gap: 0.4rem;
      }

      .btn-view-course {
         background: #4361ee;
         color: #fff;
         border: none;
         border-radius: 2rem;
         padding: 0.7rem 1.6rem;
         font-size: 1rem;
         font-weight: 600;
         text-decoration: none;
         transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
         box-shadow: 0 2px 8px #4361ee22;
         display: inline-block;
         margin-left: 1rem;
      }
      .btn-view-course:hover {
         background: #2336a7;
         transform: translateY(-2px) scale(1.04);
         box-shadow: 0 4px 16px #2336a722;
         color: #fff;
      }

      .no-content {
         text-align: center;
         padding: 3rem;
         background: #f8fafc;
         border-radius: 1rem;
      }

      .no-content i {
         font-size: 3rem;
         color: #a0aec0;
         margin-bottom: 1rem;
      }

      .no-content h3 {
         font-size: 1.5rem;
         color: #4a5568;
         margin-bottom: 0.5rem;
      }

      .no-content p {
         color: #718096;
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

      .view-more {
         text-align: center;
         margin-top: var(--spacing-xl);
      }

      body {
         padding-bottom: 2rem;
      }

      .container {
         background: none !important;
      }

      .heading {
         color: var(--white) !important;
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

<!-- Hero Section -->
<section class="hero-section">
   <div class="hero-content">
      <h1 class="hero-title">Welcome to E-Learning</h1>
      <p class="hero-subtitle">Discover a world of knowledge with our expert-led courses</p>
      <?php if($user_id == ''): ?>
         <div class="d-flex justify-content-center" style="gap: 1.5rem; margin-top: 2rem;">
            <a href="login.php" class="btn btn-lg hero-btn">Login</a>
            <a href="register.php" class="btn btn-lg hero-btn-outline">Register</a>
         </div>
      <?php endif; ?>
   </div>
</section>

<!-- Stats Section -->
<?php if($user_id != ''): ?>
<section class="container">
   <h2 class="heading">Your Learning Stats</h2>
   <div class="stats-container">
      <div class="stat-card">
         <i class="fas fa-heart stat-icon"></i>
         <div class="stat-number"><?= $total_likes ?></div>
         <div class="stat-label">Total Likes</div>
      </div>
      <div class="stat-card">
         <i class="fas fa-comments stat-icon"></i>
         <div class="stat-number"><?= $total_comments ?></div>
         <div class="stat-label">Total Comments</div>
      </div>
      <div class="stat-card">
         <i class="fas fa-bookmark stat-icon"></i>
         <div class="stat-number"><?= $total_bookmarked ?></div>
         <div class="stat-label">Saved Playlists</div>
      </div>
   </div>
</section>
<?php endif; ?>

<!-- Categories Section -->
<section class="container">
   <h2 class="heading">Popular Categories</h2>
   <div class="categories-grid">
      <div class="category-card" data-category="Development">
         <i class="fas fa-code category-icon"></i>
         <h3 class="category-title">Development</h3>
      </div>
      <div class="category-card" data-category="Business">
         <i class="fas fa-chart-simple category-icon"></i>
         <h3 class="category-title">Business</h3>
      </div>
      <div class="category-card" data-category="Design">
         <i class="fas fa-pen category-icon"></i>
         <h3 class="category-title">Design</h3>
      </div>
      <div class="category-card" data-category="Marketing">
         <i class="fas fa-chart-line category-icon"></i>
         <h3 class="category-title">Marketing</h3>
      </div>
      <div class="category-card" data-category="Music">
         <i class="fas fa-music category-icon"></i>
         <h3 class="category-title">Music</h3>
      </div>
      <div class="category-card" data-category="Photography">
         <i class="fas fa-camera category-icon"></i>
         <h3 class="category-title">Photography</h3>
      </div>
   </div>

   <!-- Category Content Section -->
   <div id="category-content" class="category-content" style="margin-top: 3rem;">
      <div class="playlists-container">
         <!-- Playlists will be loaded here -->
      </div>
   </div>
</section>

<!-- Latest Courses Section -->
<section class="container">
   <h2 class="heading">Latest Courses</h2>
   <div class="courses-grid">
      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC LIMIT 6");
         $select_courses->execute(['active']);
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
               <img src="IMAGES/<?php 
                  $tutor_img = $fetch_tutor['image'];
                  $default_imgs = ['pic-2.jpg','pic-3.jpg','pic-4.jpg','pic-5.jpg','pic-6.jpg','pic-7.jpg'];
                  $img_path = (!empty($tutor_img) && file_exists('IMAGES/'.$tutor_img)) ? $tutor_img : $default_imgs[array_rand($default_imgs)];
                  echo $img_path;
               ?>" class="tutor-img" alt="">
               <div class="tutor-info">
                  <h3><?= $fetch_tutor['name']; ?></h3>
                  <span><?= $fetch_course['date']; ?></span>
               </div>
            </div>
            <h3 class="course-title"><?= $fetch_course['title']; ?></h3>
            <a href="playlist.php?get_id=<?= $course_id; ?>" class="btn btn-primary btn-block">View Playlist</a>
         </div>
      </div>
      <?php
         }
      }else{
         echo '<p class="text-center">No courses added yet!</p>';
      }
      ?>
   </div>
   <div class="view-more">
      <a href="courses.php" class="btn btn-outline-primary">View All Courses</a>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="../FRONT END/js/modern.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
   const categoryCards = document.querySelectorAll('.category-card');
   const categoryContent = document.getElementById('category-content');
   const playlistsContainer = document.querySelector('.playlists-container');

   // Function to load playlists for a category
   async function loadPlaylists(category) {
      try {
         const response = await fetch(`api/get_playlists_by_category.php?category=${encodeURIComponent(category)}`);
         const playlists = await response.json();
         
         playlistsContainer.innerHTML = '';
         
         if (playlists.length === 0) {
            playlistsContainer.innerHTML = `
               <div class="no-content">
                  <i class="fas fa-folder-open"></i>
                  <h3>No Courses Found</h3>
                  <p>There are no courses available in this category yet.</p>
               </div>
            `;
         } else {
            playlists.forEach(playlist => {
               playlistsContainer.innerHTML += `
                  <div class="playlist-card">
                     <img src="uploaded_files/${playlist.thumb}" alt="${playlist.title}" class="playlist-thumb" onerror="this.src='https://via.placeholder.com/140x180?text=No+Image';">
                     <div class="playlist-content">
                        <h3 class="playlist-title">${playlist.title}</h3>
                        <p class="playlist-description">${playlist.description}</p>
                        <div class="playlist-meta">
                           <span><i class="fas fa-video"></i> ${playlist.total_videos ? playlist.total_videos : 0} videos</span>
                           <a href="playlist.php?get_id=${playlist.id}" class="btn-view-course">View Course</a>
                        </div>
                     </div>
                  </div>
               `;
            });
         }
         
         // Show the content with animation
         categoryContent.classList.add('visible');
      } catch (error) {
         console.error('Error loading playlists:', error);
         playlistsContainer.innerHTML = `
            <div class="no-content">
               <i class="fas fa-exclamation-circle"></i>
               <h3>Error</h3>
               <p>Failed to load courses. Please try again later.</p>
            </div>
         `;
      }
   }

   // Add click event listeners to category cards
   categoryCards.forEach(card => {
      card.addEventListener('click', function() {
         // Remove active class from all cards
         categoryCards.forEach(c => c.classList.remove('active'));
         // Add active class to clicked card
         this.classList.add('active');
         
         // Load playlists for the selected category
         const category = this.getAttribute('data-category');
         loadPlaylists(category);
      });
   });

   // Load Development category by default
   categoryCards[0].click();
});
</script>
   
</body>
</html>