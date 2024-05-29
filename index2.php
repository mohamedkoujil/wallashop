<?php
// Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");

// Permitir los m      todos GET y POST
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");

// Permitir los encabezados que se utilizan com      nmente en las solicitudes
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Permitir que las solicitudes incluyan cookies
header("Access-Control-Allow-Credentials: true");

// Configuraci      n de la conexi      n a la base de datos
$host = '172.17.0.3';
$port = '5432';
$dbname = 'wallashop';
$user = 'wallashop';
$password = 'wallashop';

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Obtener m      todo HTTP
$method = $_SERVER['REQUEST_METHOD'];
$path = isset($_GET['path']) ? $_GET['path'] : '';


// Listar todos los productos
if ($method == 'GET' && $path == 'products') {
    $result = pg_query($conn, "SELECT * FROM product where status != 'sold'");
    $products = pg_fetch_all($result);
    echo json_encode($products ?: []);
}

// Obtener informaci      n de un producto espec      fico por ID
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
// Obtener productos filtrados por categor      a
elseif ($method == 'GET' && $path == 'products' && isset($_GET['category'])) {
    $category = $_GET['category'];
    $query = "SELECT * FROM product WHERE category = '$category'";
    $result = pg_query($conn, $query);
    $products = pg_fetch_all($result);
    echo json_encode($products ?: []);
}

// Listar todas las categor      as
elseif ($method == 'GET' && $path == 'categories') {
    $query = "SELECT DISTINCT category FROM Product";
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

    $query = "INSERT INTO product (description, location, price, images, ownerid, productname, category) VALUES ('$description', '$location', '$price', '$images', '$ownerId', '$productName', '$category')";
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

    $query = "UPDATE product SET Description = '$description', Location = '$location', Price = '$price', Images = '$images', ProductName = '$productName', Category = '$category' WHERE id = '$productId'">
    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'Product updated']);
    } else {
        echo json_encode(['status' => 'Error updating product']);
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

    $query = "INSERT INTO person (Email, Password, Location, ProfilePicture, nivell, PersonName) VALUES ('$email', '$password', '$location', '$profilePicture', '$nivell', '$personName')";

    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        $error_message = pg_last_error($conn);
        if (strpos($error_message, 'duplicate key value violates unique constraint "unique_email"') !== false) {
            echo json_encode(['status' => 'Error adding product: Email already exists']);
        } else {
            echo json_encode(['status' => 'Error adding product']);
        }
    }
}

// Iniciar sesi      n
elseif ($method == 'POST' && $path == 'login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'];
    $password = $data['password'];

    $query = "SELECT * FROM person WHERE email='$email' AND password='$password'";
    $result = pg_query($conn, $query);
    $user = pg_fetch_assoc($result);

    if ($user) {
        // Devolver el usuario junto con el estado de inicio de sesi      n
        echo json_encode(['status' => 'Login successful', 'isLoggedIn' => true, 'user' => $user, 'isAdmin' => $user['nivell'] === 'admin']);
    } else {
        echo json_encode(['status' => 'Invalid credentials', 'isLoggedIn' => false]);
    }
}
// A      adir valoraci      n a usuario
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
// Borrar valoraci      n de usuario
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
elseif ($method == 'GET' && $path == 'get-favorites') {
    $userId = $_GET['userid'];
    $query = "SELECT p.* FROM favorite f JOIN product p ON f.productid = p.id WHERE f.userid = '$userId' and p.status != 'sold'";
    $result = pg_query($conn, $query);
    $favorites = pg_fetch_all($result);
    echo json_encode($favorites ?: []);
}
// A      adir producto a favoritos
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
// Borrar producto de favoritos
elseif ($method == 'DELETE' && $path == 'favorites') {
    $userId = $_GET['userid'];
    $productId = $_GET['productid'];

    $query = "DELETE FROM favorite WHERE userid='$userId' AND productid='$productId'";
    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'Favorite deleted']);
    } else {
        echo json_encode(['status' => 'Error deleting favorite']);
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
    $query = "SELECT * FROM product WHERE ownerid = '$userId' and status != 'sold'";
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
// A      adir saldo al usuario
elseif ($method == 'POST' && $path == 'add-balance') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['userId'];
    $amount = $data['amount'];
    // $cardNumber = $data['cardNumber']; // Opcional
    // $cardExpiry = $data['cardExpiry']; // Opcional
    // $cardName = $data['cardName']; // Opcional

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
// Comprar un producto usando saldo
elseif ($method == 'POST' && $path == 'purchase') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['userid'];
    $ownerId = $data['ownerid'];
    $productId = $data['productid'];
    $price = $data['price'];

    // Obtener el saldo del usuario
    $query = "SELECT balance FROM person WHERE id = '$userId'";
    $result = pg_query($conn, $query);
    $user = pg_fetch_assoc($result);

    if ($user['balance'] < $price) {
        echo json_encode(['status' => 'Insufficient balance']);
    } else {

        // Registrar la compra
        $query = "INSERT INTO transaction (buyerid, ownerid, productid) VALUES ('$userId', '$ownerId', '$productId')";
        $result = pg_query($conn, $query);

        if ($result) {
            // Restar el precio del saldo del usuario
            $query = "UPDATE person SET balance = balance - '$price' WHERE id = '$userId'";
            $result = pg_query($conn, $query);

            //Producto como sold
            $query = "update product set status = 'sold' where id = '$productId'";
            $result = pg_query($conn, $query);
            
            // Borrar solicitud de compra
            $query = "DELETE FROM purchaserequest WHERE productid = '$productId'";
            $result = pg_query($conn, $query);

            echo json_encode(['status' => 'Purchase successful']);
        } else {
            echo json_encode(['status' => 'Error during purchase']);
        }
    }
}
//Listar solicitudes de compra de un usuario
elseif ($method == 'GET' && $path == 'get-purchase-requests') {
    $userId = $_GET['userid'];
    $query = "SELECT * FROM purchaserequest WHERE buyerid = '$userId'";
    $result = pg_query($conn, $query);
    $requests = pg_fetch_all($result);
    echo json_encode($requests ?: []);
}

//Listar solicitudes de venta de un usuario
elseif ($method == 'GET' && $path == 'get-sales-requests') {
    $userId = $_GET['userid'];
    $query = "SELECT * FROM purchaserequest WHERE ownerid = '$userId'";
    $result = pg_query($conn, $query);
    $requests = pg_fetch_all($result);
    echo json_encode($requests ?: []);
}


pg_close($conn);
?>
