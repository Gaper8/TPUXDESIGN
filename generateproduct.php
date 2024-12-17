<?php
require_once 'vendor/autoload.php';

use Faker\Factory;

//database connection.
$db = new PDO('sqlite:ProductsUIUX.db');
//data generation using faker in french.
$faker = Factory::create('fr_FR');

//checks if there is already data in the database. Gets the number of items using fetchcolumn.
$query = $db->query("SELECT COUNT(*) FROM products");
$dbdata = $query->fetchColumn();

if ($dbdata > 0) {
    echo "Des données existent déjà.";
    exit();
}

//inserting the 1000 products and creating fake data for each column of the database.
for ($i = 0; $i < 1000; $i++) {
    $name = $faker->realText(20);
    $description = $faker->realText(100);
    $price = $faker->randomFloat(2, 1, 100);
    $image_url = $faker->imageUrl(50, 50, 'products', false);

    //prepares and then executes the sql queries.
    $stmt = $db->prepare("INSERT INTO products (name, description, price, image_url) VALUES (:name, :description, :price, :image_url)");
    $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':price' => $price,
        ':image_url' => $image_url,
    ]);
}

echo "Données ajoutées";

?>

