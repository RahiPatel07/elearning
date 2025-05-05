<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

// Get current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<header class="header">
  <nav class="main-navbar">
    <div class="nav-left">
      <a href="home.php" class="brand-logo">
        <i class="fas fa-graduation-cap" style="font-size:2.4rem;color:var(--primary);"></i>
        <span class="brand-name" style="color:var(--primary);">Educa.</span>
      </a>
    </div>
    <div class="nav-center">
      <a href="home.php" class="nav-link<?= $current_page == 'home.php' ? ' active' : '' ?>">Home</a>
      <a href="courses.php" class="nav-link<?= $current_page == 'courses.php' ? ' active' : '' ?>">Courses</a>
      <a href="teachers.php" class="nav-link<?= $current_page == 'teachers.php' ? ' active' : '' ?>">Teachers</a>
      <a href="about.php" class="nav-link<?= $current_page == 'about.php' ? ' active' : '' ?>">About</a>
      <a href="contact.php" class="nav-link<?= $current_page == 'contact.php' ? ' active' : '' ?>">Contact</a>
    </div>
    <div class="nav-right">
      <form action="search_course.php" method="post" class="search-box" style="margin:0;">
        <input type="text" name="search_course" class="search-input" placeholder="Search courses..." required maxlength="100">
        <button type="submit" class="search-btn" name="search_course_btn">
          <i class="fas fa-search"></i>
        </button>
      </form>
      <a href="#" class="nav-action"><i class="far fa-bell"></i></a>
      <button class="theme-toggle-new" title="Theme Toggle" tabindex="0" aria-label="Theme Toggle">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><g><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></g></svg>
      </button>
      <?php
        $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
        $select_profile->execute([$user_id]);
        if($select_profile->rowCount() > 0){
          $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
          $avatar = 'uploaded_files/' . $fetch_profile['image'];
        } else {
          $avatar = '../FRONT END/images/default-avatar.jpg';
        }
      ?>
      <div class="profile-dropdown">
        <div class="profile-trigger nav-avatar"><img src="<?= $avatar ?>" alt="User" /></div>
        <div class="profile-menu">
          <?php if($select_profile->rowCount() > 0){ ?>
          <div class="profile-header">
            <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
            <div class="profile-info">
              <h3><?= $fetch_profile['name']; ?></h3>
              <span>student</span>
            </div>
          </div>
          <div class="profile-actions">
            <a href="profile.php" class="menu-item"><i class="fas fa-user"></i>View Profile</a>
            <a href="components/user_logout.php" class="menu-item" onclick="return confirm('Logout from this website?');"><i class="fas fa-sign-out-alt"></i>Logout</a>
          </div>
          <?php } else { ?>
          <div class="profile-actions">
            <a href="login.php" class="menu-item"><i class="fas fa-sign-in-alt"></i>Login</a>
            <a href="register.php" class="menu-item"><i class="fas fa-user-plus"></i>Register</a>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </nav>
</header>

<style>
:root {
  --primary: #4361ee;
  --navbar-bg: #fafafd;
  --navbar-text: #111;
  --navbar-link: #222;
  --navbar-link-hover: #111;
  --navbar-link-bg-hover: #eaeaea;
  --navbar-link-active-bg: #111;
  --navbar-link-active-text: #fff;
  --navbar-border: #eee;
  --navbar-shadow: 0 2px 16px #0001;
  --bg-main: var(--navbar-bg);
  --text-main: var(--navbar-text);
  --border-main: var(--navbar-border);
}
.dark {
  --navbar-bg: #181c24;
  --navbar-text: #fff;
  --navbar-link: #e0e6f0;
  --navbar-link-hover: #fff;
  --navbar-link-bg-hover: #222b3a;
  --navbar-link-active-bg: #4361ee;
  --navbar-link-active-text: #fff;
  --navbar-border: #222b3a;
  --navbar-shadow: 0 2px 16px #0004;
  --bg-main: #181c24;
  --text-main: #fff;
  --border-main: #222b3a;
}
.header {
  background: var(--bg-main);
  color: var(--text-main);
  background: var(--navbar-bg);
  padding: 0;
  position: sticky;
  top: 0;
  z-index: 1000;
  transition: background 0.3s, border-color 0.3s;
}
.main-navbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  max-width: 1400px;
  margin: 0 auto;
  padding: 1.5rem 3.5rem;
  background: transparent !important;
  border-radius: 0 !important;
  min-height: 80px;
}
.nav-left {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}
.nav-left .brand-logo {
  display: flex;
  align-items: center;
  gap: 0.9rem;
  font-size: 2rem;
  font-weight: 700;
  color: var(--navbar-text);
  text-decoration: none;
}
.brand-name {
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  letter-spacing: 0.01em;
  font-size: 2rem;
}
.nav-center {
  display: flex;
  align-items: center;
  gap: 2.8rem;
}
.nav-link {
  color: var(--navbar-link);
  font-size: 1.25rem;
  font-weight: 500;
  text-decoration: none;
  padding: 0.8rem 2.2rem;
  border-radius: 999px;
  transition: background 0.2s, color 0.2s;
  background: transparent;
}
.nav-link.active {
  background: var(--navbar-link-active-bg);
  color: var(--navbar-link-active-text);
  font-weight: 700;
  border-radius: 999px;
}
.nav-link:hover {
  background: var(--navbar-link-bg-hover);
  color: var(--navbar-link-hover);
  border-radius: 999px;
}
.nav-right {
  display: flex;
  align-items: center;
  gap: 1.7rem;
}
.search-box {
  position: relative;
  margin: 0 1.2rem 0 0;
}
.search-input {
  background: #f3f3f3;
  border: 2px solid transparent;
  border-radius: 2rem;
  padding: 1rem 2.8rem 1rem 1.5rem;
  color: var(--navbar-text);
  font-size: 1.1rem;
  width: 200px;
  transition: all 0.2s;
}
.dark .search-input {
  background: #232b3b;
  color: #fff;
}
.search-input:focus {
  background: #fff;
  border-color: var(--primary);
  box-shadow: 0 0 0 2px #4361ee22;
}
.dark .search-input:focus {
  background: #232b3b;
}
.search-btn {
  position: absolute;
  right: 0.7rem;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: #888;
  cursor: pointer;
  font-size: 1.3rem;
  transition: color 0.2s;
}
.search-btn:hover {
  color: var(--primary);
}
.profile-dropdown {
  position: relative;
}
.profile-trigger.nav-avatar {
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: #f3f3f3;
  border: 2px solid #eee;
  overflow: hidden;
  cursor: pointer;
  transition: background 0.2s, border-color 0.2s;
}
.dark .profile-trigger.nav-avatar {
  background: #232b3b;
  border-color: #222b3a;
}
.profile-trigger.nav-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 50%;
  display: block;
}
.profile-menu {
  position: absolute;
  top: 120%;
  right: 0;
  background: var(--navbar-bg);
  border-radius: 50px;
  padding: 1rem 0;
  min-width: 220px;
  box-shadow: var(--navbar-shadow);
  border: 1px solid var(--navbar-border);
  display: none;
  z-index: 1001;
  transition: background 0.3s, border-color 0.3s;
}
.profile-menu.active {
  display: block;
}
.profile-header {
  display: flex;
  align-items: center;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid var(--navbar-border);
}
.profile-header img {
  width: 54px;
  height: 54px;
  border-radius: 50%;
  margin-right: 1.2rem;
}
.profile-info h3 {
  color: var(--navbar-text);
  font-size: 1.2rem;
  margin-bottom: 0.2rem;
}
.profile-info span {
  color: #888;
  font-size: 1rem;
  font-weight: 500;
}
.profile-actions {
  display: flex;
  flex-direction: column;
  gap: 0.2rem;
  padding: 0.5rem 0;
}
.menu-item {
  display: flex;
  align-items: center;
  padding: 0.9rem 1.7rem;
  color: var(--navbar-link);
  font-size: 1.1rem;
  text-decoration: none;
  transition: background 0.2s, color 0.2s;
  border-radius: 0.7rem;
  gap: 0.7rem;
}
.menu-item i {
  font-size: 1.2rem;
  color: #888;
}
.menu-item:hover {
  background: var(--navbar-link-bg-hover);
  color: var(--navbar-link-hover);
}
.nav-action {
  color: var(--navbar-link);
  font-size: 1.5rem;
  background: #f3f3f3;
  border-radius: 50%;
  width: 44px;
  height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s;
  text-decoration: none;
}
.dark .nav-action {
  background: #232b3b;
  color: #fff;
}
.nav-action:hover {
  background: #e0e0e0;
}
.dark .nav-action:hover {
  background: #222b3a;
}
.nav-avatar img {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #fff;
  box-shadow: 0 2px 8px #0001;
  background: #eee;
}
@media (max-width: 900px) {
  .main-navbar {
    flex-direction: column;
    gap: 1.5rem;
    padding: 1.2rem 0.5rem;
    min-height: unset;
  }
  .nav-center {
    gap: 1.2rem;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
   // Menu button functionality
   const menuBtn = document.getElementById('menu-btn');
   const sideBar = document.querySelector('.side-bar');
   
   if (menuBtn && sideBar) {
      menuBtn.addEventListener('click', function() {
         sideBar.classList.toggle('active');
         menuBtn.classList.toggle('active');
      });

      // Close sidebar when clicking outside
      document.addEventListener('click', function(e) {
         if (!menuBtn.contains(e.target) && !sideBar.contains(e.target)) {
            sideBar.classList.remove('active');
            menuBtn.classList.remove('active');
         }
      });
   }

   // Profile dropdown toggle
   const profileTrigger = document.querySelector('.profile-trigger');
   const profileMenu = document.querySelector('.profile-menu');
   
   if (profileTrigger && profileMenu) {
      profileTrigger.addEventListener('click', function(e) {
         e.stopPropagation();
         profileMenu.classList.toggle('active');
      });

      // Close profile menu when clicking outside
      document.addEventListener('click', function(e) {
         if (!profileTrigger.contains(e.target) && !profileMenu.contains(e.target)) {
            profileMenu.classList.remove('active');
         }
      });
   }
});
</script>