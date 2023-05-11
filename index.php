<?php
    include "top.php";
?>

<main class="landing">
    <div class="hero-banner">
        <section class="hero-content">
            <h2>UVM Housing Finder</h2>
            <p>Need a place to stay? Find the right <span style="color: #FFD416">home</span> for you today.</p>
            <nav class="cta-nav">
                <a href="register.php" class="cta">Get Started</a>
                <a href="#landing-top" class="cta secondary-cta">Learn More</a>
            </nav>
        </section>
    </div>

    <div class="landing-section-wrapper" id="landing-top">
        <section class="landing-section">
            <h2>Find a Home Hassle-Free</h2>
            <p>We make browsing for apartments and getting connected with potential roommates easy by allowing you to search, connect, and share on your terms. Take a look around and find the right place for you.</p>
        </section>

        <section class="landing-section">
            <h2>How It Works</h2>
            <div class="walkthrough">
                <section class="walkthrough-card">
                    <div class="walkthrough-header">
                        <div class="landing-icon"><i class="fa fa-user-plus" aria-hidden="true"></i></div>
                        <h3>Register an Account</h3>
                    </div>
                    <p>Make an account to keep track of listings, interact with other users, and find a place that you can call home.</p>
                </section>
                <section class="walkthrough-card">
                    <div class="walkthrough-header">
                        <div class="landing-icon"><i class="fa fa-paper-plane" aria-hidden="true"></i></div>
                        <h3>Find or Make a Request</h3>
                    </div>
                    <p>Don't have a place? Browse through dormitory roommate requests, apartment roommate requests, and sublease listings. If you do have a place already, you can make a new listing for other users to see.</p>
                </section>
                <section class="walkthrough-card">
                    <div class="walkthrough-header">
                        <div class="landing-icon"><i class="fa fa-handshake" aria-hidden="true"></i></div>
                        <h3>Come to an Agreement</h3>
                    </div>
                    <p>When you find a place that interests you, or if someone is interested in your request, come to an agreement so that everyone is happy.</p>
                </section>
            </div>
        </section>
        <section class="landing-section">
            <h2>Ready to Get Started?</h2>
            <a href="register.php" class="bullet">Let's Do This!</a>
        </section>
    </div>
</main>

<?php
    include "footer.php";
?>

        <!-- Main/Unique Section -->
        <script>
            // Handle Navigation Hamburger Button.
            const hamburgerButton = document.querySelector(".hamburger-button");
            const navigationCloseButton = document.querySelector(".navigation-close-button");
            const navigationLinks = document.querySelector(".navigation-links");

            hamburgerButton.addEventListener('click', () => {
                navigationLinks.classList.toggle("show");
                navigationCloseButton.classList.toggle("show");
            });

            navigationCloseButton.addEventListener('click', () => {
                navigationLinks.classList.toggle("show");
                navigationCloseButton.classList.toggle("show");
            });
        </script>
    </body>
</html>