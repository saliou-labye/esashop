<?php

declare(strict_types=1);

require __DIR__ . '/config/bootstrap.php';

// Minimal router for TP (MVC simplifié)
$route = $_GET['r'] ?? 'home';

// Helpers
function view(string $path, array $data = []): void
{
    extract($data);
    require __DIR__ . '/views/partials/header.php';
    require __DIR__ . '/views/' . $path;
    require __DIR__ . '/views/partials/footer.php';
}

// Routes
if ($route === 'home') {
    $catId = isset($_GET['cat']) ? (int) $_GET['cat'] : 0;

    $categories = $pdo->query('SELECT id, nom FROM categories ORDER BY nom')->fetchAll();

    if ($catId > 0) {
        $stmt = $pdo->prepare('SELECT * FROM products WHERE cat_id = :cat ORDER BY id DESC');
        $stmt->execute(['cat' => $catId]);
        $products = $stmt->fetchAll();
    } else {
        $products = $pdo->query('SELECT * FROM products ORDER BY id DESC')->fetchAll();
    }

    view('pages/home.php', compact('app', 'products', 'categories', 'catId'));
    exit;
}

if ($route === 'product') {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($id <= 0) {
        redirect('index.php');
    }
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch();
    if (!$product) {
        redirect('index.php');
    }
    view('pages/product.php', compact('app', 'product'));
    exit;
}

if ($route === 'login') {
    csrf_verify();
    $error = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['mot_de_passe'] ?? '');

        $stmt = $pdo->prepare('SELECT id, password, role FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, (string) $user['password'])) {
            if (($user['role'] ?? 'client') === 'admin') {
                $_SESSION['admin_id'] = (int) $user['id'];
                unset($_SESSION['user_id']);
                redirect('index.php?r=admin');
            }
            $_SESSION['user_id'] = (int) $user['id'];
            unset($_SESSION['admin_id']);
            redirect('index.php');
        }

        $error = "Email ou mot de passe incorrect.";
    }
    view('pages/login.php', compact('app', 'error'));
    exit;
}

if ($route === 'register') {
    csrf_verify();
    $error = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim((string) ($_POST['nom'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['mot_de_passe'] ?? '');
        $adresse = trim((string) ($_POST['adresse'] ?? ''));
        $telephone = trim((string) ($_POST['telephone'] ?? ''));

        if ($nom === '' || $email === '' || $password === '' || $adresse === '') {
            $error = "Veuillez remplir tous les champs obligatoires.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Adresse email invalide.";
        } elseif (mb_strlen($password) < 6) {
            $error = "Le mot de passe doit contenir au moins 6 caractères.";
        } else {
            try {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO users (nom, email, password, role, adresse, telephone) VALUES (:nom, :email, :pwd, 'client', :adr, :tel)");
                $stmt->execute([
                    'nom' => $nom,
                    'email' => $email,
                    'pwd' => $hash,
                    'adr' => $adresse,
                    'tel' => $telephone !== '' ? $telephone : null,
                ]);
                redirect('index.php?r=login');
            } catch (PDOException $e) {
                $error = "Cet email est déjà utilisé.";
            }
        }
    }
    view('pages/register.php', compact('app', 'error'));
    exit;
}

if ($route === 'logout') {
    session_destroy();
    redirect('index.php');
}

if ($route === 'cart') {
    // For now, reuse existing DB cart table when user logged in
    $userId = current_user_id();
    if (!$userId) {
        redirect('index.php?r=login');
    }

    csrf_verify();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $productId = (int) ($_POST['id'] ?? 0);
        $qty = max(1, (int) ($_POST['quantite'] ?? 1));
        $action = (string) ($_POST['action'] ?? 'add');

        if ($productId > 0) {
            $pstmt = $pdo->prepare('SELECT id, stock FROM products WHERE id = :id');
            $pstmt->execute(['id' => $productId]);
            $product = $pstmt->fetch();
            $availableStock = $product ? (int) ($product['stock'] ?? 0) : 0;

            if ($action === 'remove') {
                $stmt = $pdo->prepare('DELETE FROM cart WHERE user_id = :uid AND product_id = :pid');
                $stmt->execute(['uid' => $userId, 'pid' => $productId]);
            } elseif ($action === 'set') {
                $qty = min($qty, max(0, $availableStock));
                if ($qty <= 0) {
                    $stmt = $pdo->prepare('DELETE FROM cart WHERE user_id = :uid AND product_id = :pid');
                    $stmt->execute(['uid' => $userId, 'pid' => $productId]);
                    redirect('index.php?r=cart');
                }
                $stmt = $pdo->prepare('UPDATE cart SET quantite = :qty WHERE user_id = :uid AND product_id = :pid');
                $stmt->execute(['qty' => $qty, 'uid' => $userId, 'pid' => $productId]);
            } else {
                if ($availableStock <= 0) {
                    redirect('index.php?r=cart');
                }
                $qty = min($qty, $availableStock);
                // Note: avec emulate_prepares désactivé, ne pas réutiliser le même nom (:qty) deux fois dans la requête.
                $stmt = $pdo->prepare('INSERT INTO cart (user_id, product_id, quantite) VALUES (:uid, :pid, :qty) ON DUPLICATE KEY UPDATE quantite = quantite + :qty_add');
                $stmt->execute(['uid' => $userId, 'pid' => $productId, 'qty' => $qty, 'qty_add' => $qty]);
                // Cap quantity to stock
                $cap = $pdo->prepare('UPDATE cart c JOIN products p ON p.id = c.product_id SET c.quantite = LEAST(c.quantite, p.stock) WHERE c.user_id = :uid AND c.product_id = :pid');
                $cap->execute(['uid' => $userId, 'pid' => $productId]);
            }
        }
        redirect('index.php?r=cart');
    }

    $stmt = $pdo->prepare('SELECT p.*, c.quantite FROM products p JOIN cart c ON p.id = c.product_id WHERE c.user_id = :uid');
    $stmt->execute(['uid' => $userId]);
    $items = $stmt->fetchAll();

    $vatRate = (float) ($app['vat_rate'] ?? 0.18);
    $subtotal = 0.0;
    foreach ($items as $it) {
        $subtotal += ((float) $it['prix']) * ((int) $it['quantite']);
    }
    $vat = $subtotal * $vatRate;
    $total = $subtotal + $vat;

    view('pages/cart.php', compact('app', 'items', 'subtotal', 'vat', 'total', 'vatRate'));
    exit;
}

