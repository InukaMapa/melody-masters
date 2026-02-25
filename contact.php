<?php
include "includes/header.php";
?>

<main class="page-wrapper">
    <!-- Refined Hero Section -->
    <section class="contact-hero-premium">
        <div class="hero-content fade-in-up">
            <span class="badge-premium">CONTACT US</span>
            <h1>Let's Start a<span> Conversation</span></h1>
            <p>Whether you're a beginner or a pro, our experts are here to guide your musical journey.</p>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="info-section">
        <div class="container-narrow">
            <div class="contact-grid">
                <!-- Contact Form -->
                <div class="contact-form-wrapper card-block">
                    <h3>Send us a Message</h3>
                    <form action="process_contact.php" method="POST" class="modern-form">
                        <div class="grid-cols-2">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="Inquiry about Piano" required>
                        </div>
                        <div class="form-group">
                            <label>Message</label>
                            <textarea name="message" class="form-control" rows="5" placeholder="How can we help you?" required></textarea>
                        </div>
                        <div class="form-actions" style="display: flex; justify-content: center; margin-top: 20px;">
                            <button type="submit" class="btn-premium-sm" style="width: auto; padding: 12px 40px;">Send Message</button>
                        </div>
                    </form>
                </div>

                <!-- Contact Details -->
                <div class="contact-details">
                    <div class="detail-card-glass">
                        <div class="icon-circle"><i class="fa fa-map-marker-alt"></i></div>
                        <div class="detail-info">
                            <h4>Showroom</h4>
                            <p>123 Music Avenue<br>Sound City, SC 45678</p>
                        </div>
                    </div>
                    <div class="detail-card-glass">
                        <div class="icon-circle"><i class="fa fa-envelope-open"></i></div>
                        <div class="detail-info">
                            <h4>Email Direct</h4>
                            <p>hello@melodymasters.com</p>
                            <p>support@melodymasters.com</p>
                        </div>
                    </div>
                    <div class="detail-card-glass">
                        <div class="icon-circle"><i class="fa fa-phone-volume"></i></div>
                        <div class="detail-info">
                            <h4>Call Us</h4>
                            <p>+1 (555) 123-4567</p>
                            <p>Mon - Sat, 9am - 8pm</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php include "includes/footer.php"; ?>
