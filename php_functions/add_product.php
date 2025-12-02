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

        <h1>Add Products</h1>
        <div class = "my_form"> 
            <form action = "" method = "POST" enctype = multipart/form-data> 
                <div class= "div_deg"> 
                    <label>Product Name</label>
                    <input type="text" name="name">
                </div>

                <div class= "div_deg"> 
                    <label>Description</label>
                    <textarea name = "description"> </textarea>
                </div>


            <div class= "div_deg"> 
                    <label>Price</label>
                    <input type="num" name="price">
                </div>

            <div class= "div_deg"> 
                    <label>Quantity</label>
                    <input type="num" name="quantity">
                </div>

            <div class= "div_deg"> 
                    <label for= "installation_available">Installation Availability</label>
                    <select name = "installation_available"> 
                        <option value = "Yes">Yes</option>
                        <option value = "No">No</option>
                    </select>
                </div>    

            <div class= "div_deg"> 
                 <label>Product Image</label>
                 <input type="file" name="img">
            </div>

                
                <div class= "div_deg"> 
                    <input type="submit" name="add_product" value = "Add Product">
                </div>

            </form>



        </div>
        

</div>
    
</div> 
</div>



</body>

</html>



<?php

session_start();

if(isset($_POST["add_product"])) {
    $product_name = $_POST['name'];
    $product_description = $_POST['description'];
    $product_price = $_POST['price'];
    $product_quantity = $_POST['quantity'];
    $product_install = $_POST['installation_available'];
    $product_img = $_FILES['img']['name'];

    $tmp = explode(".",$product_img);

    $newfilename = round(microtime(true)). '.' .end($tmp);

    $uploadpath = "../product_image/". $newfilename;

    move_uploaded_file($_FILES['img']['tmp_name'], $uploadpath);
    



    require_once 'dbh.php';
    
    $sql = "INSERT into products(name,description,price,quantity,installation_available,img) 
    Values('$product_name','$product_description',' $product_price','$product_quantity',' $product_install','$newfilename')";

    $data = mysqli_query($conn,$sql);

    if($data) {
        header('location: add_product.php');
    }

}




?>