if ($route === 'checkout') {
    $userId = current_user_id();
    if (!$userId) {
        redirect('index.php?r=login');
    }

    $stmt = $pdo->prepare('SELECT p.*, c.quantite FROM products p JOIN cart c ON p.id = c.product_id WHERE c.user_id = :uid');
    $stmt->execute(['uid' => $userId]);
    $items = $stmt->fetchAll();
    if (!$items) {
        redirect('index.php?r=cart');
    }

    $vatRate = (float) ($app['vat_rate'] ?? 0.18);
    $subtotal = 0.0;
    foreach ($items as $it) {
        $subtotal += ((float) $it['prix']) * ((int) $it['quantite']);
    }
    $vat = $subtotal * $vatRate;
    $total = $subtotal + $vat;

    csrf_verify();
    $error = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $adresse = trim((string) ($_POST['adresse'] ?? ''));
        if ($adresse === '') {
            $error = "Adresse de livraison obligatoire.";
        } else {
            $pdo->beginTransaction();
            try {
                $stmt = $pdo->prepare("INSERT INTO orders (user_id, statut, adresse, total) VALUES (:user_id, 'En attente', :adr, :total)");
                $stmt->execute(['user_id' => $userId, 'adr' => $adresse, 'total' => $total]);
                $orderId = (int) $pdo->lastInsertId();

                $ins = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantite, prix_unit) VALUES (:oid, :pid, :q, :pu)');
                $lock = $pdo->prepare('SELECT stock FROM products WHERE id = :id FOR UPDATE');
                $dec = $pdo->prepare('UPDATE products SET stock = stock - :q WHERE id = :id');
                $del = $pdo->prepare('DELETE FROM cart WHERE user_id = :uid');
                foreach ($items as $it) {
                    $pid = (int) $it['id'];
                    $q = (int) $it['quantite'];

                    $lock->execute(['id' => $pid]);
                    $row = $lock->fetch();
                    $stock = $row ? (int) ($row['stock'] ?? 0) : 0;
                    if ($q <= 0 || $q > $stock) {
                        throw new RuntimeException('Stock insuffisant.');
                    }

                    $ins->execute([
                        'oid' => $orderId,
                        'pid' => $pid,
                        'q' => $q,
                        'pu' => (float) $it['prix'],
                    ]);
                    $dec->execute(['q' => $q, 'id' => $pid]);
                }
                $del->execute(['uid' => $userId]);
                $pdo->commit();
                redirect('index.php?r=confirmation&id=' . $orderId);
            } catch (Throwable $e) {
                $pdo->rollBack();
                $error = "Impossible de valider la commande (stock insuffisant ou erreur).";
            }
        }
    }

    view('pages/checkout.php', compact('app', 'items', 'subtotal', 'vat', 'total', 'vatRate', 'error'));
    exit;
}

