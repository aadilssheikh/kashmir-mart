<?php
session_start();
include("../../db.php");
error_reporting(0);

function sanitizeInput($input) {
    global $con;
    return mysqli_real_escape_string($con, $input);
}

if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $product_id = sanitizeInput($_GET['product_id']);

    $result = mysqli_query($con, "SELECT product_image FROM products WHERE product_id='$product_id'");
    $row = mysqli_fetch_assoc($result);
    $picture = $row['product_image'];
    $path = "../product_images/$picture";

    if (file_exists($path)) {
        unlink($path);
    }

    mysqli_query($con, "DELETE FROM products WHERE product_id='$product_id'");
}

$result = mysqli_query($con, "SELECT product_id, product_image, product_title, product_price FROM products");

include "sidenav.php";
include "topheader.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products List</title>
</head>
<body>
    <div class="content">
        <div class="container-fluid">
            <div class="col-md-14">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Products List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive ps">
                            <table class="table tablesorter" id="page1">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $product_id = $row['product_id'];
                                        $image = $row['product_image'];
                                        $product_name = $row['product_title'];
                                        $price = $row['product_price'];

                                        echo "<tr>
                                            <td><img src='../../product_images/$image' style='width:50px; height:50px; border:groove #000'></td>
                                            <td>$product_name</td>
                                            <td>$price</td>
                                            <td><a class='btn btn-success' href='products_list.php?product_id=$product_id&action=delete'>Delete</a></td>
                                        </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include "footer.php";
    ?>
</body>
</html>
