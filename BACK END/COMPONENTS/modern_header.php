<?php
if(isset($page_title)) {
   $title = $page_title;
} else {
   $title = "Educa";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= $title; ?> | Educa</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
   <link rel="stylesheet" href="../FRONT END/css/modern.css">
</head>
<body>

<header class="modern-header">
   <nav class="nav-container">
      <div class="logo-container">
         <i class="fas fa-graduation-cap"></i>
         <a href="home.php" class="logo">Educa.</a>
      </div>

      <div class="nav-links">
         <a href="home.php" class="nav-link <?= $page_title == 'Home' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i>
            <span>Home</span>
         </a>
         <a href="courses.php" class="nav-link <?= $page_title == 'Courses' ? 'active' : ''; ?>">
            <i class="fas fa-graduation-cap"></i>
            <span>Courses</span>
         </a>
         <a href="teachers.php" class="nav-link <?= $page_title == 'Teachers' ? 'active' : ''; ?>">
            <i class="fas fa-chalkboard-teacher"></i>
            <span>Teachers</span>
         </a>
         <a href="about.php" class="nav-link <?= $page_title == 'About' ? 'active' : ''; ?>">
            <i class="fas fa-question-circle"></i>
            <span>About</span>
         </a>
         <a href="contact.php" class="nav-link <?= $page_title == 'Contact' ? 'active' : ''; ?>">
            <i class="fas fa-headset"></i>
            <span>Contact</span>
         </a>
      </div>

      <div class="nav-right">
         <form action="courses.php" method="post" class="search-box">
            <input type="text" name="search_course" placeholder="Search courses..." maxlength="100">
            <button type="submit" name="search_course_btn">
               <i class="fas fa-search"></i>
            </button>
         </form>

         <div class="user-actions">
            <?php if($user_id != ''): ?>
               <div class="profile-container">
                  <?php
                     $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                     $select_profile->execute([$user_id]);
                     if($select_profile->rowCount() > 0){
                        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                  ?>
                  <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="" class="profile-img">
                  <?php } ?>
                  <div class="dropdown-menu">
                     <a href="profile.php">
                        <i class="fas fa-user"></i>
                        Profile
                     </a>
                     <a href="components/user_logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                     </a>
                  </div>
               </div>
            <?php else: ?>
               <a href="login.php" class="auth-btn login">Login</a>
               <a href="register.php" class="auth-btn register">Register</a>
            <?php endif; ?>
            
            <button class="theme-toggle">
               <i class="fas fa-moon"></i>
            </button>
         </div>
      </div>
   </nav>
</header>

<style>
:root {
   --header-bg: rgba(15, 23, 42, 0.95);
   --primary-blue: #4361ee;
   --text-light: rgba(255, 255, 255, 0.9);
   --text-lighter: rgba(255, 255, 255, 0.7);
   --border-color: rgba(255, 255, 255, 0.1);
}

.modern-header {
   position: fixed;
   top: 0;
   left: 0;
   right: 0;
   z-index: 1000;
   background: var(--header-bg);
   backdrop-filter: blur(8px);
   border-bottom: 1px solid var(--border-color);
}

.nav-container {
   max-width: 1400px;
   margin: 0 auto;
   padding: 0.75rem 2rem;
   display: flex;
   align-items: center;
   justify-content: space-between;
   gap: 2rem;
}

.logo-container {
   display: flex;
   align-items: center;
   gap: 0.75rem;
}

.logo-container i {
   font-size: 1.75rem;
   color: var(--primary-blue);
}

.logo {
   font-size: 1.5rem;
   font-weight: 700;
   color: #fff;
   text-decoration: none;
}

.nav-links {
   display: flex;
   align-items: center;
   gap: 2.5rem;
}

.nav-link {
   display: flex;
   align-items: center;
   gap: 0.5rem;
   color: var(--text-lighter);
   text-decoration: none;
   font-size: 1rem;
   transition: color 0.2s ease;
}

.nav-link i {
   font-size: 1.1rem;
}

.nav-link:hover,
.nav-link.active {
   color: var(--text-light);
}

.nav-right {
   display: flex;
   align-items: center;
   gap: 1.5rem;
}

.search-box {
   display: flex;
   align-items: center;
   background: rgba(255, 255, 255, 0.1);
   border-radius: 0.5rem;
   padding: 0.5rem 0.75rem;
   min-width: 250px;
}

.search-box input {
   background: none;
   border: none;
   color: #fff;
   width: 100%;
   font-size: 0.9rem;
   padding: 0.25rem;
}

.search-box input::placeholder {
   color: var(--text-lighter);
}

.search-box button {
   background: none;
   border: none;
   color: var(--text-lighter);
   cursor: pointer;
   padding: 0.25rem;
   display: flex;
   align-items: center;
   justify-content: center;
}

.search-box button:hover {
   color: var(--text-light);
}

.user-actions {
   display: flex;
   align-items: center;
   gap: 1.25rem;
}

.auth-btn {
   padding: 0.5rem 1.25rem;
   border-radius: 0.5rem;
   font-size: 0.95rem;
   font-weight: 500;
   text-decoration: none;
   transition: all 0.2s ease;
}

.auth-btn.login {
   color: #fff;
   background: rgba(255, 255, 255, 0.1);
}

.auth-btn.register {
   color: #fff;
   background: var(--primary-blue);
}

.auth-btn:hover {
   transform: translateY(-2px);
   opacity: 0.95;
}

.profile-container {
   position: relative;
}

.profile-img {
   width: 38px;
   height: 38px;
   border-radius: 50%;
   cursor: pointer;
   border: 2px solid var(--border-color);
}

.dropdown-menu {
   position: absolute;
   top: 100%;
   right: 0;
   margin-top: 0.75rem;
   background: var(--header-bg);
   border: 1px solid var(--border-color);
   border-radius: 0.5rem;
   padding: 0.5rem;
   min-width: 180px;
   display: none;
}

.profile-container:hover .dropdown-menu {
   display: block;
}

.dropdown-menu a {
   display: flex;
   align-items: center;
   gap: 0.75rem;
   padding: 0.75rem;
   color: var(--text-lighter);
   text-decoration: none;
   transition: color 0.2s ease;
   border-radius: 0.25rem;
}

.dropdown-menu a:hover {
   color: var(--text-light);
   background: rgba(255, 255, 255, 0.05);
}

.theme-toggle {
   background: rgba(255, 255, 255, 0.1);
   border: none;
   color: var(--text-lighter);
   width: 38px;
   height: 38px;
   border-radius: 50%;
   display: flex;
   align-items: center;
   justify-content: center;
   cursor: pointer;
   transition: all 0.2s ease;
}

.theme-toggle:hover {
   color: var(--text-light);
   background: rgba(255, 255, 255, 0.15);
}

@media (max-width: 1024px) {
   .nav-container {
      padding: 0.75rem 1.5rem;
   }

   .nav-links {
      gap: 2rem;
   }

   .search-box {
      min-width: 200px;
   }
}

@media (max-width: 768px) {
   .nav-links span {
      display: none;
   }

   .nav-links {
      gap: 1.5rem;
   }

   .nav-link i {
      font-size: 1.2rem;
   }

   .search-box {
      display: none;
   }
}

@media (max-width: 480px) {
   .nav-container {
      padding: 0.75rem 1rem;
   }

   .logo-container i {
      font-size: 1.5rem;
   }

   .logo {
      font-size: 1.25rem;
   }

   .auth-btn {
      padding: 0.4rem 0.75rem;
      font-size: 0.85rem;
   }
}
</style> 