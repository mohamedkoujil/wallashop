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
    $result = pg_query($conn, "SELECT * FROM product WHERE status = 'available'");
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
    $query = "SELECT * FROM product WHERE id = '$productId'";
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
    $query = "SELECT * FROM product WHERE category = '$category' AND status = 'available'";
    $result = pg_query($conn, $query);
    $products = pg_fetch_all($result);
    echo json_encode($products ?: []);
}

// Listar todas las categorías de productos activos
elseif ($method == 'GET' && $path == 'categories') {
    $query = "SELECT DISTINCT category FROM product WHERE status = 'available'";
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

    $query = "INSERT INTO product (description, location, price, images, ownerid, productname, category, status) VALUES ('$description', '$location', '$price', '$images', '$ownerId', '$productName', '$category', 'available')";
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

// Borrar un producto
elseif ($method == 'DELETE' && $path == 'product') {
    $productId = $_GET['id'];

    //Set status to deleted
    $query = "UPDATE product SET status = 'deleted' WHERE id = '$productId'";
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
    $role = isset($data['role']) ? $data['role'] : 'user';
    $personName = $data['personName'];

    // Asegúrate de que el correo electrónico sea único
    $checkQuery = "SELECT * FROM person WHERE email = '$email'";
    $checkResult = pg_query($conn, $checkQuery);

    if (pg_num_rows($checkResult) > 0) {
        // Email ya existe
        echo json_encode(['status' => 'Error adding user: Email already exists']);
    } else {
        // Inserta nuevo usuario
        $query = "INSERT INTO person (email, password, location, profilepicture, role, personname, status) VALUES ('$email', '$password', '$location', '$profilePicture', '$role', '$personName', 'active')";

        $result = pg_query($conn, $query);

        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            $error_message = pg_last_error($conn);
            echo json_encode(['status' => 'Error adding user', 'error' => $error_message]);
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
    $profilePicture = $data['profilepicture'];
    $nivell = $data['role'];
    $personName = $data['personname'];

    $query = "UPDATE person SET email = '$email', location = '$location', profilepicture = '$profilePicture', role = '$nivell', personname = '$personName'";
    if ($password) {
        $query .= ", password = '$password'";
    }
    $query .= " WHERE id = '$userId'";

    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'User updated', 'user' => $data]);
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
        echo json_encode(['status' => 'Login successful', 'isLoggedIn' => true, 'user' => $user, 'isAdmin' => $user['role'] === 'admin']);
    } else {
        echo json_encode(['status' => 'Invalid credentials', 'isLoggedIn' => false]);
    }
}

// Obtener información de un usuario por ID
elseif ($method == 'GET' && $path == 'user') {
    $userId = $_GET['id'];
    $query = "SELECT * FROM person WHERE id = '$userId'";
    $result = pg_query($conn, $query);
    $user = pg_fetch_assoc($result);
    if ($user) {
        echo json_encode($user);
    } else {
        echo json_encode(['status' => 'User not found']);
    }
}

// Añadir valoración a usuario
elseif ($method == 'POST' && $path == 'valoracion') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['userid'];
    $rate = $data['rate'];
    $comment = $data['comment'];
    $ownerId = $data['ownerid'];
    $productId = $data['productid'];

    // Obtener el id de la transacción
    $query = "SELECT id FROM transaction WHERE buyerid = '$userId' AND ownerid = '$ownerId' AND productid = '$productId'";
    $result = pg_query($conn, $query);
    $transaction = pg_fetch_assoc($result);
    $transactionId = $transaction['id'];

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

// Listar valoraciones de un usuario
elseif ($method == 'GET' && $path == 'user-valorations') {
    $userId = $_GET['userid'];
    $query = "SELECT * FROM valoration WHERE ownerid = '$userId' or userid = '$userId'";
    $result = pg_query($conn, $query);
    $valorations = pg_fetch_all($result);
    echo json_encode($valorations ?: []);
}

// Listar todas las valoraciones  
elseif ($method == 'GET' && $path == 'all-valorations') {
    $result = pg_query($conn, "SELECT * FROM valoration");
    $valorations = pg_fetch_all($result);
    echo json_encode($valorations ?: []);
}

