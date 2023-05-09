<?php
    include "top.php";
?>

<main class="listing-page">
    <div class="listing">
        <div class="listing-slideshow">
            <!-- Slides -->
            <div class="slide">
                <img src="images/landing-background.jpg">
            </div>
            <div class="slide">
                <img src="images/adad.PNG">
            </div>
            <div class="slide">
                <img src="images/Capture.PNG">
            </div>
            <div class="slide">
                <img src="images/colors.PNG">
            </div>

            <!-- Next/Previous Buttons -->
            <button class="slideshow-previous" onClick="changeSlide(-1);">&#10094;</button>
            <button class="slideshow-next" onClick="changeSlide(1);">&#10095;</button>

            <!-- Slideshow Dots -->
            <div class="slideshow-dots">
                <span class="slideshow-dot" onclick="setSlide(1)"></span>
                <span class="slideshow-dot" onclick="setSlide(2)"></span>
                <span class="slideshow-dot" onclick="setSlide(3)"></span>
                <span class="slideshow-dot" onclick="setSlide(4)"></span>
            </div>
        </div>
        <section class="listing-header">
            <h2>Dorm Listing Example</h2>
            <p><strong>Posted By:</strong> <a href="#">Username</a></p>
            <p><i class="fa fa-envelope"></i> <a href="mailto:example@gmail.com">example@gmail.com</a></p>
            <button class="follow-button"><i class="fa fa-heart"></i> Save Listing</button>
        </section>
        <section class="listing-details">
            <h3>Information</h3>
            <p><strong>Residential Hall:</strong> Harris</p>
            <p><strong>Campus:</strong> Athletic</p>
            <p><strong>Room Type:</strong> Double</p>
            <p><strong>Bathroom: </strong>Multiple-occupancy, gendered.</p>
        </section>
        <section class="listing-description">
            <h3>Description</h3>
            <p>Placeholder description about room and people living here.</p>
        </section>
        <section class="listing-additional-information">
            <h3>Additional Information</h3>
            <p>None.</p>
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

            // Slideshow
            let slideIndex = 1;
            showSlide(slideIndex);

            function changeSlide(indexChange) {
                slideIndex += indexChange;
                showSlide(slideIndex);
            }

            function setSlide(slide) {
                slideIndex = slide;
                showSlide(slideIndex);
            }

            function showSlide(slide) {
                let slides = document.getElementsByClassName("slide");
                let dots = document.getElementsByClassName("slideshow-dot");
                
                // Return to first slide if user toggles next past last slide.
                if(slide > slides.length) {
                    slideIndex = 1;
                }

                // Set to last slide if user toggles previous past the first slide.
                if(slide < 1) {
                    slideIndex = slides.length;
                }

                let index;
                for(index = 0; index < slides.length; index++) {
                    slides[index].style.display = "none";
                }
                
                for(index = 0; index < slides.length; index++) {
                    dots[index].className = dots[index].className.replace(" active-slide", "");
                }

                slides[slideIndex - 1].style.display = "flex";
                dots[slideIndex - 1].className += " active-slide";
            }
        </script>
    </body>
</html>