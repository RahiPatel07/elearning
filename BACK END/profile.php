<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
}

// Fetch user profile
$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->execute([$user_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

// Fetch likes count
$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

// Fetch comments count
$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

// Fetch bookmarks count
$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

// Fetch learning progress
$select_courses = $conn->prepare("SELECT * FROM `enrolled_courses` WHERE user_id = ?");
$select_courses->execute([$user_id]);
$total_courses = $select_courses->rowCount();

$select_completed = $conn->prepare("SELECT * FROM `enrolled_courses` WHERE user_id = ? AND status = 'completed'");
$select_completed->execute([$user_id]);
$completed_courses = $select_completed->rowCount();

$progress_percentage = $total_courses > 0 ? round(($completed_courses / $total_courses) * 100) : 0;

// Fetch recent activities
$select_activities = $conn->prepare("
   SELECT 
      'course_completion' as type,
      c.title as content,
      c.created_at as time
   FROM `enrolled_courses` ec
   JOIN `courses` c ON ec.course_id = c.id
   WHERE ec.user_id = ? AND ec.status = 'completed'
   UNION ALL
   SELECT 
      'comment' as type,
      c.comment as content,
      c.date as time
   FROM `comments` c
   WHERE c.user_id = ?
   UNION ALL
   SELECT 
      'bookmark' as type,
      p.title as content,
      NULL as time
   FROM `bookmark` b
   JOIN `playlist` p ON b.playlist_id = p.id
   WHERE b.user_id = ?
   ORDER BY time DESC
   LIMIT 5
");
$select_activities->execute([$user_id, $user_id, $user_id]);
$activities = $select_activities->fetchAll(PDO::FETCH_ASSOC);

// Function to format time ago
function timeAgo($datetime) {
   $time = strtotime($datetime);
   $now = time();
   $diff = $now - $time;
   
   if ($diff < 60) {
      return "just now";
   } elseif ($diff < 3600) {
      $mins = floor($diff / 60);
      return $mins . " minute" . ($mins > 1 ? "s" : "") . " ago";
   } elseif ($diff < 86400) {
      $hours = floor($diff / 3600);
      return $hours . " hour" . ($hours > 1 ? "s" : "") . " ago";
   } elseif ($diff < 604800) {
      $days = floor($diff / 86400);
      return $days . " day" . ($days > 1 ? "s" : "") . " ago";
   } else {
      return date('M j, Y', $time);
   }
}

// Function to get activity icon
function getActivityIcon($type) {
   switch ($type) {
      case 'course_completion':
         return 'fas fa-play';
      case 'comment':
         return 'fas fa-comment';
      case 'bookmark':
         return 'fas fa-bookmark';
      default:
         return 'fas fa-circle';
   }
}

// Function to get activity text
function getActivityText($type, $content) {
   switch ($type) {
      case 'course_completion':
         return "Completed \"$content\" course";
      case 'comment':
         return "Commented on \"$content\"";
      case 'bookmark':
         return "Saved \"$content\" playlist";
      default:
         return $content;
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Profile | E-Learning Platform</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../FRONT END/css/modern.css">
   <style>
      .profile-container {
         max-width: 1200px;
         margin: 0 auto;
         padding: 2rem;
      }

      .profile-header {
         background: var(--white);
         border-radius: 2.5rem;
         padding: 3.5rem 2.5rem 3.5rem 2.5rem;
         margin-bottom: 2rem;
         box-shadow: 0 8px 32px 0 rgba(67,97,238,0.10);
         display: flex;
         align-items: center;
         gap: 2.5rem;
         position: relative;
         overflow: hidden;
      }

      .profile-avatar-container {
         display: flex;
         align-items: center;
         justify-content: center;
         min-width: 120px;
         min-height: 120px;
         margin-right: 2.5rem;
      }

      .profile-avatar {
         width: 110px;
         height: 110px;
         border-radius: 50%;
         object-fit: cover;
         border: 4px solid var(--primary);
         box-shadow: 0 4px 24px rgba(67,97,238,0.18);
         background: #fff;
         transition: transform 0.3s;
      }

      .profile-avatar:hover {
         transform: scale(1.07) rotate(-2deg);
      }

      .profile-info {
         flex: 1;
         display: flex;
         flex-direction: column;
         justify-content: center;
         gap: 1.2rem;
      }

      .profile-name {
         font-size: 2.6rem;
         font-weight: 800;
         color: var(--secondary);
         margin-bottom: 0.2rem;
         letter-spacing: 0.5px;
         display: flex;
         align-items: center;
         gap: 1rem;
      }

      .profile-role {
         font-size: 1.3rem;
         color: var(--primary-light);
         font-weight: 600;
         margin-bottom: 0.5rem;
         display: flex;
         align-items: center;
         gap: 0.7rem;
      }

      .profile-actions {
         display: flex;
         gap: 1.2rem;
         margin-bottom: 0;
      }

      .profile-btn {
         background: var(--primary);
         color: #fff !important;
         border: none;
         border-radius: 1.2rem;
         font-size: 1.2rem;
         font-weight: 700;
         padding: 0.9rem 2.2rem;
         cursor: pointer;
         box-shadow: 0 2px 8px #4361ee22;
         display: flex;
         align-items: center;
         gap: 0.7rem;
         transition: background 0.2s;
      }

      .profile-btn i {
         color: #fff !important;
         opacity: 1 !important;
      }

      .profile-btn:hover {
         background: #2336a7;
         color: #fff !important;
         box-shadow: 0 4px 16px #2336a722;
      }

      .profile-stats {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
         gap: 2rem;
         margin-top: 3rem;
      }

      .stat-card {
         background: var(--white);
         border-radius: 1.5rem;
         padding: 2rem;
         text-align: center;
         box-shadow: 0 4px 16px rgba(67,97,238,0.08);
         transition: transform 0.3s ease, box-shadow 0.3s ease;
         position: relative;
         overflow: hidden;
      }

      .stat-card::before {
         content: '';
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
         opacity: 0.05;
         z-index: 0;
      }

      .stat-card:hover {
         transform: translateY(-5px);
         box-shadow: 0 8px 24px rgba(67,97,238,0.12);
      }

      .stat-icon {
         font-size: 2.4rem;
         color: var(--primary);
         margin-bottom: 1rem;
         position: relative;
         z-index: 1;
      }

      .stat-number {
         font-size: 2.8rem;
         font-weight: 700;
         color: var(--secondary);
         margin-bottom: 0.5rem;
         position: relative;
         z-index: 1;
      }

      .stat-label {
         font-size: 1.4rem;
         color: var(--gray-600);
         position: relative;
         z-index: 1;
      }

      .stat-link {
         display: inline-block;
         margin-top: 1rem;
         color: var(--primary);
         font-weight: 600;
         text-decoration: none;
         position: relative;
         z-index: 1;
         transition: color 0.3s ease;
      }

      .stat-link:hover {
         color: var(--primary-dark);
      }

      .profile-progress {
         margin-top: 3rem;
         background: var(--white);
         border-radius: 1.5rem;
         padding: 2rem;
         box-shadow: 0 4px 16px rgba(67,97,238,0.08);
      }

      .progress-title {
         font-size: 1.8rem;
         font-weight: 600;
         color: var(--secondary);
         margin-bottom: 1.5rem;
         display: flex;
         align-items: center;
         gap: 1rem;
      }

      .progress-title i {
         color: var(--primary);
      }

      .progress-bar {
         height: 1rem;
         background: var(--gray-200);
         border-radius: 0.5rem;
         overflow: hidden;
         margin-bottom: 1rem;
      }

      .progress-fill {
         height: 100%;
         background: linear-gradient(90deg, var(--primary) 0%, var(--primary-dark) 100%);
         border-radius: 0.5rem;
         transition: width 0.3s ease;
      }

      .progress-stats {
         display: flex;
         justify-content: space-between;
         font-size: 1.4rem;
         color: var(--gray-600);
      }

      .recent-activity {
         margin-top: 3rem;
         background: var(--white);
         border-radius: 1.5rem;
         padding: 2rem;
         box-shadow: 0 4px 16px rgba(67,97,238,0.08);
      }

      .activity-title {
         font-size: 1.8rem;
         font-weight: 600;
         color: var(--secondary);
         margin-bottom: 1.5rem;
         display: flex;
         align-items: center;
         gap: 1rem;
      }

      .activity-title i {
         color: var(--primary);
      }

      .activity-list {
         display: flex;
         flex-direction: column;
         gap: 1.5rem;
      }

      .activity-item {
         display: flex;
         align-items: center;
         gap: 1.5rem;
         padding: 1.5rem;
         background: var(--gray-50);
         border-radius: 1rem;
         transition: transform 0.3s ease;
      }

      .activity-item:hover {
         transform: translateX(5px);
      }

      .activity-icon {
         width: 4rem;
         height: 4rem;
         background: var(--primary);
         border-radius: 1rem;
         display: flex;
         align-items: center;
         justify-content: center;
         color: var(--white);
         font-size: 1.8rem;
      }

      .activity-content {
         flex: 1;
      }

      .activity-text {
         font-size: 1.4rem;
         color: var(--secondary);
         margin-bottom: 0.5rem;
      }

      .activity-time {
         font-size: 1.2rem;
         color: var(--gray-600);
      }

      /* Dark theme styles */
      body.dark .profile-header,
      body.dark .stat-card,
      body.dark .profile-progress,
      body.dark .recent-activity {
         background: var(--gray-800);
      }

      body.dark .profile-name,
      body.dark .stat-number,
      body.dark .progress-title,
      body.dark .activity-title,
      body.dark .activity-text {
         color: var(--white);
      }

      body.dark .profile-role,
      body.dark .stat-label,
      body.dark .progress-stats,
      body.dark .activity-time {
         color: var(--gray-400);
      }

      body.dark .stat-icon,
      body.dark .profile-btn {
         color: var(--primary-light);
      }

      body.dark .profile-btn {
         border-color: var(--primary-light);
      }

      body.dark .profile-btn:hover {
         background: var(--primary-light);
         color: var(--white);
      }

      body.dark .activity-item {
         background: var(--gray-700);
      }

      @media (max-width: 768px) {
         .profile-header {
            flex-direction: column;
            text-align: center;
            padding: 2rem 1rem;
         }
         .profile-avatar-container {
            margin-right: 0;
            margin-bottom: 1.5rem;
         }
         .profile-info {
            align-items: center;
         }
         .profile-actions {
            flex-direction: column;
            gap: 0.7rem;
            width: 100%;
         }
         .profile-btn {
            width: 100%;
            justify-content: center;
         }
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="profile-container">
   <div class="profile-header">
      <div class="profile-avatar-container">
         <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="Profile" class="profile-avatar">
      </div>
      <div class="profile-info">
         <h1 class="profile-name">
            <?= $fetch_profile['name']; ?>
            <?php if(isset($fetch_profile['verified']) && $fetch_profile['verified']): ?>
               <i class="fas fa-check-circle profile-verified"></i>
            <?php endif; ?>
         </h1>
         <div class="profile-role">
            <i class="fas fa-graduation-cap"></i>
            <?= isset($fetch_profile['role']) ? $fetch_profile['role'] : 'student'; ?>
         </div>
         <div class="profile-actions">
            <a href="update.php" class="profile-btn">
               <i class="fas fa-user-edit"></i>
               Edit Profile
            </a>
            <a href="settings.php" class="profile-btn">
               <i class="fas fa-cog"></i>
               Settings
            </a>
         </div>
      </div>
   </div>

   <div class="profile-stats">
      <div class="stat-card">
         <div class="stat-icon"><i class="fas fa-bookmark"></i></div>
         <div class="stat-number"><?= $total_bookmarked; ?></div>
         <div class="stat-label">Saved Playlists</div>
         <a href="bookmarks.php" class="stat-link">View Playlists</a>
      </div>

      <div class="stat-card">
         <div class="stat-icon"><i class="fas fa-heart"></i></div>
         <div class="stat-number"><?= $total_likes; ?></div>
         <div class="stat-label">Liked Videos</div>
         <a href="liked.php" class="stat-link">View Liked</a>
      </div>

      <div class="stat-card">
         <div class="stat-icon"><i class="fas fa-comment"></i></div>
         <div class="stat-number"><?= $total_comments; ?></div>
         <div class="stat-label">Comments</div>
         <a href="comments.php" class="stat-link">View Comments</a>
      </div>
   </div>

   <div class="profile-progress">
      <h2 class="progress-title">
         <i class="fas fa-chart-line"></i>
         Learning Progress
      </h2>
      <div class="progress-bar">
         <div class="progress-fill" style="width: <?= $progress_percentage; ?>%;"></div>
      </div>
      <div class="progress-stats">
         <span><?= $progress_percentage; ?>% Complete</span>
         <span><?= $completed_courses; ?>/<?= $total_courses; ?> Courses</span>
      </div>
   </div>

   <div class="recent-activity">
      <h2 class="activity-title">
         <i class="fas fa-history"></i>
         Recent Activity
      </h2>
      <div class="activity-list">
         <?php if(count($activities) > 0): ?>
            <?php foreach($activities as $activity): ?>
               <div class="activity-item">
                  <div class="activity-icon">
                     <i class="<?= getActivityIcon($activity['type']); ?>"></i>
                  </div>
                  <div class="activity-content">
                     <div class="activity-text"><?= getActivityText($activity['type'], $activity['content']); ?></div>
                     <div class="activity-time"><?= timeAgo($activity['time']); ?></div>
                  </div>
               </div>
            <?php endforeach; ?>
         <?php else: ?>
            <div class="activity-item">
               <div class="activity-content">
                  <div class="activity-text">No recent activity</div>
               </div>
            </div>
         <?php endif; ?>
      </div>
   </div>
</div>

<?php include 'components/footer.php'; ?>

<script src="../FRONT END/js/modern.js"></script>
</body>
</html>