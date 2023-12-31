<?php

use Doctrine\ORM\EntityManager;

require_once dirname(__DIR__, 2) . '/bootstrap.php';

/** @var EntityManager $em */
$em = $entityManager;
$productId = $_GET['id'];
/** @var \App\Entity\Product $product */
$product = $em->getRepository(\App\Entity\Product::class)->find($productId);
$title = $product->getName();

include dirname(__DIR__, 1) . '/includes/site-header.php';
?>


<!-- Night button -->
<!-- End night button -->

<!-- Begin page content -->
<main class="flex-shrink-0">
    <div class="container">
        <h1 class="mt-5"><span style="color: red;">$<?php echo number_format($product->getPrice() / 100, 2); ?></span> <?php echo $product->getName(); ?></h1>
        <p class="lead"><?php echo $product->getDescription(); ?></p>
        <a href="/products/single-product-checkout.php?id=<?php echo $product->getId(); ?>"  class="btn btn-primary">BUY IT NOW</a>
    </div>
</main>

<footer class="footer mt-auto py-3 bg-body-tertiary">
    <div class="container">
        <span class="text-body-secondary">Place sticky footer content here.</span>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>
</html>

