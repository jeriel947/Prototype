<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    include '../database/db-connection.php';

    // Retrieve the orderId from the request
    $orderId = $_POST['orderId'];
    $menuId = $_POST['menuId'];

    try {
        mysqli_begin_transaction($con);

        // Update tbl_orders
        $updateOrderQuery = "UPDATE tbl_orders SET order_status = 2 WHERE id = ?";
        $stmtOrder = mysqli_prepare($con, $updateOrderQuery);
        mysqli_stmt_bind_param($stmtOrder, "s", $orderId);
        mysqli_stmt_execute($stmtOrder);

        // Commit the transaction
        mysqli_commit($con);

        // Handle the response
        $response = array('success' => true, 'message' => "Order {$orderId} received successfully");
        echo json_encode($response);

        // Close the statement
        mysqli_stmt_close($stmtOrder);
    } catch (Exception $e) {
        // Rollback the transaction in case of any error
        mysqli_rollback($con);

        // Handle the error
        $response = array('success' => false, 'message' => 'Error occurred during updates');
        echo json_encode($response);
    }

    // Close the the database connection
    mysqli_close($con);
?>