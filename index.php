<?php
// We determine which page we are on.
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $currentPage = (int) strip_tags($_GET['page']);
} else {
    $currentPage = 1;
}
try {
    $db = new PDO('sqlite:ProductsUIUX.db');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// We determine the total number of products.
$sql = 'SELECT COUNT(*) AS nb_products FROM products;';
$query = $db->prepare($sql);
$query->execute();
$result = $query->fetch();
$nbProducts = (int) $result['nb_products'];
$perPage = 42;

// We calculate the total number of pages.
$pages = ceil($nbProducts / $perPage);

// Calculation of the 1st product on the page.
$first = ($currentPage * $perPage) - $perPage;

// We retrieve the products for the current page.
$sql = 'SELECT * FROM products ORDER BY id ASC LIMIT :first, :perPage;';
$query = $db->prepare($sql);
$query->bindValue(':first', $first, PDO::PARAM_INT);
$query->bindValue(':perPage', $perPage, PDO::PARAM_INT);

// We execute.
$query->execute();
$products = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Produits</title>
    <!-- Bootstrap. -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <main class="container">
        <div class="row">
            <section class="col-12">
                <h1>Liste des Produits</h1>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Prix</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= $product['id'] ?></td>
                                <td><?= $product['name'] ?></td>
                                <td><?= $product['description'] ?></td>
                                <td><?= $product['price'] ?> €</td>
                                <td><img src="<?= $product['image_url'] ?>" alt=""></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <!-- Link to the previous page (disabled if on the 1st page). -->
                        <li class="page-item <?= ($currentPage == 1) ? 'disabled' : '' ?>">
                            <a href="./?page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
                        </li>
                        <?php for ($page = 1; $page <= $pages; $page++): ?>
                            <!-- Link to each page. -->
                            <li class="page-item <?= ($currentPage == $page) ? 'active' : '' ?>">
                                <a href="./?page=<?= $page ?>" class="page-link"><?= $page ?></a>
                            </li>
                        <?php endfor; ?>
                        <!-- Link to the next page (disabled if on the last page). -->
                        <li class="page-item <?= ($currentPage == $pages) ? 'disabled' : '' ?>">
                            <a href="./?page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
                        </li>
                    </ul>
                </nav>
            </section>
        </div>
    </main>
</body>
</html>
