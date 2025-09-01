<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services | Ancestral</title>
    <link rel="stylesheet" href="../cascade/HomePage.css? <?php echo time(); ?>">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #ffffff;
            color: #000000;
        }

       

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 50px 20px;
        }

        .section {
            background: #f9f9f9;
            margin: 20px 0;
            padding: 30px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }

        .section-icon {
            font-size: 2rem;
            color: #b33f62;
            flex-shrink: 0;
        }

        .section-content h2 {
            margin-top: 0;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .section-content ul {
            list-style: none;
            padding-left: 0;
        }

        .section-content ul li {
            margin-bottom: 8px;
            padding-left: 20px;
            position: relative;
        }

        .section-content ul li::before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #b33f62;
        }

        .get-started-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            border: 1px solid #b33f62;
            border-radius: 6px;
            text-decoration: none;
            color: #b33f62;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .get-started-btn:hover {
            background-color: #b33f62;
            color: #fff;
        }

    </style>
</head>
<body>

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



<div class="container">

    <div class="section">
        <div class="section-icon">🤖</div>
        <div class="section-content">
            <h2>AI Solutions</h2>
            <ul>
                <li>Predictive analytics and automation</li>
                <li>Custom AI tools for business or daily life</li>
                <li>Machine learning models and AI integration</li>
            </ul>
            <a href="#" class="get-started-btn">Get Started</a>
        </div>
    </div>

    <div class="section">
        <div class="section-icon">📱</div>
        <div class="section-content">
            <h2>Web & Mobile App Development</h2>
            <ul>
                <li>Responsive websites</li>
                <li>Web apps and Progressive Web Apps (PWAs)</li>
                <li>Mobile apps for Android and iOS</li>
            </ul>
            <a href="#" class="get-started-btn">Get Started</a>
        </div>
    </div>

    <img src="../../BackGround.jpg" class="bg-image" alt="Background Image" style="z-index: -100;" >

    <div class="section">
        <div class="section-icon">💻</div>
        <div class="section-content">
            <h2>Business Software Solutions</h2>
            <ul>
                <li>Enterprise Resource Planning (ERP) software</li>
                <li>Customer Relationship Management (CRM) tools</li>
                <li>Productivity and workflow optimization software</li>
            </ul>
            <a href="#" class="get-started-btn">Get Started</a>
        </div>
    </div>

    <div class="section">
        <div class="section-icon">🛠️</div>
        <div class="section-content">
            <h2>Software Maintenance & Support</h2>
            <ul>
                <li>Bug fixing and troubleshooting</li>
                <li>Performance optimization</li>
                <li>Regular updates and feature enhancements</li>
            </ul>
            <a href="#" class="get-started-btn">Get Started</a>
        </div>
    </div>

    <div class="section">
        <div class="section-icon">☁️</div>
        <div class="section-content">
            <h2>Cloud Solutions & Hosting</h2>
            <ul>
                <li>Cloud-based software deployment</li>
                <li>Secure and scalable hosting solutions</li>
                <li>Database management and cloud storage integration</li>
            </ul>
            <a href="#" class="get-started-btn">Get Started</a>
        </div>
    </div>

    <div class="section">
        <div class="section-icon">🎨</div>
        <div class="section-content">
            <h2>UI/UX Design</h2>
            <ul>
                <li>User-friendly interface design</li>
                <li>Prototyping and wireframing</li>
                <li>Improving user engagement and experience</li>
            </ul>
            <a href="#" class="get-started-btn">Get Started</a>
        </div>
    </div>

    <div class="section">
        <div class="section-icon">📈</div>
        <div class="section-content">
            <h2>Consulting & Technology Strategy</h2>
            <ul>
                <li>IT strategy for digital transformation</li>
                <li>AI adoption consulting</li>
                <li>Tech roadmap planning for businesses</li>
            </ul>
            <a href="#" class="get-started-btn">Get Started</a>
        </div>
    </div>

</div>

</body>
</html>
