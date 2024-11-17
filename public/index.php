<!-- run this command after navigating to public directory: php -S localhost:8000 -->


<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SQL Query Assistant</title>
    <link rel="stylesheet" href="styles.css" />
    <script src="script.js" defer></script>
</head>

<body>
    <header>
        <nav>
            <div class="logo">SQLMagic</div>
            <div class="nav-links">
                <a href="#home">Home</a>
                <a href="#about">About</a>
                <a href="#converter">Try It</a>
                <a href="logout.php">Logout</a>

            </div>
        </nav>
    </header>

    <section id="home" class="hero">
        <div class="hero-content">
            <h1>Transform Natural Language to SQL</h1>
            <p>Write queries in plain English and let AI do the magic</p>
            <a href="#converter" class="cta-button">Get Started</a>
        </div>
        <div class="hero-image">
            <div class="floating-cards">
                <div class="card">SELECT * FROM magic;</div>
                <div class="card">Natural Language → SQL</div>
                <div class="card">"Show me all the entries in magic table."</div>
            </div>
        </div>
        <div class="background-animation"></div>
    </section>

    <section id="about" class="info-section">
        <h2>Experience the Magic of Natural SQL</h2>
        <div class="features">
            <div class="feature">
                <h3>Natural Language Input</h3>
                <p>
                    Simply describe what you want to query in plain English. No SQL
                    knowledge required.
                </p>
            </div>
            <div class="feature">
                <h3>Intelligent Translation</h3>
                <p>
                    Our AI instantly converts your natural language into precise,
                    optimized SQL queries.
                </p>
            </div>
            <div class="feature">
                <h3>Real-time Results</h3>
                <p>
                    Get immediate results with syntax highlighting and formatted output.
                </p>
            </div>
        </div>
    </section>

    <section id="converter" class="query-section">
        <div class="query-container">
            <div class="input-area">
                <textarea
                    id="naturalQuery"
                    placeholder="Enter your query in natural language..."></textarea>
                <button id="convertBtn"><span>Convert to SQL</span></button>
            </div>
            <div class="output-area">
                <div class="sql-output">
                    <h3>SQL Query</h3>
                    <pre id="sqlQuery"></pre>
                </div>
                <div class="results">
                    <h3>Results</h3>
                    <div id="queryResults"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Client-side script -->
    <script type="module" src="script.js"></script>
</body>

</html>