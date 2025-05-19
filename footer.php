<?php

// footer.php
?>
<footer class="footer">
    <div class="footer-container">
        <div class="footer-grid">
            <div class="footer-section">
                <h3 class="footer-title">ShoeStore</h3>
                <p class="footer-text">Premium shoes for every step of your journey.</p>
            </div>
            <div class="footer-section">
                <h4 class="footer-subtitle">Shop</h4>
                <ul class="footer-list">
                    <li><a href="shop.php">All Products</a></li>
                    <li><a href="#">Featured</a></li>
                    <li><a href="#">New Arrivals</a></li>
                    <li><a href="#">Sale</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4 class="footer-subtitle">Support</h4>
                <ul class="footer-list">
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="#">FAQs</a></li>
                    <li><a href="#">Shipping</a></li>
                    <li><a href="#">Returns</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4 class="footer-subtitle">Connect</h4>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-pinterest"></i></a>
                </div>
                <p class="footer-text">Subscribe to our newsletter</p>
                <form action="subscribe.php" method="POST" class="subscribe-form">
                    <input type="email" name="email" placeholder="Your email" required>
                    <button type="submit">→</button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© <?php echo date('Y'); ?> ShoeStore. All rights reserved.</p>
            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer {
        background-color: #ffffff;
        border-top: 1px solid #e5e7eb;
        margin-top: 5rem;
        font-family: 'Montserrat', sans-serif;
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2.5rem 1.5rem;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    @media (min-width: 768px) {
        .footer-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .footer-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1rem;
    }

    .footer-subtitle {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1rem;
    }

    .footer-text {
        color: #4b5563;
        font-size: 0.95rem;
    }

    .footer-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-list li {
        margin-bottom: 0.5rem;
    }

    .footer-list a {
        color: #4b5563;
        text-decoration: none;
        font-size: 0.95rem;
        transition: color 0.3s ease;
    }

    .footer-list a:hover {
        color: #6b21a8;
    }

    .social-icons {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .social-icons a {
        color: #4b5563;
        font-size: 1.2rem;
        transition: color 0.3s ease;
    }

    .social-icons a:hover {
        color: #6b21a8;
    }

    .subscribe-form {
        display: flex;
        margin-top: 0.5rem;
    }

    .subscribe-form input {
        flex: 1;
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-right: none;
        border-radius: 0.375rem 0 0 0.375rem;
        outline: none;
    }

    .subscribe-form input:focus {
        box-shadow: 0 0 0 2px #9333ea;
        border-color: #9333ea;
    }

    .subscribe-form button {
        background-color: #6b21a8;
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 0 0.375rem 0.375rem 0;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .subscribe-form button:hover {
        background-color: #5b21b6;
    }

    .footer-bottom {
        border-top: 1px solid #e5e7eb;
        margin-top: 2rem;
        padding-top: 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    @media (min-width: 768px) {
        .footer-bottom {
            flex-direction: row;
        }
    }

    .footer-links {
        display: flex;
        gap: 1.5rem;
    }

    .footer-links a {
        color: #4b5563;
        font-size: 0.95rem;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer-links a:hover {
        color: #6b21a8;
    }
</style>
