<?php 
require_once 'php_functions/dbh.php';


if (isset($_GET['id'])) {
  $id = intval($_GET['id']);

  $sql = "SELECT * from products WHERE product_id = $id";

  $result = mysqli_query($conn, $sql);

  $row = mysqli_fetch_assoc($result);

  if(!$row) {
    die("Product not found");
  }
} else {
  die("No product ID Provided");
}


?> 



<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Product Details | LuxeHome</title>
  <meta name="description" content="Product details and images for LuxeHome smart home products." />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .logo-img{ width:48px; height:48px; border-radius:50%; object-fit:cover }
  </style>
</head>
<body class="bg-gray-50">
  <header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center py-4">
        <a href="index.php" class="flex items-center space-x-3">
          <img src="images/image.png" alt="LuxeHome logo" class="logo-img">
          <div>
            <h1 class="text-xl font-bold text-gray-900">LuxeHome</h1>
            <p class="text-xs text-gray-500">Smart Living Elevated</p>
          </div>
        </a>

        <div class="flex items-center space-x-4">
          <a href="products.php" class="nav-link hover:text-emerald-600">Back to products</a>
          <a href="#basket" id="viewBasket2" class="relative action-btn hover:text-emerald-600">
            <i class="fas fa-shopping-cart"></i>
            <span id="cartCount2" class="cart-badge absolute -top-2 -right-2 bg-emerald-600 text-white text-xs px-1.5 rounded-full">0</span>
          </a>
        </div>
      </div>
    </div>
  </header>

  <main class="max-w-6xl mx-auto px-4 py-10">
    <div id="productWrap" class="bg-white rounded-xl shadow p-6 grid lg:grid-cols-2 gap-6">

  <div>
    <img src="product_image/<?= $row['img'] ?>" class="w-full rounded-lg object-cover h-80">
  </div>

  <div>
    <h1 class="text-2xl font-bold mb-2"><?= $row['name'] ?></h1>
    <p class="text-emerald-600 font-semibold text-xl mb-4">£<?= number_format($row['price'], 2) ?></p>
    <p class="text-gray-600 mb-4"><?= $row['description'] ?></p>

    <div class="mb-4">
      <label class="block text-sm text-gray-600 mb-2"><?= $row['quantity']?></label>
      <input id="qty" type="number" min="1" value="1" class="w-24 p-2 border rounded-md" />
    </div>

    <div class="flex gap-3">
      <form action="php_functions/addToCart.php" method="POST">
   <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
   <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-md">Add to Basket</button>
</form>


    </div>

    <p id="addedMsg" class="text-green-600 mt-4 hidden">Added to basket</p>
  </div>

</div>

  </main>

  <footer class="bg-gray-900 text-gray-300 py-10 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid md:grid-cols-3 gap-8">
        <div>
          <div class="flex items-center space-x-3 mb-4">
            <img src="images/image.png" alt="LuxeHome logo" class="logo-img">
            <div>
              <h3 class="text-lg font-bold text-white">LuxeHome</h3>
              <p class="text-sm text-gray-400">Smart Living Elevated</p>
            </div>
          </div>
          <p class="text-gray-400 text-sm mb-4">Experience premium smart home technology designed for modern, elegant living.</p>
        </div>
        <div>
          <h4 class="text-white font-semibold mb-3">Quick Links</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="index.php" class="hover:text-white">Home</a></li>
            <li><a href="products.php" class="hover:text-white">Shop</a></li>
            <li><a href="#" class="hover:text-white">Collections</a></li>
            <li><a href="contact.php" class="hover:text-white">Contact</a></li>
          </ul>
        </div>
        <div>
          <h4 class="text-white font-semibold mb-3">Contact</h4>
          <ul class="space-y-2 text-sm">
            <li>hello@luxehome.com</li>
            <li>1-800-LUXE-HOME</li>
            <li>Mon–Fri: 9am–6pm GMT</li>
          </ul>
        </div>
      </div>
      <div class="border-t border-gray-700 mt-8 pt-4 text-center text-sm text-gray-400">
        © 2025 LuxeHome. All rights reserved.
      </div>
    </div>
  </footer>


 <script>


  function getCart(){ return JSON.parse(localStorage.getItem('luxehome_cart') || '[]'); }
  function setCart(c){ localStorage.setItem('luxehome_cart', JSON.stringify(c)); updateCartCount(); }
  function updateCartCount(){ document.getElementById('cartCount2').textContent = getCart().length; }
  updateCartCount();

  document.getElementById('addBtn').addEventListener('click', () => {
    const qty = parseInt(document.getElementById('qty').value) || 1;
    const cart = getCart();
    const existing = cart.find(i=>i.id===PRODUCT.product_id);
    if(existing) existing.qty += qty; 
    else cart.push({ id: PRODUCT.product_id, title: PRODUCT.name, desc: PRODUCT.desc ,price: PRODUCT.price,img: PRODUCT.img,qty });
    setCart(cart);
    document.getElementById('addedMsg').classList.remove('hidden');
    setTimeout(()=> document.getElementById('addedMsg').classList.add('hidden'), 1800);
  });

  document.getElementById('buyBtn').addEventListener('click', () => {
    alert('Dummy checkout — payment integration not implemented in this demo.');
  });
</script>

</body>
</html>
