<?php
// Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");

// Permitir los métodos GET y POST
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");

// Permitir los encabezados que se utilizan comúnmente en las solicitudes
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Permitir que las solicitudes incluyan cookies
header("Access-Control-Allow-Credentials: true");

// Configuración de la conexión a la base de datos
$host = '172.17.0.3';
$port = '5432';
$dbname = 'wallashop';
$user = 'wallashop';
$password = 'wallashop';

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Obtener método HTTP
$method = $_SERVER['REQUEST_METHOD'];
$path = isset($_GET['path']) ? $_GET['path'] : '';

// Listar todos los productos activos
if ($method == 'GET' && $path == 'products') {
    $result = pg_query($conn, "SELECT * FROM product WHERE status = 'active'");
    $products = pg_fetch_all($result);
    echo json_encode($products ?: []);
}

// Listar todos los usuarios activos
elseif ($method == 'GET' && $path == 'users') {
    $result = pg_query($conn, "SELECT * FROM person WHERE status = 'active'");
    $users = pg_fetch_all($result);
    echo json_encode($users ?: []);
}

// Obtener información de un producto específico por ID
elseif ($method == 'GET' && $path == 'product') {
    $productId = $_GET['id'];
    $query = "SELECT p.*, o.email AS ownerEmail, o.personname AS ownerName FROM product p JOIN person o ON p.ownerid = o.id WHERE p.id = '$productId' AND p.status = 'active'";
    $result = pg_query($conn, $query);
    $product = pg_fetch_assoc($result);
    if ($product) {
        echo json_encode($product);
    } else {
        echo json_encode(['status' => 'Product not found']);
    }
}

// Obtener productos filtrados por categoría
elseif ($method == 'GET' && $path == 'products' && isset($_GET['category'])) {
    $category = $_GET['category'];
    $query = "SELECT * FROM product WHERE category = '$category' AND status = 'active'";
    $result = pg_query($conn, $query);
    $products = pg_fetch_all($result);
    echo json_encode($products ?: []);
}

// Listar todas las categorías de productos activos
elseif ($method == 'GET' && $path == 'categories') {
    $query = "SELECT DISTINCT category FROM product WHERE status = 'active'";
    $result = pg_query($conn, $query);
    $categories = pg_fetch_all($result);
    echo json_encode($categories ?: []);
}

// Añadir un producto
elseif ($method == 'POST' && $path == 'product') {
    $data = json_decode(file_get_contents('php://input'), true);
    $description = $data['description'];
    $location = $data['location'];
    $price = $data['price'];
    $images = $data['images'];
    $ownerId = $data['ownerId'];
    $productName = $data['productName'];
    $category = $data['category'];

    $query = "INSERT INTO product (description, location, price, images, ownerid, productname, category, status) VALUES ('$description', '$location', '$price', '$images', '$ownerId', '$productName', '$category', 'active')";
    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'Product added']);
    } else {
        echo json_encode(['status' => 'Error adding product']);
    }
}

// Actualizar un producto
elseif ($method == 'PUT' && $path == 'product') {
    $data = json_decode(file_get_contents('php://input'), true);
    $productId = $data['id'];
    $description = $data['description'];
    $location = $data['location'];
    $price = $data['price'];
    $images = $data['images'];
    $productName = $data['productName'];
    $category = $data['category'];

    $query = "UPDATE product SET description = '$description', location = '$location', price = '$price', images = '$images', productname = '$productName', category = '$category' WHERE id = '$productId'";
    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'Product updated']);
    } else {
        echo json_encode(['status' => 'Error updating product']);
    }
}

// Marcar un producto como inactivo en lugar de borrarlo
elseif ($method == 'DELETE' && $path == 'product') {
    $productId = $_GET['id'];

    $query = "UPDATE product SET status = 'inactive' WHERE id = '$productId'";
    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'Product marked as inactive']);
    } else {
        echo json_encode(['status' => 'Error marking product as inactive']);
    }
}

// Crear usuario
elseif ($method == 'POST' && $path == 'register') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'];
    $password = $data['password'];
    $location = $data['location'];
    $profilePicture = $data['profilePicture'];
    $nivell = isset($data['nivell']) ? $data['nivell'] : 'user';
    $personName = $data['personName'];

    $query = "INSERT INTO person (email, password, location, profilepicture, nivell, personname, status) VALUES ('$email', '$password', '$location', '$profilePicture', '$nivell', '$personName', 'active')";

    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        $error_message = pg_last_error($conn);
        if (strpos($error_message, 'duplicate key value violates unique constraint "unique_email"') !== false) {
            echo json_encode(['status' => 'Error adding user: Email already exists']);
        } else {
            echo json_encode(['status' => 'Error adding user']);
        }
    }
}

