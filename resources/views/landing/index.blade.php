{{-- landing.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TaskMate</title>
    <link rel="icon" type="image/png" href="{{ asset('../images/logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    {{-- My Css --}}
    <link rel="stylesheet" href="{{ asset('../css/landing.css') }}">
</head>

<body>
    <nav>
        <div class="nav-logo">
            <img src="{{ '../images/logo.png' }}" alt="TaskMate Logo">
        </div>
        <div class="nav-links">
            <ul>
                <li><a href="#about">About</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#how-it-works">Usage</a></li>
                <li><a href="#testimonials">Testimonials</a></li>
                <li><a href="#faq">FAQ</a></li>
            </ul>
        </div>
        <div class="nav-button">
            <a href="{{ Route('login') }}">
                <button class="sign-in">Sign In</button>
            </a>
            <a href="{{ Route('register') }}">
                <button class="sign-up">Sign Up</button>
            </a>
        </div>
        <div class="mobile-menu-btn">
            <i class="fas fa-bars"></i>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu">
        <ul>
            <li><a href="#about">About</a></li>
            <li><a href="#features">Features</a></li>
            <li><a href="#how-it-works">How It Works</a></li>
            <li><a href="#testimonials">Testimonials</a></li>
            <li><a href="#faq">FAQ</a></li>
            <li class="mobile-btns">
                <a href="{{ Route('login') }}">
                    <button class="sign-in">Sign In</button>
                </a>
                <a href="{{ Route('register') }}">
                    <button class="sign-up">Sign Up</button>
                </a>
            </li>
        </ul>
    </div>

    <div class="section">
        <div class="description">
            <h1>Hey, Go-Getter! <br> Welcome to Task<span style="color: #39A5ED;">Mate</span></h1>
            <p>Smash your to-dos, stay on top of your game, and get <br> things done effortlessly. TaskMate's got your back <br>
                for a smoother, smarter way to tackle your day!</p>
                <a href="{{ Route('register') }}">
                    <button>Get Started</button>
                </a>
        </div>
        <div class="image">
            <img src="{{ '../images/landingImage.png' }}" alt="TaskMate Hero Image">
        </div>
    </div>

    <div class="" id="about"></div>

    <!-- About Section -->
    <div id="about" class="about-section">
        <div class="about-content">
            <h2>About Task<span style="color: #39A5ED;">Mate</span></h2>
            <div class="about-grid">
                <div class="about-text">
                    <p>TaskMate was born from a simple idea: managing your tasks shouldn't be a task itself. We've created a powerful yet intuitive platform that helps individuals and teams organize their work and achieve their goals with less stress and more efficiency.</p>
                    <p>Founded in 2022, TaskMate has quickly grown to become a trusted productivity partner for thousands of users worldwide. Our mission is to transform how people approach their daily tasks and projects by providing smart tools that adapt to your unique workflow.</p>
                    <p>Whether you're a busy professional juggling multiple projects, a student balancing assignments, or a team coordinating complex workflows, TaskMate scales to meet your needs while maintaining the simplicity you crave.</p>
                </div>
                <div class="about-stats">
                    <div class="stat-item">
                        <span class="stat-number">50K+</span>
                        <span class="stat-label">Active Users</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">1M+</span>
                        <span class="stat-label">Tasks Completed</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">99%</span>
                        <span class="stat-label">Satisfaction Rate</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Customer Support</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="" id="features"></div>
    <!-- Features Section -->
    <div id="features" class="features">
        <h2>Why Choose Task<span style="color: #39A5ED;">Mate</span>?</h2>
        <div class="features-container">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3>Simple Task Management</h3>
                <p>Create, organize, and track your tasks with an intuitive interface designed for productivity.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-note-sticky"></i>
                </div>
                <h3>Smart Notes</h3>
                <p>Organize thoughts efficiently with intelligent categorization, searchable content, and seamless integration with your tasks and calendar.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3>Calendar Integration</h3>
                <p>Sync with your favorite calendar apps to view all your commitments in one place.</p>
            </div>
        </div>
    </div>

    <div class="" id="usage"></div>
    <!-- How It Works Section -->
    <div id="how-it-works" class="how-it-works">
        <h2>How Task<span style="color: #39A5ED;">Mate</span> Works</h2>
        <div class="steps-container">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Sign Up</h3>
                <p>Create your free account in seconds and access your personal dashboard.</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>Create Tasks</h3>
                <p>Add your to-dos with details, priorities, and deadlines that matter to you.</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>Stay Organized</h3>
                <p>Track progress, receive reminders, and celebrate completing your tasks.</p>
            </div>
        </div>
    </div>

    <div class="" id="testimonials"></div>
    <!-- Testimonials Section -->
    <div id="testimonials" class="testimonials">
        <h2>What Our Users Say</h2>
        <div class="testimonials-container">
            <div class="testimonial-card">
                <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                <p>"TaskMate transformed how I manage my daily workload. I've never been more productive!"</p>
                <div class="user-info">
                    <div class="user-avatar">
                        <img src="{{ '../images/avatar1.jpg' }}" alt="User Avatar">
                    </div>
                    <div class="user-details">
                        <h4>Shin RyuJin</h4>
                        <p>Freelance Designer</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                <p>"Simple, intuitive, and exactly what I needed to keep track of my busy schedule."</p>
                <div class="user-info">
                    <div class="user-avatar">
                        <img src="{{ '../images/avatar2.jpg' }}" alt="User Avatar">
                    </div>
                    <div class="user-details">
                        <h4>Choi Hyun Wook</h4>
                        <p>Project Manager</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="" id="faq"></div>
    <!-- FAQ Section -->
    <div id="faq" class="faq-section">
        <h2>Frequently Asked Questions</h2>
        <div class="faq-container">
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Is TaskMate free to use?</h3>
                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                </div>
                <div class="faq-answer">
                    <p>Yes! TaskMate offers a free plan with essential features. We also offer premium plans with advanced capabilities for power users.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Does TaskMate integrate with other productivity tools?</h3>
                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                </div>
                <div class="faq-answer">
                    <p>Absolutely! TaskMate seamlessly integrates with popular tools like Google Calendar, Slack, Microsoft Teams, and more to create a unified productivity ecosystem.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h3>How secure is my data with TaskMate?</h3>
                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                </div>
                <div class="faq-answer">
                    <p>We take security seriously. All your data is encrypted and stored securely. We never share your information with third parties.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Newsletter Section -->
    <div class="newsletter">
        <div class="newsletter-content">
            <h2>Stay Updated</h2>
            <p>Subscribe to our newsletter for productivity tips and TaskMate updates.</p>
            <form class="newsletter-form">
                <input type="email" placeholder="Enter your email">
                <button type="submit">Subscribe</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-logo">
                <img src="{{ '../images/logo.png' }}" alt="TaskMate Logo">
                <p>TaskMate © 2023. All rights reserved.</p>
            </div>
            <div class="footer-links">
                <div class="link-column">
                    <h3>Company</h3>
                    <ul>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Press</a></li>
                        <li><a href="#">Blog</a></li>
                    </ul>
                </div>
                <div class="link-column">
                    <h3>Product</h3>
                    <ul>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#">Pricing</a></li>
                        <li><a href="#">Integrations</a></li>
                        <li><a href="#faq">FAQ</a></li>
                    </ul>
                </div>
                <div class="link-column">
                    <h3>Resources</h3>
                    <ul>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">Guides</a></li>
                        <li><a href="#">API</a></li>
                        <li><a href="#">Community</a></li>
                    </ul>
                </div>
                <div class="link-column">
                    <h3>Legal</h3>
                    <ul>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                        <li><a href="#">GDPR</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <div class="footer-nav">
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
                <a href="#">Contact Us</a>
                <a href="#">Support</a>
            </div>
        </div>
    </footer>

    <script>
        // Simple JavaScript for FAQ toggle
        document.addEventListener('DOMContentLoaded', function() {
            const faqItems = document.querySelectorAll('.faq-item');

            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');
                const answer = item.querySelector('.faq-answer');
                const icon = item.querySelector('.faq-icon i');

                question.addEventListener('click', () => {
                    // Toggle answer visibility
                    answer.classList.toggle('active');

                    // Change icon
                    if (icon.classList.contains('fa-plus')) {
                        icon.classList.remove('fa-plus');
                        icon.classList.add('fa-minus');
                    } else {
                        icon.classList.remove('fa-minus');
                        icon.classList.add('fa-plus');
                    }
                });
            });

            // Mobile menu toggle
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const mobileMenu = document.querySelector('.mobile-menu');

            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('active');

                // Change icon
                const icon = mobileMenuBtn.querySelector('i');
                if (icon.classList.contains('fa-bars')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });

            // Close mobile menu when clicking on links
            const mobileLinks = document.querySelectorAll('.mobile-menu a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.remove('active');
                    const icon = mobileMenuBtn.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk cek apakah elemen sudah visible dalam viewport
        function isElementInViewport(el) {
            const rect = el.getBoundingClientRect();
            return (
                rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 0.85
            );
        }

        // Terapkan kelas 'animate' pada elemen yang akan dianimasikan
        const sections = document.querySelectorAll('.about-section, .features, .how-it-works, .testimonials, .faq-section, .newsletter');
        const cards = document.querySelectorAll('.feature-card, .step, .testimonial-card, .faq-item, .stat-item');

        // Fungsi untuk menganimasikan elemen
        function animateOnScroll() {
            // Animasi untuk seksi
            sections.forEach(section => {
                if (isElementInViewport(section) && !section.classList.contains('active')) {
                    section.classList.add('active');

                    // Animasi untuk heading dalam seksi
                    const heading = section.querySelector('h2');
                    if (heading) {
                        heading.classList.add('active');
                    }

                    // Animasi untuk paragraf dalam seksi
                    const paragraphs = section.querySelectorAll('p');
                    paragraphs.forEach((p, index) => {
                        setTimeout(() => {
                            p.classList.add('active');
                        }, 100 * (index + 1));
                    });
                }
            });

            // Animasi untuk card dan elemen lainnya
            cards.forEach(card => {
                if (isElementInViewport(card) && !card.classList.contains('active')) {
                    card.classList.add('active');
                }
            });

            // Pastikan section utama terlihat
            const mainSection = document.querySelector('.section');
            if (mainSection) {
                mainSection.querySelectorAll('.animate').forEach(el => {
                    el.classList.add('active');
                });
                mainSection.querySelectorAll('.fade-in-left').forEach(el => {
                    el.classList.add('active');
                });
                mainSection.querySelectorAll('.fade-in-right').forEach(el => {
                    el.classList.add('active');
                });
            }
        }

        // Terapkan kelas untuk elemen yang akan dianimasikan
        function setupAnimations() {
            // HERO SECTION - Atur sebagai visible sejak awal
            const heroSection = document.querySelector('.section');
            if (heroSection) {
                heroSection.classList.add('initial-visible');

                // Hero text - tambahkan initial-visible
                const description = heroSection.querySelector('.description');
                if (description) {
                    description.classList.add('fade-in-left', 'initial-visible');
                    description.querySelectorAll('h1, p').forEach(el => {
                        el.classList.add('initial-visible');
                    });
                }

                // Hero image - tambahkan initial-visible
                const image = heroSection.querySelector('.image img');
                if (image) {
                    image.classList.add('fade-in-right', 'initial-visible');
                }
            }

            // Section headings
            document.querySelectorAll('.about-section h2, .features h2, .how-it-works h2, .testimonials h2, .faq-section h2, .newsletter h2').forEach(el => {
                el.classList.add('animate');
            });

            // ABOUT SECTION - Pastikan paragrafnya ada animasi
            const aboutSection = document.querySelector('.about-section');
            if (aboutSection) {
                // About text paragraphs - Penting untuk konten yang hilang
                const aboutText = aboutSection.querySelector('.about-text');
                if (aboutText) {
                    aboutText.querySelectorAll('p').forEach((p, index) => {
                        p.classList.add('fade-in-left', `delay-${index + 1}`);
                        p.style.display = 'block'; // Pastikan terlihat
                    });
                }
            }

            // Feature cards
            document.querySelectorAll('.feature-card').forEach((card, index) => {
                card.classList.add('animate', `delay-${index + 1}`);
            });

            // Steps
            document.querySelectorAll('.step').forEach((step, index) => {
                step.classList.add('animate', `delay-${index + 1}`);
            });

            // Testimonials
            document.querySelectorAll('.testimonial-card').forEach((card, index) => {
                card.classList.add('animate', `delay-${index + 1}`);
            });

            // FAQ items
            document.querySelectorAll('.faq-item').forEach((item, index) => {
                item.classList.add('animate', `delay-${index + 1}`);
            });

            // Stats
            document.querySelectorAll('.stat-item').forEach((stat, index) => {
                stat.classList.add('zoom-in', `delay-${index + 1}`);
            });
        }

        // Setup animations
        setupAnimations();

        // Run on load - PENTING: Pastikan hero section terlihat segera
        document.querySelector('.section')?.classList.add('active');
        document.querySelectorAll('.section .animate, .section .fade-in-left, .section .fade-in-right').forEach(el => {
            el.classList.add('active');
        });

        // Jalankan animasi untuk elemen yang sudah terlihat
        animateOnScroll();

        // Run on scroll
        window.addEventListener('scroll', animateOnScroll);
    });


    </script>
</body>

</html>
