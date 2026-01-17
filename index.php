<?php
require_once 'config/session.php';
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get available animals
$query = "SELECT * FROM animals WHERE adoption_status = 'Available' ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$animals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centro de Adopción de Animales</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <h2>🐾 Centro de Adopción</h2>
                </div>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">Inicio</a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a href="dashboard.php" class="nav-link">Panel</a>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php" class="nav-link">Cerrar Sesión</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a href="login.php" class="nav-link">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a href="register.php" class="nav-link">Registrarse</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </nav>
    </header>

    <main class="main-content">
        <section class="hero">
            <div class="hero-content">
                <h1>Encuentra tu Compañero Perfecto</h1>
                <p>Ayudamos a conectar animales necesitados con familias amorosas</p>
                <?php if (!isLoggedIn()): ?>
                    <a href="register.php" class="btn btn-primary">Comenzar Adopción</a>
                <?php endif; ?>
            </div>
        </section>

        <section class="animals-section">
            <div class="container">
                <h2 class="section-title">Animales Disponibles para Adopción</h2>
                <p class="section-subtitle">Encuentra tu compañero perfecto entre nuestros adorables animales que buscan un hogar lleno de amor</p>
                <div class="animals-grid">
                    <?php foreach ($animals as $animal): ?>
                        <div class="animal-card">
                            <img src="<?php echo htmlspecialchars($animal['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($animal['name']); ?>" 
                                 class="animal-image">
                            <div class="animal-info">
                                <h3><?php echo htmlspecialchars($animal['name']); ?></h3>
                                <p class="animal-breed"><?php echo htmlspecialchars($animal['species'] . ' - ' . $animal['breed']); ?></p>
                                <p class="animal-details">
                                    <span class="animal-detail-tag"><?php echo htmlspecialchars($animal['age']); ?> años</span>
                                    <span class="animal-detail-tag"><?php echo htmlspecialchars($animal['gender']); ?></span>
                                    <span class="animal-detail-tag"><?php echo htmlspecialchars($animal['size']); ?></span>
                                </p>
                                <p class="animal-description"><?php echo htmlspecialchars($animal['description']); ?></p>
                                <?php if (isLoggedIn()): ?>
                                    <button class="btn btn-primary" onclick="adoptAnimal(<?php echo $animal['id']; ?>)">
                                        Solicitar Adopción
                                    </button>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-secondary">Iniciar Sesión para Adoptar</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Centro de Adopción de Animales. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>