if ($route === 'confirmation') {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    view('pages/confirmation.php', compact('app', 'id'));
    exit;
}

if ($route === 'orders') {
    $userId = current_user_id();
    if (!$userId) {
        redirect('index.php?r=login');
    }
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = :id ORDER BY id DESC');
    $stmt->execute(['id' => $userId]);
    $orders = $stmt->fetchAll();
    view('pages/orders.php', compact('app', 'orders'));
    exit;
}

if ($route === 'admin') {
    if (!current_admin_id()) {
        redirect('index.php?r=login');
    }
    view('pages/admin/dashboard.php', compact('app'));
    exit;
}

if ($route === 'admin_categories') {
    if (!current_admin_id()) {
        redirect('index.php?r=login');
    }

    csrf_verify();
    $flash = null;
    $error = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = (string) ($_POST['action'] ?? '');
        if ($action === 'add') {
            $nom = trim((string) ($_POST['nom'] ?? ''));
            $description = trim((string) ($_POST['description'] ?? ''));
            if ($nom === '') {
                $error = "Le nom de catégorie est obligatoire.";
            } else {
                try {
                    $stmt = $pdo->prepare('INSERT INTO categories (nom, description) VALUES (:nom, :description)');
                    $stmt->execute([
                        'nom' => $nom,
                        'description' => $description !== '' ? $description : null,
                    ]);
                    $flash = "Catégorie ajoutée.";
                } catch (PDOException $e) {
                    $error = "Impossible d'ajouter cette catégorie (nom déjà utilisé).";
                }
            }
        } elseif ($action === 'update') {
            $id = (int) ($_POST['id'] ?? 0);
            $nom = trim((string) ($_POST['nom'] ?? ''));
            $description = trim((string) ($_POST['description'] ?? ''));
            if ($id <= 0 || $nom === '') {
                $error = "Informations invalides pour modifier la catégorie.";
            } else {
                try {
                    $stmt = $pdo->prepare('UPDATE categories SET nom = :nom, description = :description WHERE id = :id');
                    $stmt->execute([
                        'id' => $id,
                        'nom' => $nom,
                        'description' => $description !== '' ? $description : null,
                    ]);
                    $flash = "Catégorie modifiée.";
                } catch (PDOException $e) {
                    $error = "Impossible de modifier cette catégorie (nom déjà utilisé).";
                }
            }
        } elseif ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id > 0) {
                try {
                    $stmt = $pdo->prepare('DELETE FROM categories WHERE id = :id');
                    $stmt->execute(['id' => $id]);
                    $flash = "Catégorie supprimée.";
                } catch (PDOException $e) {
                    $error = "Suppression impossible: cette catégorie contient encore des produits.";
                }
            }
        }
    }

    $categories = $pdo->query('SELECT id, nom, description FROM categories ORDER BY nom')->fetchAll();
    $editCategoryId = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
    $editCategory = null;
    foreach ($categories as $cat) {
        if ((int) $cat['id'] === $editCategoryId) {
            $editCategory = $cat;
            break;
        }
    }
    if ($editCategory === null && $categories) {
        $editCategory = $categories[0];
    }
    view('pages/admin/categories.php', compact('app', 'categories', 'flash', 'error', 'editCategory'));
    exit;
}

