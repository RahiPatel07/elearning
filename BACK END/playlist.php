<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:home.php');
}

// --- Mark playlist as completed if all videos are watched ---
if($user_id && $get_id){
   // Get all content_ids for this playlist
   $stmt = $conn->prepare("SELECT id FROM content WHERE playlist_id = ?");
   $stmt->execute([$get_id]);
   $all_content_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
   if(count($all_content_ids) > 0){
      // Get watched content_ids for this user and playlist
      $in = str_repeat('?,', count($all_content_ids) - 1) . '?';
      $params = array_merge([$user_id], $all_content_ids);
      $stmt = $conn->prepare("SELECT content_id FROM user_videos WHERE user_id = ? AND watched = 1 AND content_id IN ($in)");
      $stmt->execute($params);
      $watched_content_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
      if(count($all_content_ids) == count($watched_content_ids)){
         // Mark course as completed
         $stmt = $conn->prepare("INSERT INTO user_courses (user_id, playlist_id, completed, completed_at)
             VALUES (?, ?, 1, NOW())
             ON DUPLICATE KEY UPDATE completed = 1, completed_at = NOW()");
         $stmt->execute([$user_id, $get_id]);
      }
   }
}

if(isset($_POST['save_list'])){

   if($user_id != ''){
      
      $list_id = $_POST['list_id'];
      $list_id = filter_var($list_id, FILTER_SANITIZE_STRING);

      $select_list = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
      $select_list->execute([$user_id, $list_id]);

      if($select_list->rowCount() > 0){
         $remove_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
         $remove_bookmark->execute([$user_id, $list_id]);
         $message[] = 'playlist removed!';
      }else{
         $insert_bookmark = $conn->prepare("INSERT INTO `bookmark`(user_id, playlist_id) VALUES(?,?)");
         $insert_bookmark->execute([$user_id, $list_id]);
         $message[] = 'playlist saved!';
      }

   }else{
      $message[] = 'please login first!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Playlist - E-Learning Platform</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="../FRONT END/css/modern.css">
   <style>
      .playlist-section {
         background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
         padding: var(--spacing-xxl) 0;
         color: var(--white);
         margin-bottom: var(--spacing-xl);
      }

      .playlist-content {
         max-width: 1200px;
         margin: 0 auto;
         padding: 0 var(--spacing-md);
      }

      .playlist-header {
         display: grid;
         grid-template-columns: 1fr 2fr;
         gap: var(--spacing-xl);
         align-items: start;
      }

      .playlist-thumb {
         position: relative;
         border-radius: var(--radius-lg);
         overflow: hidden;
         box-shadow: var(--shadow-lg);
      }

      .playlist-thumb img {
         width: 100%;
         height: 300px;
         object-fit: cover;
      }

      .video-count {
         position: absolute;
         top: var(--spacing-md);
         right: var(--spacing-md);
         background: var(--primary);
         color: var(--white);
         padding: var(--spacing-sm) var(--spacing-md);
         border-radius: var(--radius-md);
         font-weight: 600;
      }

      .playlist-info {
         color: var(--white);
      }

      .tutor-info {
         display: flex;
         align-items: center;
         margin-bottom: var(--spacing-lg);
      }

      .tutor-img {
         width: 60px;
         height: 60px;
         border-radius: var(--radius-full);
         margin-right: var(--spacing-md);
         border: 3px solid var(--white);
      }

      .tutor-details h3 {
         font-size: 1.8rem;
         margin: 0;
      }

      .tutor-details span {
         font-size: 1.4rem;
         opacity: 0.9;
      }

      .playlist-title {
         font-size: 2.4rem;
         margin-bottom: var(--spacing-md);
      }

      .playlist-description {
         font-size: 1.6rem;
         line-height: 1.6;
         margin-bottom: var(--spacing-lg);
         opacity: 0.9;
      }

      .playlist-date {
         display: flex;
         align-items: center;
         font-size: 1.4rem;
         opacity: 0.8;
      }

      .playlist-date i {
         margin-right: var(--spacing-sm);
      }

      .save-list {
         position: absolute;
         top: var(--spacing-md);
         left: var(--spacing-md);
      }

      .save-list button {
         background: var(--white);
         color: var(--primary);
         border: none;
         padding: var(--spacing-sm) var(--spacing-md);
         border-radius: var(--radius-md);
         font-weight: 600;
         cursor: pointer;
         transition: all var(--transition-medium);
      }

      .save-list button:hover {
         transform: translateY(-2px);
         box-shadow: var(--shadow-md);
      }

      .videos-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
         gap: var(--spacing-lg);
         padding: var(--spacing-lg);
      }

      .video-card {
         background: var(--white);
         border-radius: var(--radius-lg);
         overflow: hidden;
         transition: transform var(--transition-medium);
         box-shadow: var(--shadow-md);
      }

      .video-card:hover {
         transform: translateY(-5px);
      }

      .video-thumb {
         position: relative;
         width: 100%;
         height: 200px;
      }

      .video-thumb img {
         width: 100%;
         height: 100%;
         object-fit: cover;
      }

      .play-icon {
         position: absolute;
         top: 50%;
         left: 50%;
         transform: translate(-50%, -50%);
         background: var(--primary);
         color: var(--white);
         width: 50px;
         height: 50px;
         border-radius: var(--radius-full);
         display: flex;
         align-items: center;
         justify-content: center;
         font-size: 2rem;
      }

      .video-title {
         padding: var(--spacing-md);
         font-size: 1.6rem;
         color: var(--secondary);
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

<!-- Playlist Section -->
<section class="playlist-section">
   <div class="playlist-content">
      <?php
         $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? and status = ? LIMIT 1");
         $select_playlist->execute([$get_id, 'active']);
         if($select_playlist->rowCount() > 0){
            $fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC);

            $playlist_id = $fetch_playlist['id'];

            $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
            $count_videos->execute([$playlist_id]);
            $total_videos = $count_videos->rowCount();

            $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ? LIMIT 1");
            $select_tutor->execute([$fetch_playlist['tutor_id']]);
            $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);

            $select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
            $select_bookmark->execute([$user_id, $playlist_id]);
      ?>
      <div class="playlist-header">
         <div class="playlist-thumb">
            <form action="" method="post" class="save-list">
               <input type="hidden" name="list_id" value="<?= $playlist_id; ?>">
               <?php if($select_bookmark->rowCount() > 0): ?>
                  <button type="submit" name="save_list"><i class="fas fa-bookmark"></i> Saved</button>
               <?php else: ?>
                  <button type="submit" name="save_list"><i class="far fa-bookmark"></i> Save Playlist</button>
               <?php endif; ?>
            </form>
            <span class="video-count"><?= $total_videos; ?> videos</span>
            <img src="../IMAGES/<?= (!empty($fetch_playlist['thumb']) && file_exists(__DIR__.'/../IMAGES/'.$fetch_playlist['thumb'])) ? $fetch_playlist['thumb'] : 'no-image.png'; ?>" alt="Playlist Thumbnail">
         </div>
         <div class="playlist-info">
            <div class="tutor-info">
               <img src="../IMAGES/<?= $fetch_tutor['image']; ?>" class="tutor-img" alt="">
               <div class="tutor-details">
                  <h3><?= $fetch_tutor['name']; ?></h3>
                  <span><?= $fetch_tutor['profession']; ?></span>
               </div>
            </div>
            <h1 class="playlist-title"><?= $fetch_playlist['title']; ?></h1>
            <p class="playlist-description"><?= $fetch_playlist['description']; ?></p>
            <div class="playlist-date">
               <i class="fas fa-calendar"></i>
               <span><?= $fetch_playlist['date']; ?></span>
            </div>
         </div>
      </div>
      <?php
         }else{
            echo '<p class="empty">This playlist was not found!</p>';
         }  
      ?>
   </div>
</section>

<!-- Videos Section -->
<section class="container">
   <h2 class="heading">Playlist Videos</h2>
   <div class="videos-grid">
      <?php
         $select_content = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ? AND status = ? ORDER BY date DESC");
         $select_content->execute([$get_id, 'active']);
         if($select_content->rowCount() > 0){
            while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){  
      ?>
      <a href="watch_video.php?get_id=<?= $fetch_content['id']; ?>" class="video-card">
         <div class="video-thumb">
            <img src="../IMAGES/<?= (!empty($fetch_content['thumb']) && file_exists(__DIR__.'/../IMAGES/'.$fetch_content['thumb'])) ? $fetch_content['thumb'] : 'no-image.png'; ?>" alt="Video Thumbnail">
            <div class="play-icon">
               <i class="fas fa-play"></i>
            </div>
         </div>
         <h3 class="video-title"><?= $fetch_content['title']; ?></h3>
      </a>
      <?php
            }
         }else{
            echo '<p class="empty">No videos added yet!</p>';
         }
      ?>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>