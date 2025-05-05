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

// --- Mark video as watched automatically ---
if($user_id && $get_id){
   $stmt = $conn->prepare("INSERT INTO user_videos (user_id, content_id, watched, watched_at)
       VALUES (?, ?, 1, NOW())
       ON DUPLICATE KEY UPDATE watched = 1, watched_at = NOW()");
   $stmt->execute([$user_id, $get_id]);
}

if(isset($_POST['like_content'])){
   if($user_id != ''){
      $content_id = $_POST['content_id'];
      $content_id = filter_var($content_id, FILTER_SANITIZE_STRING);
      $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $select_content->execute([$content_id]);
      $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);
      $tutor_id = $fetch_content['tutor_id'];
      $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND content_id = ?");
      $select_likes->execute([$user_id, $content_id]);
      if($select_likes->rowCount() > 0){
         $remove_likes = $conn->prepare("DELETE FROM `likes` WHERE user_id = ? AND content_id = ?");
         $remove_likes->execute([$user_id, $content_id]);
         $message[] = 'removed from likes!';
      }else{
         $insert_likes = $conn->prepare("INSERT INTO `likes`(user_id, tutor_id, content_id) VALUES(?,?,?)");
         $insert_likes->execute([$user_id, $tutor_id, $content_id]);
         $message[] = 'added to likes!';
      }
   }else{
      $message[] = 'please login first!';
   }
}

if(isset($_POST['add_comment'])){
   if($user_id != ''){
      $id = unique_id();
      $comment_box = $_POST['comment_box'];
      $comment_box = filter_var($comment_box, FILTER_SANITIZE_STRING);
      $content_id = $_POST['content_id'];
      $content_id = filter_var($content_id, FILTER_SANITIZE_STRING);
      $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $select_content->execute([$content_id]);
      $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);
      $tutor_id = $fetch_content['tutor_id'];
      if($select_content->rowCount() > 0){
         $select_comment = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ? AND user_id = ? AND tutor_id = ? AND comment = ?");
         $select_comment->execute([$content_id, $user_id, $tutor_id, $comment_box]);
         if($select_comment->rowCount() > 0){
            $message[] = 'comment already added!';
         }else{
            $insert_comment = $conn->prepare("INSERT INTO `comments`(id, content_id, user_id, tutor_id, comment) VALUES(?,?,?,?,?)");
            $insert_comment->execute([$id, $content_id, $user_id, $tutor_id, $comment_box]);
            $message[] = 'new comment added!';
         }
      }else{
         $message[] = 'something went wrong!';
      }
   }else{
      $message[] = 'please login first!';
   }
}

