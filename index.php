perfume-website/
│
├── index.php            # Homepage with perfume items
├── buy.php              # Product-specific order form
├── reviews.php          # Customer reviews
├── db.php               # Database connection
├── style.css            # Site-wide styles (same color theme)
├── script.js            # Logo splash animation
│
├── images/              # Perfume images
│   ├── perfume1.jpg
│   ├── ... (up to 10)
│   └── logo.png
│
└── sql/
    └── schema.sql       # MySQL script to create tables

--- db.php ---
<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'perfume_store';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}
?>

--- sql/schema.sql ---
CREATE DATABASE IF NOT EXISTS perfume_store;
USE perfume_store;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  image VARCHAR(255),
  price DECIMAL(10,2)
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT,
  customer_name VARCHAR(255),
  contact VARCHAR(50),
  address TEXT,
  payment_method VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  review TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--- index.php ---
<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfume Elegance</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
  <div id="logo-splash">
    <img src="images/logo.png" alt="Logo" class="logo-animation">
  </div>
  <nav>
    <div class="logo">Perfume Elegance</div>
    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>
      <li><a href="reviews.php">Reviews</a></li>
    </ul>
  </nav>
  <section class="gallery">
    <?php
    $result = $conn->query("SELECT * FROM products");
    while($row = $result->fetch_assoc()): ?>
      <div class="product-card">
        <img src="images/<?= $row['image'] ?>" alt="<?= $row['name'] ?>">
        <h3><?= $row['name'] ?></h3>
        <p>&#8358;<?= number_format($row['price'], 2) ?></p>
        <a href="buy.php?product_id=<?= $row['id'] ?>" class="buy-btn">Buy Item</a>
      </div>
    <?php endwhile; ?>
  </section>
</body>
</html>

--- buy.php ---
<?php include 'db.php';
$id = $_GET['product_id'];
$product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'];
  $contact = $_POST['contact'];
  $address = $_POST['address'];
  $payment = $_POST['payment'];
  $conn->query("INSERT INTO orders (product_id, customer_name, contact, address, payment_method)
                VALUES ($id, '$name', '$contact', '$address', '$payment')");
  echo "<script>alert('Order placed successfully!'); window.location='index.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Buy <?= $product['name'] ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Order: <?= $product['name'] ?> - &#8358;<?= number_format($product['price'], 2) ?></h2>
  <form method="post">
    <input name="name" required placeholder="Full Name">
    <input name="contact" required placeholder="Contact Number">
    <input name="address" required placeholder="Delivery Address">
    <select name="payment">
      <option value="Card">Card</option>
      <option value="Cash">Cash</option>
      <option value="Transfer">Transfer</option>
    </select>
    <button type="submit">Confirm Purchase</button>
  </form>
</body>
</html>

--- reviews.php ---
<?php include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'];
  $review = $_POST['review'];
  $conn->query("INSERT INTO reviews (name, review) VALUES ('$name', '$review')");
}
$reviews = $conn->query("SELECT * FROM reviews ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Customer Reviews</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Customer Reviews</h2>
  <form method="post">
    <input name="name" placeholder="Your Name" required>
    <textarea name="review" placeholder="Your Review" required></textarea>
    <button type="submit">Submit Review</button>
  </form>
  <div class="reviews">
    <?php while($r = $reviews->fetch_assoc()): ?>
      <div class="review-card">
        <h4><?= $r['name'] ?></h4>
        <p><?= $r['review'] ?></p>
        <small><?= $r['created_at'] ?></small>
      </div>
    <?php endwhile; ?>
  </div>
</body>
</html>

--- style.css ---
:root {
  --ash: #f2f2f2;
  --ash-dark: #1f1f1f;
  --light-ash: #e6e6e6;
  --teal: #007777;
  --deep-teal: #004d4d;
  --hover-teal: #009999;
  --text-dark: #222;
  --text-light: #ddd;
  --bg-card: #f9f9f9;
  --bg-card-dark: #2c2c2c;
}
body { background: var(--ash); color: var(--deep-teal); font-family: sans-serif; margin: 0; }
nav { display: flex; justify-content: space-between; background: var(--light-ash); padding: 1rem; }
.logo { font-size: 1.5rem; color: var(--teal); }
.nav-links { display: flex; gap: 1rem; list-style: none; }
.gallery { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; padding: 2rem; }
.product-card, .review-card { background: var(--bg-card); padding: 1rem; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
img { max-width: 100%; border-radius: 10px; }
form { display: flex; flex-direction: column; gap: 1rem; padding: 2rem; }
input, textarea, select { padding: 0.5rem; border: 1px solid #ccc; border-radius: 5px; }
button, .buy-btn { padding: 0.7rem; background: var(--teal); color: white; border: none; border-radius: 5px; text-decoration: none; text-align: center; }

--- script.js ---
document.addEventListener('DOMContentLoaded', () => {
  const splash = document.getElementById('logo-splash');
  setTimeout(() => {
    splash.style.display = 'none';
  }, 2000);
});
