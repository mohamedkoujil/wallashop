<?php
// Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");

// Permitir los métodos GET y POST
header("Access-Control-Allow-Methods: GET, POST, DELETE");

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

// Listar todos los productos
if ($method == 'GET' && $path == 'products') {
    $result = pg_query($conn, "SELECT * FROM Product");
    $products = pg_fetch_all($result);
    echo json_encode($products);
}

// Obtener información de un producto específico por ID
elseif ($method == 'GET' && $path == 'product') {
    $productId = $_GET['id'];
    $query = "SELECT p.*, o.email AS ownerEmail, o.personname AS ownerName FROM product p JOIN person o ON p.ownerid = o.id WHERE p.id = '$productId'";
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
    $query = "SELECT * FROM product WHERE category = '$category'";
    $result = pg_query($conn, $query);
    $products = pg_fetch_all($result);
    echo json_encode($products);
}

// Listar todas las categorías
elseif ($method == 'GET' && $path == 'categories') {
    $query = "SELECT DISTINCT category FROM Product";
    $result = pg_query($conn, $query);
    $categories = pg_fetch_all($result);
    echo json_encode($categories);
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

    $query = "INSERT INTO product (Description, Location, Price, Images, OwnerId, ProductName) VALUES ('$description', '$location', '$price', '$images', '$ownerId', '$productName')";
    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'Product added']);
    } else {
        echo json_encode(['status' => 'Error adding product']);
    }
}

// Borrar un producto
elseif ($method == 'DELETE' && $path == 'product') {
    $productId = $_GET['id'];

    $query = "DELETE FROM product WHERE id='$productId'";
    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'Product deleted']);
    } else {
        echo json_encode(['status' => 'Error deleting product']);
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

    // Check if the email already exists
    $emailCheckQuery = "SELECT * FROM person WHERE email = '$email'";
    $emailCheckResult = pg_query($conn, $emailCheckQuery);
    $emailExists = pg_fetch_result($emailCheckResult, 0);

    if ($emailExists > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email address already registered']);
    } else {
        // Proceed with the insertion
        $query = "INSERT INTO person (Email, Password, Location, ProfilePicture, nivell, PersonName) VALUES ('$email', '$password', '$location', '$profilePicture', '$nivell', '$personName')";
        $result = pg_query($conn, $query);

        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error adding user: ' . pg_last_error()]);
        }
    }
}

// Iniciar sesión
elseif ($method == 'POST' && $path == 'login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'];
    $password = $data['password'];

    $query = "SELECT * FROM person WHERE email='$email' AND password='$password'";
    $result = pg_query($conn, $query);
    $user = pg_fetch_assoc($result);

    if ($user) {
        // Devolver el usuario junto con el estado de inicio de sesión
        echo json_encode(['status' => 'Login successful', 'isLoggedIn' => true, 'user' => $user]);
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

    $query = "INSERT INTO valoration (userId, rate, comment, transactionId, ownerId) VALUES ('$userId', '$rate', '$comment', '$transactionId', '$ownerId')";
    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'Valoration added']);
    } else {
        echo json_encode(['status' => 'Error adding valoration']);
    }
}

// Borrar valoración de usuario
elseif ($method == 'DELETE' && $path == 'valoracion') {
    $valorationId = $_GET['id'];

    $query = "DELETE FROM valoration WHERE id='$valorationId'";
    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'Valoration deleted']);
    } else {
        echo json_encode(['status' => 'Error deleting valoration']);
    }
}

// Listar productos favoritos de un usuario
elseif ($method == 'GET' && $path == 'favorites') {
    $userId = $_GET['userid'];
    $query = "SELECT p.* FROM favorite f JOIN product p ON f.productid = p.id WHERE f.userid = '$userId'";
    $result = pg_query($conn, $query);
    $favorites = pg_fetch_all($result);
    echo json_encode($favorites);
}

// Listar historial de compras de un usuario
elseif ($method == 'GET' && $path == 'purchase-history') {
    $userId = $_GET['userid'];
    $query = "SELECT t.*, p.* FROM transaction t JOIN product p ON t.productid = p.id WHERE t.buyerid = '$userId'";
    $result = pg_query($conn, $query);
    $purchaseHistory = pg_fetch_all($result);
    echo json_encode($purchaseHistory);
}

// Listar historial de ventas de un usuario
elseif ($method == 'GET' && $path == 'sales-history') {
    $userId = $_GET['userid'];
    $query = "SELECT t.*, p.* FROM transaction t JOIN product p ON t.productid = p.id WHERE t.ownerid = '$userId'";
    $result = pg_query($conn, $query);
    $salesHistory = pg_fetch_all($result);
    echo json_encode($salesHistory);
}

pg_close($conn);
?>