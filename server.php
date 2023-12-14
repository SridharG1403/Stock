<?php

$db = new SQLite3('stock.db');

// Create table if not exists
$db->exec('CREATE TABLE IF NOT EXISTS needed_stock (id INTEGER PRIMARY KEY, item TEXT)');
$db->exec('CREATE TABLE IF NOT EXISTS available_stock (id INTEGER PRIMARY KEY, item TEXT)');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  if (isset($_GET['action']) && isset($_GET['item'])) {
    $action = $_GET['action'];
    $item = $_GET['item'];

    switch ($action) {
      case 'addNeed':
        $db->exec("INSERT INTO needed_stock (item) VALUES ('$item')");
        break;

      case 'addHave':
        $db->exec("INSERT INTO available_stock (item) VALUES ('$item')");
        break;

      case 'remove':
        $db->exec("DELETE FROM needed_stock WHERE item='$item'");
        $db->exec("DELETE FROM available_stock WHERE item='$item'");
        break;
    }
  }
}

// Fetch items for needed stock
$neededResult = $db->query('SELECT * FROM needed_stock');
$neededStock = [];
while ($row = $neededResult->fetchArray()) {
  $neededStock[] = $row['item'];
}

// Fetch items for available stock
$availableResult = $db->query('SELECT * FROM available_stock');
$availableStock = [];
while ($row = $availableResult->fetchArray()) {
  $availableStock[] = $row['item'];
}

$response = [
  'neededStock' => $neededStock,
  'availableStock' => $availableStock,
];

echo json_encode($response);

$db->close();
?>
