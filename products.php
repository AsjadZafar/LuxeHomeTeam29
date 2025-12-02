<?php
require_once 'php_functions/dbh.php';

$sql = "SELECT * from products";

$result = mysqli_query($conn, $sql);


?>





<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Products | LuxeHome</title>
  <meta name="description" content="Browse LuxeHome premium smart home products for living room, kitchen, bedroom and outdoors." />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .logo-img{ width:48px; height:48px; border-radius:50%; object-fit:cover }
  </style>
</head>
<body class="bg-gray-50">
  <!-- Header -->
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

        <nav class="hidden md:flex space-x-8">
          <a href="index.php" class="nav-link hover:text-emerald-600 transition">Home</a>
          <a href="products.php" class="nav-link text-emerald-600 font-semibold">Shop</a>
          <a href="#" class="nav-link hover:text-emerald-600 transition">Collections</a>
          <a href="about_us.php" class="nav-link hover:text-emerald-600 transition">Inspiration</a>
          <a href="contact.php" class="nav-link hover:text-emerald-600 transition">Contact</a>
        </nav>

        <div class="flex items-center space-x-4">
          <button id="searchToggle" class="action-btn hover:text-emerald-600"><i class="fas fa-search"></i></button>
          <a href="cart.php" id="viewBasket" class="relative action-btn hover:text-emerald-600">
            <i class="fas fa-shopping-cart"></i>
            <span id="cartCount" class="cart-badge absolute -top-2 -right-2 bg-emerald-600 text-white text-xs px-1.5 rounded-full">0</span>
          </a>
          <a href="#" class="action-btn hover:text-emerald-600"><i class="fas fa-user"></i></a>
        </div>
      </div>
    </div>
  </header>

  <!-- Search Bar -->
  <section class="py-8 bg-gray-100">
    <div class="max-w-6xl mx-auto px-4">
      <div class="bg-white rounded-xl shadow-sm p-6 flex gap-4 items-center">
        <input id="searchInput" type="text" placeholder="Search products, e.g. Smart Lamp, Thermostat"
          class="w-full p-3 border rounded-md focus:ring-emerald-500 focus:border-emerald-500" />
        <select id="categoryFilter" class="p-3 border rounded-md">
          <option value="">All categories</option>
          <option value="Living Room">Living Room</option>
          <option value="Kitchen">Kitchen</option>
          <option value="Bedroom">Bedroom</option>
          <option value="Bathroom">Bathroom</option>
          <option value="Outdoor">Outdoor</option>
        </select>
        <button id="searchBtn" class="bg-emerald-600 text-white px-4 py-3 rounded-md hover:bg-emerald-700">Search</button>
      </div>
    </div>
  </section>

  <!-- Products Grid -->
  <main class="max-w-7xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-semibold mb-6">Featured LuxeHome Products</h2>
    <div id="productsGrid" class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
      <!-- Product cards inserted by JS -->
    </div>
  </main>

  <!-- Footer (same style as contact page) -->
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


  <?php

  $productArray = [];

  while($row = mysqli_fetch_assoc($result)) {

    $productArray[] = [
  "id" => $row["product_id"],
  "title" => $row["name"],
  "desc" => $row["description"],
  "price" => floatval($row["price"]),
  "qty" => intval($row["quantity"]),
  "install" => $row["installation_available"],
  "img" => "product_image/". $row["img"],
  "short" => substr($row["description"], 0, 60) . "...",
];



  }
?>
  <script>
    // Sample product data (9 products)
    const PRODUCTS = <?php echo json_encode($productArray); ?>;

    // render products
    const grid = document.getElementById('productsGrid');
    function formatPrice(p){ return '£' + p.toFixed(2); }
    function renderProducts(list){
      grid.innerHTML = '';
      list.forEach(p => {
        const card = document.createElement('article');
        card.className = 'bg-white rounded-xl shadow p-4 cursor-pointer hover:shadow-md transition';
        card.innerHTML = `
          <a href="productDetails.php?id=${p.id}" class="block">
            <img src="${p.img}" alt="${p.title}" class="w-full h-48 object-cover rounded-md mb-3">
            <h3 class="font-semibold text-lg">${p.title}</h3>
            <p class="text-sm text-gray-500 mb-2">${p.short}</p>
            <div class="flex items-center justify-between">
              <span class="text-emerald-600 font-semibold">${formatPrice(p.price)}</span>
              <span class="text-xs text-gray-400">${p.category}</span>
            </div>
          </a>
        `;
        grid.appendChild(card);
      });
    }

    renderProducts(PRODUCTS);

    // search/filter
    document.getElementById('searchBtn').addEventListener('click', () => applyFilters());
    document.getElementById('searchInput').addEventListener('keydown', e => { if(e.key==='Enter') applyFilters(); });
    document.getElementById('categoryFilter').addEventListener('change', applyFilters);

    function applyFilters(){
      const q = document.getElementById('searchInput').value.trim().toLowerCase();
      const cat = document.getElementById('categoryFilter').value;
      const filtered = PRODUCTS.filter(p => {
        const matchQ = q ? (p.title.toLowerCase().includes(q) || p.short.toLowerCase().includes(q) || p.desc.toLowerCase().includes(q)) : true;
        const matchCat = cat ? p.category === cat : true;
        return matchQ && matchCat;
      });
      renderProducts(filtered);
    }

    // Simple basket using localStorage
    function getCart(){ return JSON.parse(localStorage.getItem('luxehome_cart') || '[]'); }
    function setCart(c){ localStorage.setItem('luxehome_cart', JSON.stringify(c)); updateCartCount(); }
    function updateCartCount(){ document.getElementById('cartCount').textContent = getCart().length; }
    updateCartCount();

    // view basket click -> simple alert or link
    document.getElementById('viewBasket').addEventListener('click', (e)=>{
      e.preventDefault();
      const items = getCart();
      if(items.length===0){ alert('Your basket is empty.'); return; }
      // For demo: show a quick summary and a link to a dedicated basket page (not implemented here)
      const total = items.reduce((s,i)=> s + i.price * i.qty, 0);
      if(confirm(`You have ${items.length} item(s) in your basket. Total: £${total.toFixed(2)}.\n\nGo to basket page?`)){
        window.location.href = 'cart.php';
      }
    });

    // expose addToCart globally so productDetails page can use it
    window.luxeAddToCart = function(productId, qty=1){
      const p = PRODUCTS.find(x=>x.id===productId);
      if(!p) return;
      const cart = getCart();
      const existing = cart.find(i=>i.id===p.id);
      if(existing) existing.qty += qty; else cart.push({ id: p.id, title: p.title, price: p.price, qty });
      setCart(cart);
      alert('Added to basket: ' + p.title);
    }

  </script>
</body>
</html>