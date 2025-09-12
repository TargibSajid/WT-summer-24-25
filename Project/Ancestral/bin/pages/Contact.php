<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Ancestral</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #333;
        }

        .contact-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
        }

        .contact-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .contact-header h1 {
            font-size: 36px;
            color: #222;
        }

        .contact-header p {
            font-size: 18px;
            color: #555;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .contact-info {
            background: #f5f5f5;
            padding: 25px;
            border-radius: 10px;
        }

        .contact-info h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .contact-info p {
            margin: 10px 0;
        }

        .contact-form {
            background: #f5f5f5;
            padding: 25px;
            border-radius: 10px;
        }

        .contact-form h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .contact-form form input,
        .contact-form form textarea,
        .contact-form form select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .contact-form form button {
            background: #0066cc;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .contact-form form button:hover {
            background: #004999;
        }

        .faq-section {
            margin-top: 50px;
        }

        .faq-section h2 {
            font-size: 28px;
            margin-bottom: 15px;
        }

        .faq {
            margin: 10px 0;
        }

        .faq h3 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .map-section {
            margin-top: 50px;
        }

        iframe {
            width: 100%;
            height: 350px;
            border: none;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="contact-container">
        <div class="contact-header">
            <h1>Contact Ancestral</h1>
            <p>We’re here to help! Reach out to us for inquiries, support, or collaborations.</p>
        </div>

        <div class="contact-grid">
            <div class="contact-info">
                <h2>Get in Touch</h2>
                <p><strong>Phone:</strong> +880 1234 567890</p>
                <p><strong>Email:</strong> contact@ancestral.com</p>
                <p><strong>Address:</strong> House 10, Road 12, Dhanmondi, Dhaka, Bangladesh</p>
            </div>

            <div class="contact-form">
                <h2>Send Us a Message</h2>
                <form>
                    <input type="text" placeholder="Full Name" required>
                    <input type="email" placeholder="Email Address" required>
                    <input type="tel" placeholder="Phone Number" required>
                    <select>
                        <option>Select Subject</option>
                        <option>General Inquiry</option>
                        <option>Partnership</option>
                        <option>Support</option>
                    </select>
                    <textarea rows="5" placeholder="Your Message" required></textarea>
                    <button type="submit">Send Message</button>
                </form>
            </div>
        </div>

        <div class="faq-section">
            <h2>Frequently Asked Questions</h2>
            <div class="faq">
                <h3>How soon will you respond?</h3>
                <p>We aim to respond within 24–48 hours.</p>
            </div>
            <div class="faq">
                <h3>Do you offer free consultations?</h3>
                <p>Yes, our initial consultations are completely free.</p>
            </div>
        </div>

        <div class="map-section">
            <h2>Find Us on the Map</h2>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!..." allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</body>
</html>
