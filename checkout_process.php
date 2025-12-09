<?php
session_start();
include "db.php";

if (isset($_SESSION["uid"])) {
    $user_id = $_SESSION["uid"];

    // Extract user information from the submitted form
    $f_name = $_POST["firstname"];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $cardname = $_POST['cardname'];
    $cardnumber = $_POST['cardNumber'];
    $expdate = $_POST['expdate'];
    $cvv = $_POST['cvv'];

    // Calculate order ID
    $sql0 = "SELECT MAX(order_id) AS max_val FROM `orders_info`";
    $runquery = mysqli_query($con, $sql0);
    
    if ($runquery) {
        $row = mysqli_fetch_array($runquery);
        $order_id = $row["max_val"] + 1;
    } else {
        echo(mysqli_error($con));
        exit;
    }

    // Insert order information into the orders_info table
    $sql_order = "INSERT INTO `orders_info` 
    (`order_id`, `user_id`, `f_name`, `email`, `address`, 
    `city`, `state`, `zip`, `cardname`, `cardnumber`, `expdate`, `cvv`) 
    VALUES ('$order_id', '$user_id', '$f_name', '$email', 
    '$address', '$city', '$state', '$zip', '$cardname', '$cardnumber', '$expdate', '$cvv')";

    if (mysqli_query($con, $sql_order)) {
        // Loop through products and insert into order_products table
        $total_count = $_POST['total_count'];
        $order_pro_id = 1; // Initialize the order_pro_id
        
        for ($i = 1; $i <= $total_count; $i++) {
            $prod_id = $_POST['prod_id_' . $i];
            $prod_price = $_POST['prod_price_' . $i];
            $prod_qty = $_POST['prod_qty_' . $i];
            $sub_total = (int)$prod_price * (int)$prod_qty;

            $sql_product = "INSERT INTO `order_products` 
            (`order_pro_id`, `order_id`, `product_id`, `qty`, `amt`) 
            VALUES ('$order_pro_id', '$order_id', '$prod_id', '$prod_qty', '$sub_total')";

            if (mysqli_query($con, $sql_product)) {
                $order_pro_id++; // Increment the order_pro_id for the next product
            } else {
                echo(mysqli_error($con));
                exit;
            }
        }

        // Delete items from the cart for the user
        $del_sql = "DELETE FROM cart WHERE user_id = $user_id";
        if (mysqli_query($con, $del_sql)) {
            echo "<script>window.location.href = 'store.php'</script>";
        } else {
            echo(mysqli_error($con));
        }
    } else {
        echo(mysqli_error($con));
    }
} else {
    echo "<script>window.location.href = 'index.php'</script>";
}
?>