// Actualizar usuario
elseif ($method == 'PUT' && $path == 'update-user') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['id'];
    $email = $data['email'];
    $password = isset($data['password']) ? $data['password'] : null;
    $location = $data['location'];
    $profilePicture = $data['profilePicture'];
    $nivell = $data['nivell'];
    $personName = $data['personName'];

    $query = "UPDATE person SET email = '$email', location = '$location', profilepicture = '$profilePicture', nivell = '$nivell', personname = '$personName'";
    if ($password) {
        $query .= ", password = '$password'";
    }
    $query .= " WHERE id = '$userId'";

    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'User updated']);
    } else {
        echo json_encode(['status' => 'Error updating user']);
    }
}

// Marcar un usuario como inactivo en lugar de borrarlo
elseif ($method == 'DELETE' && $path == 'user') {
    $userId = $_GET['id'];

    $query = "UPDATE person SET status = 'inactive' WHERE id = '$userId'";
    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'User marked as inactive']);
    } else {
        echo json_encode(['status' => 'Error marking user as inactive']);
    }
}

// Iniciar sesión
elseif ($method == 'POST' && $path == 'login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'];
    $password = $data['password'];

    $query = "SELECT * FROM person WHERE email = '$email' AND password = '$password' AND status = 'active'";
    $result = pg_query($conn, $query);
    $user = pg_fetch_assoc($result);

    if ($user) {
        echo json_encode(['status' => 'Login successful', 'isLoggedIn' => true, 'user' => $user, 'isAdmin' => $user['nivell'] === 'admin']);
    } else {
        echo json_encode(['status' => 'Invalid credentials', 'isLoggedIn' => false]);
    }
}

// Añadir valoración a usuario
elseif ($method == 'POST' && $path == 'valoracion') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['userId'];
    $rate = $data['rate'];
    $comment = $data['comment'];
    $transactionId = $data['transactionId'];
    $ownerId = $data['ownerId'];

    $query = "INSERT INTO valoration (userid, rate, comment, transactionid, ownerid) VALUES ('$userId', '$rate', '$comment', '$transactionId', '$ownerId')";
    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'Valoration added']);
    } else {
        echo json_encode(['status' => 'Error adding valoration']);
    }
}

// Actualizar valoración de usuario
elseif ($method == 'PUT' && $path == 'update-valoracion') {
    $data = json_decode(file_get_contents('php://input'), true);
    $valorationId = $data['id'];
    $rate = $data['rate'];
    $comment = $data['comment'];

    $query = "UPDATE valoration SET rate = '$rate', comment = '$comment' WHERE id = '$valorationId'";
    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'Valoration updated']);
    } else {
        echo json_encode(['status' => 'Error updating valoration']);
    }
}

// Marcar valoración de usuario como inactiva
elseif ($method == 'DELETE' && $path == 'valoracion') {
    $valorationId = $_GET['id'];

    $query = "UPDATE valoration SET status = 'inactive' WHERE id = '$valorationId'";
    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'Valoration marked as inactive']);
    } else {
        echo json_encode(['status' => 'Error marking valoration as inactive']);
    }
}

// Listar productos favoritos de un usuario
elseif ($method == 'GET' && $path == 'get-favorites') {
    $userId = $_GET['userid'];
    $query = "SELECT p.* FROM favorite f JOIN product p ON f.productid = p.id WHERE f.userid = '$userId' AND p.status = 'active'";
    $result = pg_query($conn, $query);
    $favorites = pg_fetch_all($result);
    echo json_encode($favorites ?: []);
}

// Añadir producto a favoritos
elseif ($method == 'POST' && $path == 'add-favorites') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['userid'];
    $productId = $data['productid'];

    $query = "INSERT INTO favorite (userid, productid) VALUES ('$userId', '$productId')";
    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'Favorite added']);
    } else {
        echo json_encode(['status' => 'Error adding favorite']);
    }
}

// Marcar producto de favoritos como inactivo
elseif ($method == 'DELETE' && $path == 'favorites') {
    $userId = $_GET['userid'];
    $productId = $_GET['productid'];

    $query = "UPDATE favorite SET status = 'inactive' WHERE userid = '$userId' AND productid = '$productId'";
    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'Favorite marked as inactive']);
    } else {
        echo json_encode(['status' => 'Error marking favorite as inactive']);
    }
}

// Listar historial de compras de un usuario
elseif ($method == 'GET' && $path == 'purchase-history') {
    $userId = $_GET['userid'];
    $query = "SELECT t.*, p.* FROM transaction t JOIN product p ON t.productid = p.id WHERE t.buyerid = '$userId'";
    $result = pg_query($conn, $query);
    $purchaseHistory = pg_fetch_all($result);
    echo json_encode($purchaseHistory ?: []);
}

