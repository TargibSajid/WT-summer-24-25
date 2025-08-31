<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ancestral - Empowering Businesses Through Technology</title>

    <link rel = "stylesheet"  type = "text/css" href = "../cascade/HomePage.css? <?php echo time(); ?>">


    </head>

    


<body>


<div class="logout-container" >




    <button type = "submit" class = "logout">
    <img src="../../Logout.png" alt="icon" style="width:25px; height:25px; vertical-align:left;">        
    Sign in
</button>

</div>





<div class="header-container">  
<header id = "main-header">
        <h1>NCESTRAL
            
        </h1>
        <img src="../../Logo.svg" alt="Ancestral Logo" class="Logo">
        

        <nav class="nav">
            <a href="Service.php">Services</a>
            <a href = "#"> Blog/News </a>
            <a href="About.php">About</a>
            <a href="#" id = "Contact" >Contact</a>
            
            <a href="#" class="btn">Get Started</a>

        </nav>
    </header>
</div>




    <img src="../../BackGround.jpg" class="bg-image" alt="Background Image" style="z-index: -100;" >

    <div id = "vdiv" >

    <video src="../../Intro.mp4" id = introvd   autoplay muted loop playsinline ></video>

    </div>

    <section class="hero">
        <h2>Empowering Businesses Through Technology</h2>
        <p>We provide cutting-edge software solutions and services to help businesses thrive.</p>
        <a href="#" class="btn">Get Started</a>
        

        
    </section>

    <section class="section">
        <h3>Why Choose Ancestral?</h3>
        <p>With a focus on innovation and customer satisfaction, we deliver tailor-made solutions that drive growth and efficiency.</p>
        
        <center>
          <a href="#" class="btn" style = "top : 30px">Learn More</a>
        
        </center>
    </section>







<footer id = "main-footer" >
  <div style="max-width: 1000px; margin: auto; display: flex; justify-content: space-between; flex-wrap: wrap; padding: 0 20px;">

    <!-- Left: Contact Info -->
    <div style="margin-bottom: 20px;">
      <h3 style="margin-bottom: 10px;">Contact</h3>
      <p>Email: <a href="mailto:info@ancestral.com" style="color: #2c2c2c; text-decoration: none;">info@ancestral.com</a></p>
      <p>Phone: +880123456789</p>
    </div>

    <!-- Right: Social Links -->
    <div style="margin-bottom: 20px;">
      <h3 style="margin-bottom: 10px;">Follow Us</h3>
      <p>
        <a href="#" style="color: #2c2c2c; text-decoration: none; margin-right: 15px;">Instagram</a>
        <a href="#" style="color: #2c2c2c; text-decoration: none; margin-right: 15px;">Facebook</a>
        <a href="#" style="color: #2c2c2c; text-decoration: none;">YouTube</a>
      </p>
    </div>

  </div>

  <!-- Bottom Line -->
  <div style="margin-top: 20px; font-size: 14px; color: #555;">
    © 2025 Ancestral. All Rights Reserved. |
    <a href="#" style="color: #555; text-decoration: none;">Terms of Service</a> |
    <a href="#" style="color: #555; text-decoration: none;">Privacy Policy</a>
  </div>
</footer>


    




    <script src="../interactivity/HomePage.js"></script>
    <script>
     let contactView = document.getElementById("Contact");
contactView.addEventListener('click', function(event) {
    event.preventDefault();  // this must run first
    setTimeout(() => {       // force browser to wait before scrolling
        document.getElementById("main-footer").scrollIntoView({ behavior: 'smooth' });
    }, 0);
  
});
</script>>
</body>
</html>