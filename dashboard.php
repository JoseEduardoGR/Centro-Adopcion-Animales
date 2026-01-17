<?php
require_once 'config/session.php';
require_once 'config/database.php';

requireLogin();

$database = new Database();
$db = $database->getConnection();

// Get user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get user's adoptions
$query = "SELECT a.*, an.name as animal_name, an.species, an.breed 
          FROM adoptions a 
          JOIN animals an ON a.animal_id = an.id 
          WHERE a.user_id = ? 
          ORDER BY a.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$adoptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario - Centro de Adopción</title>
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
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link active">Panel</a>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link">Cerrar Sesión</a>
                    </li>
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
        <div class="container">
            <div class="dashboard-header">
                <h1>Bienvenido, <?php echo htmlspecialchars($user['full_name']); ?></h1>
                <p>Gestiona tu perfil y solicitudes de adopción</p>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <h2>Mi Perfil</h2>
                    <div class="profile-info">
                        <p><strong>Usuario:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
                        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($user['phone'] ?: 'No especificado'); ?></p>
                        <p><strong>Dirección:</strong> <?php echo htmlspecialchars($user['address'] ?: 'No especificada'); ?></p>
                        <p><strong>Miembro desde:</strong> <?php echo date('d/m/Y', strtotime($user['created_at'])); ?></p>
                    </div>
                    <button class="btn btn-secondary" onclick="openEditProfile()">Editar Perfil</button>
                </div>

                <div class="dashboard-card">
                    <h2>Mis Solicitudes de Adopción</h2>
                    <?php if (empty($adoptions)): ?>
                        <p>No tienes solicitudes de adopción aún.</p>
                        <a href="index.php" class="btn btn-primary">Ver Animales Disponibles</a>
                    <?php else: ?>
                        <div class="adoptions-list">
                            <?php foreach ($adoptions as $adoption): ?>
                                <div class="adoption-item">
                                    <div class="adoption-info">
                                        <h4><?php echo htmlspecialchars($adoption['animal_name']); ?></h4>
                                        <p><?php echo htmlspecialchars($adoption['species'] . ' - ' . $adoption['breed']); ?></p>
                                        <p><strong>Estado:</strong> 
                                            <span class="status status-<?php echo strtolower($adoption['status']); ?>">
                                                <?php echo htmlspecialchars($adoption['status']); ?>
                                            </span>
                                        </p>
                                        <p><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($adoption['created_at'])); ?></p>
                                    </div>
                                    <div class="adoption-actions">
                                        <?php if ($adoption['status'] == 'Pending'): ?>
                                            <button class="btn btn-danger btn-sm" 
                                                    onclick="cancelAdoption(<?php echo $adoption['id']; ?>)">
                                                Cancelar
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Editar Perfil</h3>
                <span class="close" onclick="closeEditProfile()">&times;</span>
            </div>
            <form id="editProfileForm" method="POST" action="update_profile.php">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <label for="edit_full_name">Nombre Completo</label>
                    <input type="text" id="edit_full_name" name="full_name" 
                           value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_email">Email</label>
                    <input type="email" id="edit_email" name="email" 
                           value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_phone">Teléfono</label>
                    <input type="tel" id="edit_phone" name="phone" 
                           value="<?php echo htmlspecialchars($user['phone']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="edit_address">Dirección</label>
                    <textarea id="edit_address" name="address" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeEditProfile()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