// Listar historial de ventas de un usuario
elseif ($method == 'GET' && $path == 'sales-history') {
    $userId = $_GET['userid'];
    $query = "SELECT t.*, p.* FROM transaction t JOIN product p ON t.productid = p.id WHERE t.ownerid = '$userId'";
    $result = pg_query($conn, $query);
    $salesHistory = pg_fetch_all($result);
    echo json_encode($salesHistory ?: []);
}

// Listar productos a la venta de un usuario
elseif ($method == 'GET' && $path == 'products-for-sale') {
    $userId = $_GET['userid'];
    $query = "SELECT * FROM product WHERE ownerid = '$userId' AND status = 'active'";
    $result = pg_query($conn, $query);
    $productsForSale = pg_fetch_all($result);
    echo json_encode($productsForSale ?: []);
}

// Obtener el balance del usuario
elseif ($method == 'GET' && $path == 'get-balance') {
    $userId = $_GET['userid'];
    $query = "SELECT balance FROM person WHERE id = '$userId'";
    $result = pg_query($conn, $query);
    if ($result) {
        $balance = pg_fetch_assoc($result);
        if ($balance) {
            echo json_encode($balance);
        } else {
            echo json_encode(['status' => 'User not found']);
        }
    } else {
        echo json_encode(['status' => 'Error executing query', 'error' => pg_last_error($conn)]);
    }
}

// Añadir saldo al usuario
elseif ($method == 'POST' && $path == 'add-balance') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['userId'];
    $amount = $data['amount'];

    if ($amount <= 0) {
        echo json_encode(['status' => 'Invalid amount']);
    } else {
        $query = "UPDATE person SET balance = balance + '$amount' WHERE id = '$userId'";
        $result = pg_query($conn, $query);

        if ($result) {
            echo json_encode(['status' => 'Balance added']);
        } else {
            echo json_encode(['status' => 'Error adding balance']);
        }
    }
}

// Crear una solicitud de compra
elseif ($method == 'POST' && $path == 'request-purchase') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['userid'];
    $ownerId = $data['ownerid'];
    $productId = $data['productid'];

    //Comprobar si el usuario ya esta interesado en el producto
    $query = "SELECT * FROM purchaserequest WHERE buyerid = '$userId' AND productid = '$productId'";
    $result = pg_query($conn, $query);
    $purchaseRequest = pg_fetch_assoc($result);

    // Si no existe la solicitud, se crea
    if (!$purchaseRequest) {
        $query = "INSERT INTO purchaserequest (ownerid, buyerid, productid) VALUES ('$ownerId', '$userId', '$productId')";
        $result = pg_query($conn, $query);

        if ($result) {
            echo json_encode(['status' => 'Purchase request sent']);
        } else {
            echo json_encode(['status' => 'Error sending purchase request']);
        }
    } else {
        echo json_encode(['status' => 'Purchase request already sent']);
    }

}

// Aceptar una solicitud de compra
elseif ($method == 'POST' && $path == 'accept-purchase') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['userid'];
    $ownerId = $data['ownerid'];
    $productId = $data['productid'];
    $price = $data['price'];

    // Verificar el saldo del comprador
    $query = "SELECT balance FROM person WHERE id = '$userId'";
    $result = pg_query($conn, $query);
    $user = pg_fetch_assoc($result);

    if ($user['balance'] < $price) {
        echo json_encode(['status' => 'Insufficient balance']);
    } else {
        // Iniciar la transacción
        pg_query($conn, "BEGIN");

        // Registrar la compra
        $query = "INSERT INTO transaction (buyerid, ownerid, productid) VALUES ('$userId', '$ownerId', '$productId')";
        $result = pg_query($conn, $query);

        if ($result) {
            // Restar el precio del saldo del comprador
            $query = "UPDATE person SET balance = balance - '$price' WHERE id = '$userId'";
            $result = pg_query($conn, $query);

            // Marcar el producto como vendido
            $query = "UPDATE product SET status = 'sold' WHERE id = '$productId'";
            $result = pg_query($conn, $query);

            if ($result) {
                // Confirmar la transacción
                pg_query($conn, "COMMIT");
                echo json_encode(['status' => 'Purchase successful']);
            } else {
                // Revertir la transacción
                pg_query($conn, "ROLLBACK");
                echo json_encode(['status' => 'Error during purchase']);
            }
        } else {
            // Revertir la transacción
            pg_query($conn, "ROLLBACK");
            echo json_encode(['status' => 'Error during purchase']);
        }
    }
}

pg_close($conn);
?>
