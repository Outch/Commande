<?php
$pdo = new PDO('mysql:host=localhost;dbname=classicmodels', 'root', 'troiswa');
$pdo->exec('SET NAMES UTF8');

$query = $pdo->prepare
(
    'SELECT
    	orderNumber,
    	customerName,
    	contactFirstName,
    	contactLastName,
    	addressLine1,
    	city
     FROM customers
     INNER JOIN orders ON orders.customerNumber = customers.customerNumber
     WHERE orders.orderNumber = ?'
);

$query->execute(array($_GET["orderNumber"]));
$customer = $query->fetch(PDO::FETCH_ASSOC);

$query = $pdo->prepare
(
    'SELECT
    	orderNumber,
    	ROUND(priceEach, 2) as priceUnit,
    	quantityOrdered,
    	orderLineNumber,
    	productName,
    	ROUND(quantityOrdered * priceEach, 2) as totalPrice
     FROM orderdetails
     INNER JOIN products ON products.productCode = orderdetails.productCode
     WHERE orderdetails.orderNumber = ?
     ORDER BY orderLineNumber
     '
);

$query->execute(array($_GET["orderNumber"]));
$orderDetails = $query->fetchAll(PDO::FETCH_ASSOC);

include "bon.phtml";
?>