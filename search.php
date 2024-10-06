<?php 
    include('database.php');
    if (!empty($_GET)) {
        if (isset($_GET['search'])) {
            $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
            $category = isset($_GET['category']) ? intval($_GET['category']) : 0;
            $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
            $rating = isset($_GET['rating']) ? intval($_GET['rating']) : 0;
            
            $sql = "SELECT id, product_name, description, price, rating, image_url FROM products WHERE ";
            
            if (!empty($keyword)) {
                $sql .= "LOWER(product_name) LIKE LOWER('%{$keyword}%') AND ";
            }
            if ($category > 0) {
                $sql .= "category = {$category} AND ";
            }
            if ($min_price > 0) {
                $sql .= "price > {$min_price} AND ";
            }
            if ($rating > 0) {
                $sql .= "rating >= {$rating} AND ";
            }
    
            // Remove trailing "AND"
            $sql = substr($sql, 0, -5);
    
            // Execute the query
            $conn = mysqli_connect("your_host", "your_username", "your_password", "your_database");
            $result = mysqli_query($conn, $sql);
    
            // Fetch and prepare data for JSON
            $data = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
    
    
            // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }
?>
