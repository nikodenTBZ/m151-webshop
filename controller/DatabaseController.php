<?php
if(file_exists("../model/Database.php")){
    include "../model/Database.php";
} else if(file_exists("./model/Database.php")){
    include "./model/Database.php";
}

function getProducts()
{

    $conn = db_connect2();

    $sql = "SELECT * FROM product";

    $result = $conn->query($sql) ->fetch_all(MYSQLI_ASSOC);

    return $result;
}

function verifyLogin($email, $password)
{
    $conn = db_connect1();
    $sql = 'SELECT * from user';

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        foreach ($result as $row) {
            if ($row['email'] == $email) {
                if (password_verify($password, $row['password'])) {
                    return true;
                }
            }
        }
    }

    $conn->close();
    return false;
}

function register($email, $password)
{
    $conn = db_connect1();
    $sql = 'SELECT * from user';


    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        foreach ($result as $row) {
            if ($row['email'] == $email) {
                $_SESSION['errorMessage'] = "error";
                return false;
            }
        }
    }

    $sql = 'INSERT INTO user(email, password) VALUES (?, ?)';

    $stmt = mysqli_prepare($conn, $sql);

    if (false === $stmt) {
        echo mysqli_error($conn);
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        mysqli_stmt_bind_param($stmt, 'ss', $email, $hashedPassword);

        if (mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }
    }

    $conn->close();
    return true;
}


function getProductById($id)
{
    $conn = db_connect1();
    $sql = "SELECT * from job where id = $id";

    $stmt = mysqli_prepare($conn, $sql);

    $result = $conn->query($sql);

    return mysqli_fetch_array($result);

}

function acceptJob($jobId, $userEmail){

    $conn = db_connect1();
    $sql = "UPDATE job SET jobAceptor=$userEmail WHERE id=$jobId";

    $stmt = mysqli_prepare($conn, $sql);

    $result = $conn->query($sql);
}

?>