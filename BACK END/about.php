<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$page_title = "About Us";
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About Us - E-Learning Platform</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../FRONT END/css/modern.css">
   <style>
.main-wrapper {
    min-height: 100vh;
    background: var(--body-bg);
    padding-top: 0;
}

.page-content {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

.about-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xxl) 0;
    text-align: center;
    margin-bottom: 4rem;
    color: var(--white);
    box-shadow: var(--shadow-lg);
}

.about-hero h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.about-hero p {
    font-size: 1.2rem;
    opacity: 0.9;
}

.content-section {
    background: var(--card-bg);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-bottom: 3rem;
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-sm);
}

.section-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.icon-wrapper {
    width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--primary);
    color: var(--white);
    border-radius: var(--radius);
    font-size: 1.2rem;
}

.section-header h2 {
    font-size: 1.8rem;
    color: var(--heading-color);
    font-weight: 600;
}

.section-content p {
    font-size: 1.1rem;
    line-height: 1.6;
    color: var(--text-color);
}

.stats-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: var(--card-bg);
    padding: 2rem;
    text-align: center;
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-sm);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card i {
    font-size: 2rem;
    color: var(--primary);
    margin-bottom: 1rem;
}

.stat-card h3 {
    font-size: 2rem;
    color: var(--heading-color);
    margin-bottom: 0.5rem;
}

.stat-card p {
    color: var(--text-muted);
    font-size: 1rem;
}

.features-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.feature-card {
    background: var(--card-bg);
    padding: 2rem;
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-sm);
    text-align: center;
    transition: transform 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
}

.feature-icon {
    width: 4rem;
    height: 4rem;
    background: var(--primary);
    color: var(--white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 1.5rem;
}

.feature-card h3 {
    font-size: 1.3rem;
    color: var(--heading-color);
    margin-bottom: 1rem;
}

.feature-card p {
    color: var(--text-muted);
    font-size: 1rem;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .about-hero {
        padding: 3rem 1.5rem;
    }

    .about-hero h1 {
        font-size: 2.5rem;
    }

    .section-header h2 {
        font-size: 1.6rem;
    }

    .stats-section {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
    }

    .stat-card {
        padding: 1.5rem;
    }

    .features-section {
        grid-template-columns: 1fr;
    }
}

.hero-section {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    padding: var(--spacing-xxl) 0;
    color: var(--white);
    text-align: center;
    margin-bottom: var(--spacing-xl);
    width: 100vw;
    max-width: 100vw;
    margin-left: calc(-50vw + 50%);
    margin-right: calc(-50vw + 50%);
    border-radius: 0 !important;
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
   font-size: 1.5rem;
   opacity: 0.9;
   margin-bottom: var(--spacing-lg);
}
@media (max-width: 768px) {
   .hero-section {
      padding: 3rem 1.5rem;
   }
   .hero-title {
      font-size: 2.5rem;
   }
}

.container {
  max-width: none !important;
  margin: 0 !important;
  padding: 0 !important;
}

.contact-info,
.contact-info h2,
.contact-info h3,
.contact-info p {
    color: #fff !important;
}

.contact-text h3,
.contact-text p {
    color: #fff !important;
}

.social-link i {
    color: var(--primary) !important;
}

.social-icon-color {
    color: var(--primary) !important;
}
   </style>
</head>
<body>
<?php include 'components/user_header.php'; ?>

<div class="main-wrapper">
    <div class="page-content">
        <div class="container">
            <!-- Hero Section -->
            <section class="hero-section">
               <div class="hero-content">
                  <h1 class="hero-title">About Us</h1>
                  <p class="hero-subtitle">Learn more about our mission and vision</p>
               </div>
            </section>

            <!-- Story Section -->
            <div class="content-section">
                <div class="section-header">
                    <div class="icon-wrapper">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <h2>Our Story</h2>
                </div>
                <div class="section-content">
                    <p>Welcome to Educa, where learning meets innovation. We are passionate about providing high-quality education that's accessible to everyone. Our platform brings together expert instructors and eager learners in an interactive online environment.</p>
                </div>
            </div>

            <!-- Mission Section -->
            <div class="content-section">
                <div class="section-header">
                    <div class="icon-wrapper">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h2>Our Mission</h2>
                </div>
                <div class="section-content">
                    <p>Our mission is to empower individuals through education. We believe that knowledge should be accessible to all, and we strive to create an inclusive learning environment that caters to diverse learning styles and needs.</p>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="stats-section">
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <h3>10,000+</h3>
                    <p>Active Students</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>100+</h3>
                    <p>Expert Instructors</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>500+</h3>
                    <p>Courses Available</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-certificate"></i>
                    <h3>15,000+</h3>
                    <p>Certifications</p>
                </div>
            </div>

            <!-- Features Section -->
            <div class="features-section">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h3>Online Learning</h3>
                    <p>Access courses anytime, anywhere with our flexible learning platform.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Expert Teachers</h3>
                    <p>Learn from industry professionals with real-world experience.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Lifetime Access</h3>
                    <p>Get unlimited access to your purchased courses forever.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'components/footer.php'; ?>

<!-- Custom JS file -->
<script src="../FRONT END/js/modern.js"></script>
   
</body>
</html>