if(isset($_POST['delete_comment'])){
   $delete_id = $_POST['comment_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ?");
   $verify_comment->execute([$delete_id]);
   if($verify_comment->rowCount() > 0){
      $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
      $delete_comment->execute([$delete_id]);
      $message[] = 'comment deleted successfully!';
   }else{
      $message[] = 'comment already deleted!';
   }
}

if(isset($_POST['update_now'])){
   $update_id = $_POST['update_id'];
   $update_id = filter_var($update_id, FILTER_SANITIZE_STRING);
   $update_box = $_POST['update_box'];
   $update_box = filter_var($update_box, FILTER_SANITIZE_STRING);
   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ? AND comment = ?");
   $verify_comment->execute([$update_id, $update_box]);
   if($verify_comment->rowCount() > 0){
      $message[] = 'comment already added!';
   }else{
      $update_comment = $conn->prepare("UPDATE `comments` SET comment = ? WHERE id = ?");
      $update_comment->execute([$update_box, $update_id]);
      $message[] = 'comment edited successfully!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Watch Video</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      body {
         background: #10131a;
         font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
         margin: 0;
         min-height: 100vh;
         padding-left: 0 !important;
      }
      .main-card {
         max-width: 900px;
         margin: 40px auto 0 auto;
         background: #181c24;
         border-radius: 2rem;
         box-shadow: 0 8px 32px rgba(67,97,238,0.13);
         overflow: hidden;
         animation: fadeInUp 0.8s cubic-bezier(.23,1.01,.32,1) both;
         transition: box-shadow 0.3s;
      }
      @keyframes fadeInUp {
         0% { opacity: 0; transform: translateY(40px); }
         100% { opacity: 1; transform: none; }
      }
      .main-card:hover {
         box-shadow: 0 16px 48px rgba(67,97,238,0.18);
      }
      .video-section {
         background: #000;
         position: relative;
         aspect-ratio: 16/9;
         width: 100%;
         overflow: hidden;
         border-top-left-radius: 2rem;
         border-top-right-radius: 2rem;
         box-shadow: 0 4px 24px rgba(67,97,238,0.10);
         animation: fadeIn 1s;
      }
      @keyframes fadeIn {
         from { opacity: 0; }
         to { opacity: 1; }
      }
      .video-section iframe, .video-section video {
         width: 100%;
         height: 100%;
         border: none;
         border-radius: 0 0 0 0;
         display: block;
         background: #000;
      }
      .info-section {
         padding: 2.5rem 2.5rem 2rem 2.5rem;
         color: #fff;
         animation: fadeIn 1.2s;
      }
      .video-title {
         font-size: 2.2rem;
         font-weight: 700;
         margin-bottom: 0.7rem;
         letter-spacing: 0.01em;
         color: #fff;
         transition: color 0.3s;
      }
      .video-meta {
         display: flex;
         gap: 2rem;
         color: #8fa2c7;
         font-size: 1.1rem;
         align-items: center;
         margin-bottom: 1.2rem;
         opacity: 0.85;
      }
      .video-meta i {
         margin-right: 0.5rem;
         color: #4895ef;
         transition: color 0.3s;
      }
      .tutor-row {
         display: flex;
         align-items: center;
         gap: 1.2rem;
         margin-bottom: 1.2rem;
      }
      .tutor-img {
         width: 56px;
         height: 56px;
         border-radius: 50%;
         object-fit: cover;
         border: 2px solid #4361ee;
         background: #fff;
         transition: box-shadow 0.3s;
      }
      .tutor-details h3 {
         font-size: 1.1rem;
         color: #fff;
         margin: 0;
      }
      .tutor-details span {
         color: #4895ef;
         font-size: 1rem;
      }
      .actions-row {
         display: flex;
         gap: 1rem;
         align-items: center;
         margin-bottom: 1.2rem;
      }
      .like-btn {
         background: #232b3b;
         color: #4895ef;
         border: none;
         border-radius: 2rem;
         padding: 0.7rem 1.6rem;
         font-size: 1.1rem;
         font-weight: 600;
         display: flex;
         align-items: center;
         gap: 0.5rem;
         cursor: pointer;
         transition: background 0.2s, color 0.2s, transform 0.2s;
         box-shadow: 0 2px 8px #4361ee22;
         position: relative;
         overflow: hidden;
      }
      .like-btn.liked {
         background: #4895ef;
         color: #fff;
         animation: pop 0.3s;
      }
      @keyframes pop {
         0% { transform: scale(1); }
         50% { transform: scale(1.2); }
         100% { transform: scale(1); }
      }
      .like-btn:hover {
         background: #2336a7;
         color: #fff;
         transform: translateY(-2px) scale(1.04);
         box-shadow: 0 4px 16px #2336a722;
      }
      .video-description {
         background: #232b3b;
         border-radius: 1rem;
         padding: 1.2rem 1.5rem;
         color: #e0e6f0;
         font-size: 1.1rem;
         margin-top: 0.5rem;
         box-shadow: 0 2px 8px #222b3a11;
         animation: fadeIn 1.3s;
      }
      .comments-section {
         background: transparent;
         border-radius: 1.2rem;
         margin: 2.5rem auto 0 auto;
         max-width: 900px;
         box-shadow: none;
         padding-bottom: 0;
      }
      .add-comment-form {
         display: flex;
         flex-direction: column;
         gap: 1.2rem;
         background: #181c24;
         border-radius: 1rem;
         padding: 2rem;
         box-shadow: 0 2px 8px rgba(67,97,238,0.05);
         margin-bottom: 2.5rem;
         animation: fadeInUp 1.1s;
      }
      .add-comment-form textarea {
         border-radius: 0.7rem;
         border: none;
         padding: 1.2rem;
         font-size: 1.1rem;
         background: #232b3b;
         color: #fff;
         resize: vertical;
         min-height: 80px;
         box-shadow: 0 1px 4px #222b3a11;
         transition: box-shadow 0.2s;
      }
      .add-comment-form textarea:focus {
         outline: 2px solid #4895ef;
         background: #232b3b;
         box-shadow: 0 2px 8px #4895ef33;
      }
      .add-comment-form .btn {
         background: #4895ef;
         color: #fff;
         border-radius: 0.7rem;
         font-size: 1.1rem;
         font-weight: 600;
         padding: 1.1rem 0;
         border: none;
         transition: background 0.2s;
         box-shadow: 0 2px 8px #4361ee22;
      }
      .add-comment-form .btn:hover {
         background: #2336a7;
      }
      .comments-list {
         display: flex;
         flex-direction: column;
         gap: 1.5rem;
         margin-top: 1.5rem;
      }
      .comment-card {
         display: flex;
         align-items: flex-start;
         gap: 1.2rem;
         background: #181c24;
         border-radius: 1rem;
         padding: 1.5rem 2rem;
         box-shadow: 0 2px 8px #222b3a11;
         opacity: 0;
         animation: fadeInUp 0.7s forwards;
      }
      .comment-card:nth-child(n) { animation-delay: 0.1s; }
      .comment-card:nth-child(2) { animation-delay: 0.2s; }
      .comment-card:nth-child(3) { animation-delay: 0.3s; }
      .comment-card:nth-child(4) { animation-delay: 0.4s; }
      .comment-card:nth-child(5) { animation-delay: 0.5s; }
      .comment-avatar {
         width: 48px;
         height: 48px;
         border-radius: 50%;
         object-fit: cover;
         border: 2px solid #4895ef;
      }
      .comment-content {
         flex: 1;
         display: flex;
         flex-direction: column;
         gap: 0.3rem;
      }
      .comment-content h3 {
         font-size: 1.1rem;
         color: #fff;
         margin: 0;
      }
      .comment-content span {
         color: #8fa2c7;
         font-size: 0.95rem;
      }
      .comment-content p {
         color: #e0e6f0;
         font-size: 1.08rem;
         margin: 0.2rem 0 0.5rem 0;
         word-break: break-word;
      }
      .comment-actions {
         display: flex;
         gap: 0.7rem;
         margin-top: 0.3rem;
      }
      .comment-actions .btn {
         background: #4895ef;
         color: #fff;
         border-radius: 0.5rem;
         font-size: 0.95rem;
         padding: 0.5rem 1.2rem;
         border: none;
         transition: background 0.2s;
      }
      .comment-actions .btn:hover {
         background: #2336a7;
      }
      .empty {
         background: #181c24;
         color: #ff6b6b;
         border-radius: 1rem;
         padding: 1.5rem;
         text-align: center;
         font-size: 1.15rem;
         margin-top: 1.5rem;
         box-shadow: 0 2px 8px #222b3a11;
      }
      @media (max-width: 900px) {
         .main-card { margin: 20px 0; border-radius: 1rem; }
         .info-section { padding: 1.2rem; }
      }
      @media (max-width: 600px) {
         .main-card { border-radius: 0.5rem; }
         .video-title { font-size: 1.2rem; }
         .info-section { padding: 0.5rem; }
         .tutor-img { width: 40px; height: 40px; }
         .video-section { border-radius: 0.5rem 0.5rem 0 0; }
      }
      footer, .footer {
         background: none !important;
         padding: 0 !important;
         box-shadow: none !important;
      }
   </style>
</head>
<body>
<?php include 'components/user_header.php'; ?>
<?php
   if(isset($_POST['edit_comment'])){
      $edit_id = $_POST['comment_id'];
      $edit_id = filter_var($edit_id, FILTER_SANITIZE_STRING);
      $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ? LIMIT 1");
      $verify_comment->execute([$edit_id]);
      if($verify_comment->rowCount() > 0){
         $fetch_edit_comment = $verify_comment->fetch(PDO::FETCH_ASSOC);
?>
<section class="edit-comment">
   <h1 class="heading">Edit Comment</h1>
   <form action="" method="post">
      <input type="hidden" name="update_id" value="<?= $fetch_edit_comment['id']; ?>">
      <textarea name="update_box" class="box" maxlength="1000" required placeholder="Please enter your comment" cols="30" rows="10"><?= $fetch_edit_comment['comment']; ?></textarea>
      <div class="flex">
         <a href="watch_video.php?get_id=<?= $get_id; ?>" class="inline-option-btn">Cancel Edit</a>
         <input type="submit" value="Update Now" name="update_now" class="inline-btn">
      </div>
   </form>
</section>
<?php
   }else{
      $message[] = 'comment was not found!';
   }
}
?>
<div class="main-card">
   <?php
      $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? AND status = ?");
      $select_content->execute([$get_id, 'active']);
      if($select_content->rowCount() > 0){
         while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){
            $content_id = $fetch_content['id'];
            $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE content_id = ?");
            $select_likes->execute([$content_id]);
            $total_likes = $select_likes->rowCount();  
            $verify_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND content_id = ?");
            $verify_likes->execute([$user_id, $content_id]);
            $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ? LIMIT 1");
            $select_tutor->execute([$fetch_content['tutor_id']]);
            $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
            $video_url = $fetch_content['video'];
   ?>
   <div class="video-section">
      <?php
         if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
            if (preg_match('/(?:youtube\\.com\/watch\\?v=|youtu\\.be\/)([\\w-]+)/', $video_url, $matches)) {
               $youtube_id = $matches[1];
               $embed_url = "https://www.youtube.com/embed/$youtube_id";
               echo '<iframe src="'.$embed_url.'" frameborder="0" allowfullscreen></iframe>';
            }
         } else {
            echo '<video controls><source src="../IMAGES/'.$video_url.'" type="video/mp4">Your browser does not support the video tag.</video>';
         }
      ?>
   </div>
   <div class="info-section">
      <div class="video-title"><?= $fetch_content['title']; ?></div>
      <div class="video-meta">
         <span><i class="fas fa-calendar"></i> <?= $fetch_content['date']; ?></span>
         <span><i class="fas fa-heart"></i> <?= $total_likes; ?> likes</span>
      </div>
      <div class="tutor-row">
         <img src="../IMAGES/<?= $fetch_tutor['image']; ?>" alt="" class="tutor-img">
         <div class="tutor-details">
            <h3><?= $fetch_tutor['name']; ?></h3>
            <span><?= $fetch_tutor['profession']; ?></span>
         </div>
      </div>
      <div class="actions-row">
         <form action="" method="post" style="display:inline;">
            <input type="hidden" name="content_id" value="<?= $content_id; ?>">
            <button type="submit" name="like_content" class="like-btn<?php if($verify_likes->rowCount() > 0) echo ' liked'; ?>">
               <i class="<?php if($verify_likes->rowCount() > 0){ echo 'fas fa-heart'; } else { echo 'far fa-heart'; } ?>"></i>
               <?php if($verify_likes->rowCount() > 0){ echo 'Liked'; } else { echo 'Like'; } ?>
            </button>
         </form>
      </div>
      <div class="video-description"><p><?= $fetch_content['description']; ?></p></div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no videos added yet!</p>';
      }
   ?>
</div>
<div class="comments-section">
   <h2 class="heading" style="color:#fff;">Comments</h2>
   <?php if($user_id != ''){ ?>
   <form action="" method="post" class="add-comment-form">
      <input type="hidden" name="content_id" value="<?= $get_id; ?>">
      <textarea name="comment_box" placeholder="Add a comment..." required maxlength="1000" cols="30" rows="4"></textarea>
      <button type="submit" name="add_comment" class="btn">Post Comment</button>
   </form>
   <?php } ?>
   <div class="comments-list">
      <?php
         $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ? ORDER BY date DESC");
         $select_comments->execute([$get_id]);
         if($select_comments->rowCount() > 0){
            $i = 0;
            while($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)){
               $select_commentor = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
               $select_commentor->execute([$fetch_comment['user_id']]);
               $fetch_commentor = $select_commentor->fetch(PDO::FETCH_ASSOC);
               $i++;
      ?>
      <div class="comment-card" style="animation-delay: <?= ($i * 0.1) ?>s;">
         <img src="../IMAGES/<?= $fetch_commentor['image']; ?>" alt="" class="comment-avatar">
         <div class="comment-content">
            <h3><?= $fetch_commentor['name']; ?></h3>
            <span><?= $fetch_comment['date']; ?></span>
            <p><?= $fetch_comment['comment']; ?></p>
            <?php if($fetch_comment['user_id'] == $user_id){ ?>
            <div class="comment-actions">
               <form action="" method="post" style="display:inline;">
                  <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>">
                  <button type="submit" name="edit_comment" class="btn">Edit</button>
                  <button type="submit" name="delete_comment" class="btn" onclick="return confirm('Delete this comment?');">Delete</button>
               </form>
            </div>
            <?php } ?>
         </div>
      </div>
      <?php
            }
         }else{
            echo '<p class="empty">no comments added yet!</p>';
         }
      ?>
   </div>
</div>
<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>