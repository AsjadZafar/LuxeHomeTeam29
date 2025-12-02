
<?php

session_start();

require_once 'dbh.php';

$sql = "SELECT * from products"; 

$result = mysqli_query($conn, $sql);

if (isset($_GET['id'])) {

    $p_id = $_GET['id'];

    $del_sql = "DELETE from products where product_id = '$p_id'";

    $data = mysqli_query($conn,$del_sql);

    if($data) {
        header("location: view_product.php");
    }
}


?>




<!DOCTYPE html>
<html>
<head>
    <meta charset = "utf-8">
    <meta name = "viewport" content = "width=device-width, initial-scale=1">

<title>Admin Page</title>
<link rel="stylesheet" href="/css/admin-style.css">
</head>

<body>
    <div class = "wrapper">

        <div class = "sidebar">
                <h2>Admin Dashboard </h2>
                <ul> 
                    <li> 
                        <a href = "add_product.php">Add Products</a>
                    </li>

                    <li> 
                        <a href = "view_product.php">View Products</a>
                    </li>

                    <li> 
                        <a href = "#">Users</a>
                    </li>

                    <li> 
                        <a href = "#">Analytics</a>
                    </li>
                </ul>

</div>
<div class = "header"> 
    <div class ="admin_header"> 
        <a href = "#">Logout</a>
    </div>

    <div class= "info">
        <h1>All Products</h1>

        <table> 
            <tr> 
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Installation Availability</th>
                <th>Image</th>
                <th>Delete</th>
            </tr>

            <?php 
            
            while($row= mysqli_fetch_assoc($result)) {

             ?>   

             <tr> 
                <td><?php echo $row['name'] ?></td>
                <td><?php echo $row['description'] ?> </td>
                <td><?php echo $row['price'] ?> </td>
                <td><?php echo $row['quantity'] ?> </td>
                <td><?php echo $row['installation_available'] ?> </td>
                <td>
                    
                <img height= "100px" width ="100px" src= "../product_image/<?php echo $row['img'] ?>" alt = "Product Pic here">
            
                </td>
                <td> <a onclick = "return confirm('You are about to delete a product. Are you sure you want to continue?');" class = "del_btn" href = "view_product.php?id=<?php echo $row['product_id']?>">Delete</a></td>
            </tr>

             <?php 

             
            }

            ?>   
            
        
        </table>

</div>
    
</div> 
</div>



</body>

</html>


