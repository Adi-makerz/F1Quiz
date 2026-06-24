<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1Quiz - Test Your Formula 1 Knowledge</title>
    <link rel="stylesheet" href="css/landing_page.css?v=1.1">
    <link rel="icon" type="image/x-icon" href="media/logo.ico">
    <!-- Google Fonts for a racing/sporty look -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
</head>
<body>

    <!-- Navigation Bar -->
    <header class="navbar">
        <div class="logo">F1 <span>ULTIMATE</span> QUIZ</div>
        <nav>
            <a href="login.php" class="btn btn-secondary">Login</a>
            <a href="register.php" class="btn btn-primary">Sign Up</a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <!-- Background Video Loop -->
        <video autoplay loop muted playsinline class="hero-video">
            <source src="media/f1-bg.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <!-- Dark tint to ensure text remains readable -->
        <div class="hero-overlay"></div>
        
        <!-- Content wrapper -->
        <div class="hero-content">
            <span class="badge">Lights Out and Away We Go!</span>
            <h1>Are You the Ultimate <br><span class="highlight">Formula 1</span> Fan?</h1>
            <p>Put your F1 knowledge to the ultimate test. Outsmart the grid, climb the global leaderboard, and prove you belong on the podium.</p>
            <div class="hero-actions">
                <a href="login.php" class="btn btn-large btn-primary">Start Racing Now</a>
                <a href="#features" class="btn btn-large btn-outline">Learn More</a>
            </div>
        </div>
    </section>

    <!-- Stats / Social Proof Banner -->
    <section class="stats-banner">
        <div class="stat-item">
            <h3>500+</h3>
            <p>Trivia Questions</p>
        </div>
        <div class="stat-item">
            <h3>10k+</h3>
            <p>Active F1 Fans</p>
        </div>
        <div class="stat-item">
            <h3>20</h3>
            <p>Global Grand Prix Tracks Covered</p>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <h2>Engineered for True Tifosi & Fans</h2>
        <p class="section-subtitle">Experience a dynamic quiz platform optimized for speed and competition.</p>
        
        <div class="feature-grid">
            <div class="feature-card">
                <div class="icon">🏎️</div>
                <h3>Real-Time Questions</h3>
                <p>Dynamic questions fetched straight from our database covering historic eras to the 2026 season regulations.</p>
            </div>
            <div class="feature-card">
                <div class="icon">⏱️</div>
                <h3>Beat the Clock</h3>
                <p>Formula 1 is all about milliseconds. Answer quickly to maximize your score and dominate the leaderboard.</p>
            </div>
            <div class="feature-card">
                <div class="icon">🏆</div>
                <h3>Personal Driver Profile</h3>
                <p>Track your race history, review previous scores, and watch your stats improve over time.</p>
            </div>
        </div>
    </section>

    <!-- Footer & Admin Port -->
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2026 F1Quiz App. All rights reserved.</p>
            <div class="footer-links">
                <a href="admin-login.php" class="admin-link">🔒 Admin Portal</a>
            </div>
        </div>
    </footer>

</body>
</html>