//Obtener info de quien ha valorado
elseif ($method == 'GET' && $path == 'get-info-valorations') {
    $userId = $_GET['userid'];
    //Obtener info de quien ha valorado
    $query = "SELECT * FROM person WHERE id IN (SELECT ownerid FROM valoration WHERE ownerid = '$userId') OR id IN (SELECT userid FROM valoration WHERE userid = '$userId')";
    $result = pg_query($conn, $query);
    $user = pg_fetch_assoc($result);
    echo json_encode($user);
}

// Listar productos favoritos de un usuario
elseif ($method == 'GET' && $path == 'get-favorites') {
    $userId = $_GET['userid'];
    $query = "SELECT p.* FROM favorite f JOIN product p ON f.productid = p.id WHERE f.userid = '$userId' AND p.status = 'available'";
    $result = pg_query($conn, $query);
    $favorites = pg_fetch_all($result);
    echo json_encode($favorites ?: []);
}

//toggle favorite
elseif ($method == 'POST' && $path == 'toggle-favorite') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['userid'];
    $productId = $data['productid'];

    $query = "SELECT * FROM favorite WHERE userid = '$userId' AND productid = '$productId'";
    $result = pg_query($conn, $query);
    $favorite = pg_fetch_assoc($result);

    if ($favorite) {
        $query = "DELETE FROM favorite WHERE userid = '$userId' AND productid = '$productId'";
        $result = pg_query($conn, $query);
        echo json_encode(['status' => 'Favorite removed']);
    } else {
        $query = "INSERT INTO favorite (userid, productid) VALUES ('$userId', '$productId')";
        $result = pg_query($conn, $query);
        echo json_encode(['status' => 'Favorite added']);
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
    $query = "SELECT * FROM product WHERE ownerid = '$userId' AND status = 'available'";
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
    // Iniciar la transacción
    pg_query($conn, "BEGIN");

    // Registrar la compra
    $query = "INSERT INTO transaction (buyerid, ownerid, productid) VALUES ('$userId', '$ownerId', '$productId')";
    $result = pg_query($conn, $query);

    if ($result) {
        // Restar el precio del saldo del comprador
        $query = "UPDATE person SET balance = balance - '$price' WHERE id = '$userId'";
        $result = pg_query($conn, $query);

        //Aumentar el saldo del vendedor
        $query = "UPDATE person SET balance = balance + '$price' WHERE id = '$ownerId'";
        $result = pg_query($conn, $query);

        // Marcar el producto como vendido
        $query = "UPDATE product SET status = 'sold' WHERE id = '$productId'";
        $result = pg_query($conn, $query);

        // Eliminar la solicitud de compra
        $query = "DELETE FROM purchaserequest WHERE buyerid = '$userId' AND productid = '$productId' AND ownerid = '$ownerId'";
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

// Rechazar una solicitud de compra
elseif ($method == 'DELETE' && $path == 'reject-purchase') {
    $userId = $_GET['userid'];
    $productId = $_GET['productid'];

    $query = "DELETE FROM purchaserequest WHERE buyerid = '$userId' AND productid = '$productId'";
    $result = pg_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'Purchase request rejected']);
    } else {
        echo json_encode(['status' => 'Error rejecting purchase request']);
    }
}

// Obtener todas las solicitudes de compra de un usuario
elseif ($method == 'GET' && $path == 'get-purchase-requests') {
    $userId = $_GET['userid'];
    $query = "SELECT * FROM purchaserequest WHERE buyerid = '$userId'";
    $result = pg_query($conn, $query);
    $purchaseRequests = pg_fetch_all($result);
    echo json_encode($purchaseRequests ?: []);
}

// Obtener todas las solicitudes de venta de un usuario
elseif ($method == 'GET' && $path == 'get-sale-requests') {
    $userId = $_GET['userid'];
    $query = "SELECT * FROM purchaserequest WHERE ownerid = '$userId'";
    $result = pg_query($conn, $query);
    $saleRequests = pg_fetch_all($result);
    echo json_encode($saleRequests ?: []);
}

pg_close($conn);
?>