if ($route === 'admin_products') {
    if (!current_admin_id()) {
        redirect('index.php?r=login');
    }

    csrf_verify();
    $flash = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = (string) ($_POST['action'] ?? '');
        if ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id > 0) {
                $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
                $stmt->execute(['id' => $id]);
                $flash = "Produit supprimé.";
            }
        } elseif ($action === 'update') {
            $id = (int) ($_POST['id'] ?? 0);
            $nom = trim((string) ($_POST['nom'] ?? ''));
            $description = trim((string) ($_POST['description'] ?? ''));
            $prix = (float) ($_POST['prix'] ?? 0);
            $stock = (int) ($_POST['stock'] ?? 0);
            $categorieId = (int) ($_POST['categorie_id'] ?? 0);

            if ($id > 0 && $nom !== '' && $categorieId > 0) {
                $imagePath = (string) ($_POST['existing_image'] ?? '');
                if (!empty($_FILES['image']['tmp_name'])) {
                    if (!is_dir(__DIR__ . '/uploads')) {
                        @mkdir(__DIR__ . '/uploads', 0777, true);
                    }
                    $ext = pathinfo((string) $_FILES['image']['name'], PATHINFO_EXTENSION);
                    $name = 'p_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
                    $dest = __DIR__ . '/uploads/' . $name;
                    if (move_uploaded_file((string) $_FILES['image']['tmp_name'], $dest)) {
                        $imagePath = 'uploads/' . $name;
                    }
                }

                $stmt = $pdo->prepare('UPDATE products SET nom=:nom, description=:d, prix=:p, stock=:s, image=:i, cat_id=:c WHERE id=:id');
                $stmt->execute([
                    'nom' => $nom,
                    'd' => $description,
                    'p' => $prix,
                    's' => $stock,
                    'i' => $imagePath !== '' ? $imagePath : null,
                    'c' => $categorieId,
                    'id' => $id,
                ]);
                $flash = "Produit modifié.";
            }
        } elseif ($action === 'add') {
            $nom = trim((string) ($_POST['nom'] ?? ''));
            $description = trim((string) ($_POST['description'] ?? ''));
            $prix = (float) ($_POST['prix'] ?? 0);
            $stock = (int) ($_POST['stock'] ?? 0);
            $categorieId = (int) ($_POST['categorie_id'] ?? 0);

            $imagePath = null;
            if (!empty($_FILES['image']['tmp_name'])) {
                if (!is_dir(__DIR__ . '/uploads')) {
                    @mkdir(__DIR__ . '/uploads', 0777, true);
                }
                $ext = pathinfo((string) $_FILES['image']['name'], PATHINFO_EXTENSION);
                $name = 'p_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
                $dest = __DIR__ . '/uploads/' . $name;
                if (move_uploaded_file((string) $_FILES['image']['tmp_name'], $dest)) {
                    $imagePath = 'uploads/' . $name;
                }
            }

            $stmt = $pdo->prepare('INSERT INTO products (nom, description, prix, stock, image, cat_id) VALUES (:nom,:d,:p,:s,:i,:c)');
            $stmt->execute([
                'nom' => $nom,
                'd' => $description,
                'p' => $prix,
                's' => $stock,
                'i' => $imagePath,
                'c' => $categorieId,
            ]);
            $flash = "Produit ajouté.";
        }
    }

    $products = $pdo->query('SELECT * FROM products ORDER BY id DESC')->fetchAll();
    $categories = $pdo->query('SELECT id, nom FROM categories ORDER BY nom')->fetchAll();
    $editProductId = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
    $editProduct = null;
    foreach ($products as $prod) {
        if ((int) $prod['id'] === $editProductId) {
            $editProduct = $prod;
            break;
        }
    }
    if ($editProduct === null && $products) {
        $editProduct = $products[0];
    }
    view('pages/admin/products.php', compact('app', 'products', 'categories', 'flash', 'editProduct'));
    exit;
}

if ($route === 'admin_orders') {
    if (!current_admin_id()) {
        redirect('index.php?r=login');
    }

    csrf_verify();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int) ($_POST['id'] ?? 0);
        $statut = (string) ($_POST['statut'] ?? 'En attente');
        $allowedStatus = ['En attente', 'Expédiée', 'Livrée'];
        if ($id > 0 && in_array($statut, $allowedStatus, true)) {
            $stmt = $pdo->prepare('UPDATE orders SET statut = :s WHERE id = :id');
            $stmt->execute(['s' => $statut, 'id' => $id]);
        }
        redirect('index.php?r=admin_orders');
    }

    $query = "SELECT o.id, o.created_at as date_commande, o.statut, u.nom,
              o.total as total
              FROM orders o
              JOIN users u ON o.user_id = u.id
              ORDER BY o.id DESC";
    $orders = $pdo->query($query)->fetchAll();
    view('pages/admin/orders.php', compact('app', 'orders'));
    exit;
}

// fallback
redirect('index.php');
