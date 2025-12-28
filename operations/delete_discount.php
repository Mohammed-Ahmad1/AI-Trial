<?php
require_once __DIR__ . '/../PHPLogicPages/DiscountsLogic.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    DeleteDiscountByProduct($_POST['product_id']);
}

header("Location: products.php");
exit;
