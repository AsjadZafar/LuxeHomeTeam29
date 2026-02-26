<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'dbh.php';


function get_order_details(){
	global $conn;
	$username = $_SESSION['username'];
	$get_details = "SELECT * FROM users WHERE username='$username'";
    $result_query = mysqli_query($conn,$get_details);
	while($row_query=mysqli_fetch_array($result_query)){
    	$user_id=$row_query['user_id'];
    	if(!isset($_GET['edit_account'])){
        	if(!isset($_GET['your_orders'])){
            	if(!isset($_GET['wishlist'])){
                $get_orders="SELECT * FROM orders WHERE user_id=$user_id";
                $result_orders_query = mysqli_query($conn,$get_orders);
                $row_count=mysqli_num_rows($result_orders_query);
                if($row_count>0){
                echo "<h2 class='text-center  text-success my-5'>You have a total of <span>$row_count</span> orders</h2>
                <p class='text-center'><a href='profile.php?your_orders'>Order Details</a></p>";
       		}else{
               	echo "<h2 class='text-center  text-success my-5'>You have a total of zero orders</h2>
                <p class='text-center'><a href='../products.php'>Browse Products</a></p>";
    	
    	}
	}
  }
    }
}
}