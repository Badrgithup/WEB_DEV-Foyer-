<?php
session_start();
require_once 'db_connect.php';

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$action_message = '';

try {
    // Handle logout
    if (isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }


    if (isset($_POST['add_student'])) {
        $name = filter_var($_POST['student_name'], FILTER_SANITIZE_STRING);
        $room = filter_var($_POST['student_room'], FILTER_SANITIZE_STRING);
        $contact = filter_var($_POST['student_contact'], FILTER_SANITIZE_STRING);

        if (!empty($name) && !empty($room) && !empty($contact)) {
            $stmt = $pdo->prepare("INSERT INTO students (name, room, contact) VALUES (?, ?, ?)");
            $stmt->execute([$name, $room, $contact]);
            $action_message = "Étudiant ajouté avec succès.";
        } else {
            $action_message = "Tous les champs sont requis.";
        }
    }

    if (isset($_POST['edit_student'])) {
        $id = filter_var($_POST['student_id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($_POST['student_name'], FILTER_SANITIZE_STRING);
        $room = filter_var($_POST['student_room'], FILTER_SANITIZE_STRING);
        $contact = filter_var($_POST['student_contact'], FILTER_SANITIZE_STRING);

        if (!empty($id) && !empty($name) && !empty($room) && !empty($contact)) {
            $stmt = $pdo->prepare("UPDATE students SET name = ?, room = ?, contact = ? WHERE id = ?");
            $stmt->execute([$name, $room, $contact, $id]);
            $action_message = "Étudiant modifié avec succès.";
        }
    }

    if (isset($_POST['delete_student'])) {
        $id = filter_var($_POST['student_id'], FILTER_SANITIZE_NUMBER_INT);
        if (!empty($id)) {
            $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
            $stmt->execute([$id]);
            $action_message = "Étudiant supprimé avec succès.";
        }
    }

    if (isset($_POST['add_payment'])) {
        $student_id = filter_var($_POST['payment_resident'], FILTER_SANITIZE_NUMBER_INT);
        $amount = filter_var($_POST['payment_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $due_date = filter_var($_POST['payment_due_date'], FILTER_SANITIZE_STRING);
        $status = filter_var($_POST['payment_status'], FILTER_SANITIZE_STRING);

        if (!empty($student_id) && !empty($amount) && !empty($due_date) && !empty($status)) {
            $stmt = $pdo->prepare("INSERT INTO payments (student_id, amount, due_date, status) VALUES (?, ?, ?, ?)");
            $stmt->execute([$student_id, $amount, $due_date, $status]);
            $action_message = "Paiement ajouté avec succès.";
        }
    }

    if (isset($_POST['edit_payment'])) {
        $id = filter_var($_POST['payment_id'], FILTER_SANITIZE_NUMBER_INT);
        $student_id = filter_var($_POST['payment_resident'], FILTER_SANITIZE_NUMBER_INT);
        $amount = filter_var($_POST['payment_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $due_date = filter_var($_POST['payment_due_date'], FILTER_SANITIZE_STRING);
        $status = filter_var($_POST['payment_status'], FILTER_SANITIZE_STRING);

        if (!empty($id)) {
            $stmt = $pdo->prepare("UPDATE payments SET student_id = ?, amount = ?, due_date = ?, status = ? WHERE id = ?");
            $stmt->execute([$student_id, $amount, $due_date, $status, $id]);
            $action_message = "Paiement modifié avec succès.";
        }
    }

    if (isset($_POST['delete_payment'])) {
        $id = filter_var($_POST['payment_id'], FILTER_SANITIZE_NUMBER_INT);
        if (!empty($id)) {
            $stmt = $pdo->prepare("DELETE FROM payments WHERE id = ?");
            $stmt->execute([$id]);
            $action_message = "Paiement supprimé avec succès.";
        }
    }

    if (isset($_POST['add_announcement'])) {
        $title = filter_var($_POST['announcement_title'], FILTER_SANITIZE_STRING);
        $content = filter_var($_POST['announcement_content'], FILTER_SANITIZE_STRING);

        if (!empty($title) && !empty($content)) {
            $stmt = $pdo->prepare("INSERT INTO announcements (title, content) VALUES (?, ?)");
            $stmt->execute([$title, $content]);
            $action_message = "Annonce publiée avec succès.";
        }
    }

    if (isset($_POST['edit_announcement'])) {
        $id = filter_var($_POST['announcement_id'], FILTER_SANITIZE_NUMBER_INT);
        $title = filter_var($_POST['announcement_title'], FILTER_SANITIZE_STRING);
        $content = filter_var($_POST['announcement_content'], FILTER_SANITIZE_STRING);

        if (!empty($id)) {
            $stmt = $pdo->prepare("UPDATE announcements SET title = ?, content = ? WHERE id = ?");
            $stmt->execute([$title, $content, $id]);
            $action_message = "Annonce modifiée avec succès.";
        }
    }

    if (isset($_POST['delete_announcement'])) {
        $id = filter_var($_POST['announcement_id'], FILTER_SANITIZE_NUMBER_INT);
        if (!empty($id)) {
            $stmt = $pdo->prepare("DELETE FROM announcements WHERE id = ?");
            $stmt->execute([$id]);
            $action_message = "Annonce supprimée avec succès.";
        }
    }

    if (isset($_POST['update_request_status'])) {
        $id = filter_var($_POST['request_id'], FILTER_SANITIZE_NUMBER_INT);
        $status = filter_var($_POST['request_status'], FILTER_SANITIZE_STRING);
        if (!empty($id) && in_array($status, ['en attente', 'traitée', 'rejetée'])) {
            $stmt = $pdo->prepare("UPDATE requests SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$status, $id]);
            $action_message = "Statut de la requête mis à jour avec succès.";
        } else {
            $action_message = "Erreur: Statut ou ID invalide.";
        }
    }

    if (isset($_POST['delete_request'])) {
        $id = filter_var($_POST['request_id'], FILTER_SANITIZE_NUMBER_INT);
        if (!empty($id)) {
            $stmt = $pdo->prepare("DELETE FROM requests WHERE id = ?");
            $stmt->execute([$id]);
            $action_message = "Requête supprimée avec succès.";
        }
    }

    $student_count = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
    $pending_payments = $pdo->query("SELECT COUNT(*) FROM payments WHERE status = 'en attente'")->fetchColumn();
    $recent_notices = $pdo->query("SELECT COUNT(*) FROM announcements")->fetchColumn();
    $pending_requests = $pdo->query("SELECT COUNT(*) FROM requests WHERE status = 'en attente'")->fetchColumn();

    $stmt = $pdo->prepare("SELECT id, name, room, contact FROM students");
    $stmt->execute();
    $students = $stmt->fetchAll();

    $stmt = $pdo->prepare("SELECT p.id, s.name, p.amount, p.due_date, p.status
                           FROM payments p JOIN students s ON p.student_id = s.id");
    $stmt->execute();
    $payments = $stmt->fetchAll();

    $stmt = $pdo->prepare("SELECT id, title, content FROM announcements");
    $stmt->execute();
    $announcements = $stmt->fetchAll();

    $stmt = $pdo->prepare("SELECT r.id, s.name, r.type, r.message, r.status
                           FROM requests r JOIN students s ON r.student_id = s.id");
    $stmt->execute();
    $requests = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error in admin_dashboard: " . $e->getMessage());
    $action_message = "Erreur: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Foyer Ibn Zohr - Espace Administrateur</title>
    <link rel="stylesheet" href="admin_dashboard1.css" />
</head>
<body>
<script>
    fetch('check_session_admin.php')
        .then(response => response.json())
        .then(data => {
            if (!data.logged_in) {
                window.location.href = 'index.php';
            }
        });
</script>

<div class="dashboard-container">
    <header class="admin-header">
        <h1>Espace Administrateur - Foyer Ibn Zohr</h1>
        <form method="POST">
            <input type="hidden" name="logout" value="1">
            <button type="submit" class="logout-btn">Déconnexion</button>
        </form>
    </header>

    <nav class="admin-nav">
        <ul>
            <li><a href="#" class="nav-link active" data-section="dashboard">Tableau de bord</a></li>
            <li><a href="#" class="nav-link" data-section="students">Étudiants</a></li>
            <li><a href="#" class="nav-link" data-section="payments">Paiements</a></li>
            <li><a href="#" class="nav-link" data-section="announcements">Annonces</a></li>
            <li><a href="#" class="nav-link" data-section="requests">demande</a></li>
        </ul>
    </nav>

    <main class="admin-main">
        <?php if ($action_message): ?>
            <div class="action-message"><?php echo htmlspecialchars($action_message); ?></div>
        <?php endif; ?>

        <section id="dashboard-section" class="content-section active">
            <h2>Tableau de bord</h2>
            <div class="stats-container">
                <div class="stat-card">
                    <h3>Étudiants</h3>
                    <p><?php echo $student_count; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Paiements en attente</h3>
                    <p><?php echo $pending_payments; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Annonces</h3>
                    <p><?php echo $recent_notices; ?></p>
                </div>
                <div class="stat-card">
                    <h3>demande en attente</h3>
                    <p><?php echo $pending_requests; ?></p>
                </div>
            </div>
        </section>

        <section id="students-section" class="content-section">
            <h2>Gestion des Étudiants</h2>
            <div class="action-bar">
                <button id="add-resident-btn" class="action-btn">Ajouter un Étudiant</button>
                <input type="text" id="resident-search" placeholder="Rechercher...">
            </div>

            <form method="POST" id="add-student-form" style="display:none;">
                <div class="form-group">
                    <label for="student_name">Nom complet</label>
                    <input type="text" id="student_name" name="student_name" required>
                </div>
                <div class="form-group">
                    <label for="student_room">Chambre</label>
                    <input type="text" id="student_room" name="student_room" required>
                </div>
                <div class="form-group">
                    <label for="student_contact">Contact</label>
                    <input type="text" id="student_contact" name="student_contact" required>
                </div>
                <button type="submit" name="add_student" class="action-btn">Enregistrer</button>
                <button type="button" id="cancel-student" class="action-btn secondary">Annuler</button>
            </form>

            <table class="data-table" id="residents-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Chambre</th>
                    <th>Contact</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo $student['id']; ?></td>
                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                        <td><?php echo htmlspecialchars($student['room'] ?? 'Non assignée'); ?></td>
                        <td><?php echo htmlspecialchars($student['contact'] ?? 'N/A'); ?></td>
                        <td>
                            <button class="action-btn small edit-resident" data-id="<?php echo $student['id']; ?>">Éditer</button>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Voulez-vous vraiment supprimer cet étudiant ?');">
                                <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                <button type="submit" name="delete_student" class="action-btn small secondary">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    <tr id="edit-student-form-<?php echo $student['id']; ?>" style="display:none;">
                        <td colspan="5">
                            <form method="POST">
                                <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                <div class="form-group">
                                    <label for="edit_student_name_<?php echo $student['id']; ?>">Nom complet</label>
                                    <input type="text" id="edit_student_name_<?php echo $student['id']; ?>" name="student_name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_student_room_<?php echo $student['id']; ?>">Chambre</label>
                                    <input type="text" id="edit_student_room_<?php echo $student['id']; ?>" name="student_room" value="<?php echo htmlspecialchars($student['room'] ?? ''); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_student_contact_<?php echo $student['id']; ?>">Contact</label>
                                    <input type="text" id="edit_student_contact_<?php echo $student['id']; ?>" name="student_contact" value="<?php echo htmlspecialchars($student['contact'] ?? ''); ?>" required>
                                </div>
                                <button type="submit" name="edit_student" class="action-btn">Enregistrer</button>
                                <button type="button" class="action-btn secondary cancel-edit-student" data-id="<?php echo $student['id']; ?>">Annuler</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section id="payments-section" class="content-section">
            <h2>Gestion des Paiements</h2>
            <div class="action-bar">
                <button id="add-payment-btn" class="action-btn">Ajouter un Paiement</button>
                <select id="payment-filter">
                    <option value="all">Tous</option>
                    <option value="en attente">En attente</option>
                    <option value="payé">Payé</option>
                    <option value="impayé">Impayé</option>
                </select>
                <input type="text" id="payment-search" placeholder="Rechercher...">
            </div>

            <form method="POST" id="add-payment-form" style="display:none;">
                <div class="form-group">
                    <label for="payment_resident">Étudiant</label>
                    <select id="payment_resident" name="payment_resident" required>
                        <?php foreach ($students as $student): ?>
                            <option value="<?php echo $student['id']; ?>"><?php echo htmlspecialchars($student['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="payment_amount">Montant (TND)</label>
                    <input type="number" id="payment_amount" name="payment_amount" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="payment_due_date">Date limite</label>
                    <input type="date" id="payment_due_date" name="payment_due_date" required>
                </div>
                <div class="form-group">
                    <label for="payment_status">Statut</label>
                    <select id="payment_status" name="payment_status" required>
                        <option value="en attente">En attente</option>
                        <option value="payé">Payé</option>
                        <option value="impayé">Impayé</option>
                    </select>
                </div>
                <button type="submit" name="add_payment" class="action-btn">Enregistrer</button>
                <button type="button" id="cancel-payment" class="action-btn secondary">Annuler</button>
            </form>

            <!-- Payments Table -->
            <table class="data-table" id="payments-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Étudiant</th>
                    <th>Montant</th>
                    <th>Date limite</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td><?php echo $payment['id']; ?></td>
                        <td><?php echo htmlspecialchars($payment['name']); ?></td>
                        <td><?php echo htmlspecialchars($payment['amount']); ?> TND</td>
                        <td><?php echo htmlspecialchars($payment['due_date']); ?></td>
                        <td><?php echo htmlspecialchars($payment['status']); ?></td>
                        <td>
                            <button class="action-btn small edit-payment" data-id="<?php echo $payment['id']; ?>">Éditer</button>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Voulez-vous vraiment supprimer ce paiement ?');">
                                <input type="hidden" name="payment_id" value="<?php echo $payment['id']; ?>">
                                <button type="submit" name="delete_payment" class="action-btn small secondary">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    <tr id="edit-payment-form-<?php echo $payment['id']; ?>" style="display:none;">
                        <td colspan="6">
                            <form method="POST">
                                <input type="hidden" name="payment_id" value="<?php echo $payment['id']; ?>">
                                <div class="form-group">
                                    <label for="edit_payment_resident_<?php echo $payment['id']; ?>">Étudiant</label>
                                    <select id="edit_payment_resident_<?php echo $payment['id']; ?>" name="payment_resident" required>
                                        <?php foreach ($students as $student): ?>
                                            <option value="<?php echo $student['id']; ?>" <?php echo ($student['name'] == $payment['name']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($student['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit_payment_amount_<?php echo $payment['id']; ?>">Montant (TND)</label>
                                    <input type="number" id="edit_payment_amount_<?php echo $payment['id']; ?>" name="payment_amount" step="0.01" value="<?php echo htmlspecialchars($payment['amount']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_payment_due_date_<?php echo $payment['id']; ?>">Date limite</label>
                                    <input type="date" id="edit_payment_due_date_<?php echo $payment['id']; ?>" name="payment_due_date" value="<?php echo htmlspecialchars($payment['due_date']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_payment_status_<?php echo $payment['id']; ?>">Statut</label>
                                    <select id="edit_payment_status_<?php echo $payment['id']; ?>" name="payment_status" required>
                                        <option value="en attente" <?php echo ($payment['status'] == 'en attente') ? 'selected' : ''; ?>>En attente</option>
                                        <option value="payé" <?php echo ($payment['status'] == 'payé') ? 'selected' : ''; ?>>Payé</option>
                                        <option value="impayé" <?php echo ($payment['status'] == 'impayé') ? 'selected' : ''; ?>>Impayé</option>
                                    </select>
                                </div>
                                <button type="submit" name="edit_payment" class="action-btn">Enregistrer</button>
                                <button type="button" class="action-btn secondary cancel-edit-payment" data-id="<?php echo $payment['id']; ?>">Annuler</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section id="announcements-section" class="content-section">
            <h2>Gestion des Annonces</h2>
            <div class="action-bar">
                <button id="new-announcement-btn" class="action-btn">Nouvelle Annonce</button>
            </div>

            <form method="POST" id="add-announcement-form" style="display:none;">
                <div class="form-group">
                    <label for="announcement_title">Titre</label>
                    <input type="text" id="announcement_title" name="announcement_title" required>
                </div>
                <div class="form-group">
                    <label for="announcement_content">Contenu</label>
                    <textarea id="announcement_content" name="announcement_content" rows="4" required></textarea>
                </div>
                <button type="submit" name="add_announcement" class="action-btn">Publier</button>
                <button type="button" id="cancel-announcement" class="action-btn secondary">Annuler</button>
            </form>

            <div class="announcements-list">
                <?php foreach ($announcements as $announcement): ?>
                    <div class="announcement-card">
                        <h3><?php echo htmlspecialchars($announcement['title']); ?></h3>
                        <p><?php echo htmlspecialchars($announcement['content']); ?></p>
                        <div class="announcement-actions">
                            <button class="action-btn small edit-announcement" data-id="<?php echo $announcement['id']; ?>">Éditer</button>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Voulez-vous vraiment supprimer cette annonce ?');">
                                <input type="hidden" name="announcement_id" value="<?php echo $announcement['id']; ?>">
                                <button type="submit" name="delete_announcement" class="action-btn small secondary">Supprimer</button>
                            </form>
                        </div>
                    </div>
                    <div id="edit-announcement-form-<?php echo $announcement['id']; ?>" style="display:none;">
                        <form method="POST">
                            <input type="hidden" name="announcement_id" value="<?php echo $announcement['id']; ?>">
                            <div class="form-group">
                                <label for="edit_announcement_title_<?php echo $announcement['id']; ?>">Titre</label>
                                <input type="text" id="edit_announcement_title_<?php echo $announcement['id']; ?>" name="announcement_title" value="<?php echo htmlspecialchars($announcement['title']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_announcement_content_<?php echo $announcement['id']; ?>">Contenu</label>
                                <textarea id="edit_announcement_content_<?php echo $announcement['id']; ?>" name="announcement_content" rows="4" required><?php echo htmlspecialchars($announcement['content']); ?></textarea>
                            </div>
                            <button type="submit" name="edit_announcement" class="action-btn">Enregistrer</button>
                            <button type="button" class="action-btn secondary cancel-edit-announcement" data-id="<?php echo $announcement['id']; ?>">Annuler</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="requests-section" class="content-section">
            <h2>Gestion des Requêtes</h2>
            <div class="action-bar">
                <input type="text" id="request-search" placeholder="Rechercher...">
            </div>

            <table class="data-table" id="requests-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Étudiant</th>
                    <th>Type</th>
                    <th>Message</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><?php echo $request['id']; ?></td>
                        <td><?php echo htmlspecialchars($request['name']); ?></td>
                        <td><?php echo htmlspecialchars($request['type']); ?></td>
                        <td><?php echo htmlspecialchars($request['message']); ?></td>
                        <td><?php echo htmlspecialchars($request['status']); ?></td>
                        <td>
                            <button class="action-btn small edit-request" data-id="<?php echo $request['id']; ?>">Modifier Statut</button>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Voulez-vous vraiment supprimer cette requête ?');">
                                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                <button type="submit" name="delete_request" class="action-btn small secondary">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    <tr id="edit-request-form-<?php echo $request['id']; ?>" class="edit-request-form" style="display:none;">
                        <td colspan="6">
                            <form method="POST" class="request-status-form">
                                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                <div class="form-group">
                                    <label for="request_status_<?php echo $request['id']; ?>">Statut</label>
                                    <select id="request_status_<?php echo $request['id']; ?>" name="request_status" required>
                                        <option value="en attente" <?php echo ($request['status'] == 'en attente') ? 'selected' : ''; ?>>En attente</option>
                                        <option value="traitée" <?php echo ($request['status'] == 'traitée') ? 'selected' : ''; ?>>Traitée</option>
                                        <option value="rejetée" <?php echo ($request['status'] == 'rejetée') ? 'selected' : ''; ?>>Rejetée</option>
                                    </select>
                                </div>
                                <button type="submit" name="update_request_status" class="action-btn">Enregistrer</button>
                                <button type="button" class="action-btn secondary cancel-edit-request" data-id="<?php echo $request['id']; ?>">Annuler</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>
<script src="admin_dashboard.js"></script>
</body>
</html>