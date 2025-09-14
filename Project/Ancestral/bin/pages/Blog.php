<?php

    session_start();

    if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} elseif (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blog & News | Ancestral</title>
  <link rel="stylesheet" href="../cascade/Blog.css">
  <link rel="stylesheet" href="../cascade/HomePage.css">
</head>
<body>

<!-- Header -->
<div class="header-container">
    <header id="main-header">
        <h1 onclick = "location.href = 'HomePage.php'">NCESTRAL</h1>
        <img src="../../Logo.svg" alt="Ancestral Logo" class="Logo">
        <nav class="nav">
            <a href="Service.php">Services</a>
            <a href="Blog.php">Blog/News</a>
            <a href="About.php">About</a>
            <a href="Contact.php" id="Contact">Contact</a>
            <a href="#" class="btn" onclick = >Get Started</a>
        </nav>
    </header>
</div>

<!-- Hero Section -->
<section class="hero-x">
  <div class="hero-content">
    <h2>Blog & News</h2>
    <p>Stay updated with the latest insights, technology trends, and company news from Ancestral.</p>
  </div>
</section>

<!-- Blog & News Section -->
<section class="blog-section">
  <div class="container">

    <!-- Card 1 -->
    <div class="blog-card">
      <img src="../../AITB.jpg" alt="AI Revolution">
      <div class="blog-content">
        <span class="category">Blog</span>
        <h3>How AI is Transforming Businesses</h3>
        <p>Discover how artificial intelligence is revolutionizing industries with automation, predictive analytics, and smarter decision-making.</p>
        <div class="meta">March 15, 2025</div>
        <a href="#" class="read-more">Read More</a>
      </div>
    </div>

    <!-- Card 2 -->
    <div class="blog-card">
      <img src="../../MCP.jpg" alt="Cloud Partnership">
      <div class="blog-content">
        <span class="category">News</span>
        <h3>Ancestral Partners with Major Cloud Provider</h3>
        <p>We’re excited to announce our new partnership that enhances cloud hosting and data management solutions for our clients.</p>
        <div class="meta">February 28, 2025</div>
        <a href="#" class="read-more">Read More</a>
      </div>
    </div>

    <!-- Card 3 -->
    <div class="blog-card">
      <img src="../../WT.png" alt="Web Development Trends">
      <div class="blog-content">
        <span class="category">Blog</span>
        <h3>Top 5 Web Development Trends in 2025</h3>
        <p>From WebAssembly to Progressive Web Apps, discover what’s shaping the future of web and mobile development.</p>
        <div class="meta">January 20, 2025</div>
        <a href="#" class="read-more">Read More</a>
      </div>
    </div>

  </div>
</section>

<!-- Footer -->
<footer>
  <p>© 2025 Ancestral. All Rights Reserved.</p>
</footer>

</body>
</html>
