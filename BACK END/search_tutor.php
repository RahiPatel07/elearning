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
   <title>Search Tutors - E-Learning Platform</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="../FRONT END/css/modern.css">
   <style>
      .tutors-section {
         background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
         padding: var(--spacing-xxl) 0;
         color: var(--white);
         margin-bottom: var(--spacing-xl);
      }

      .tutors-content {
         max-width: 1200px;
         margin: 0 auto;
         padding: 0 var(--spacing-md);
      }

      .tutors-header {
         text-align: center;
         margin-bottom: var(--spacing-xl);
      }

      .tutors-title {
         font-size: 3.2rem;
         color: var(--white);
         margin-bottom: var(--spacing-md);
      }

      .search-form {
         max-width: 600px;
         margin: 0 auto var(--spacing-xl);
         position: relative;
      }

      .search-input {
         width: 100%;
         padding: var(--spacing-md) var(--spacing-lg);
         padding-right: 5rem;
         border: none;
         border-radius: var(--radius-lg);
         font-size: 1.6rem;
         background: var(--white);
         box-shadow: var(--shadow-md);
      }

      .search-input:focus {
         outline: none;
         box-shadow: var(--shadow-lg);
      }

      .search-btn {
         position: absolute;
         right: var(--spacing-md);
         top: 50%;
         transform: translateY(-50%);
         background: none;
         border: none;
         color: var(--primary);
         font-size: 1.8rem;
         cursor: pointer;
         transition: all var(--transition-medium);
      }

      .search-btn:hover {
         color: var(--primary-dark);
      }

      .tutors-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
         gap: var(--spacing-lg);
      }

      .tutor-card {
         background: var(--white);
         border-radius: var(--radius-lg);
         overflow: hidden;
         box-shadow: var(--shadow-md);
         transition: transform var(--transition-medium);
      }

      .tutor-card:hover {
         transform: translateY(-5px);
      }

      .tutor-header {
         padding: var(--spacing-md);
         display: flex;
         align-items: center;
         gap: var(--spacing-md);
      }

      .tutor-img {
         width: 80px;
         height: 80px;
         border-radius: var(--radius-full);
         object-fit: cover;
         border: 3px solid var(--primary);
      }

      .tutor-info h3 {
         font-size: 1.8rem;
         color: var(--secondary);
         margin: 0 0 var(--spacing-xs);
      }

      .tutor-info span {
         font-size: 1.4rem;
         color: var(--gray-600);
      }

      .tutor-stats {
         padding: var(--spacing-md);
         border-top: 1px solid var(--gray-200);
      }

      .stat-item {
         display: flex;
         justify-content: space-between;
         padding: var(--spacing-sm) 0;
         font-size: 1.4rem;
      }

      .stat-item span {
         color: var(--primary);
         font-weight: 600;
      }

      .view-profile-btn {
         display: block;
         width: 100%;
         padding: var(--spacing-md);
         background: var(--primary);
         color: var(--white);
         text-align: center;
         text-decoration: none;
         font-size: 1.4rem;
         font-weight: 600;
         transition: all var(--transition-medium);
      }

      .view-profile-btn:hover {
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

<!-- Tutors Section -->
<section class="tutors-section">
   <div class="tutors-content">
      <div class="tutors-header">
         <h1 class="tutors-title">Expert Tutors</h1>
      </div>
      <form action="" method="post" class="search-form">
         <input type="text" name="search_tutor" maxlength="100" placeholder="Search tutor..." required class="search-input">
         <button type="submit" name="search_tutor_btn" class="search-btn">
            <i class="fas fa-search"></i>
         </button>
      </form>
      <div class="tutors-grid">
         <?php
            if(isset($_POST['search_tutor']) or isset($_POST['search_tutor_btn'])){
               $search_tutor = $_POST['search_tutor'];
               $select_tutors = $conn->prepare("SELECT * FROM `tutors` WHERE name LIKE '%{$search_tutor}%'");
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
         <div class="tutor-card">
            <div class="tutor-header">
               <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" class="tutor-img" alt="">
               <div class="tutor-info">
                  <h3><?= $fetch_tutor['name']; ?></h3>
                  <span><?= $fetch_tutor['profession']; ?></span>
               </div>
            </div>
            <div class="tutor-stats">
               <div class="stat-item">
                  <span>Playlists</span>
                  <span><?= $total_playlists; ?></span>
               </div>
               <div class="stat-item">
                  <span>Total Videos</span>
                  <span><?= $total_contents ?></span>
               </div>
               <div class="stat-item">
                  <span>Total Likes</span>
                  <span><?= $total_likes ?></span>
               </div>
               <div class="stat-item">
                  <span>Total Comments</span>
                  <span><?= $total_comments ?></span>
               </div>
            </div>
            <form action="tutor_profile.php" method="post">
               <input type="hidden" name="tutor_email" value="<?= $fetch_tutor['email']; ?>">
               <button type="submit" name="tutor_fetch" class="view-profile-btn">View Profile</button>
            </form>
         </div>
         <?php
                  }
               }else{
                  echo '<p class="empty">No results found!</p>';
               }
            }else{
               echo '<p class="empty">Please search something!</p>';
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