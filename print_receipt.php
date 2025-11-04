<?php
session_start();
require 'config.php';
require_once('tcpdf/tcpdf.php'); // Adjust path

$order_id = $_GET['order_id'];
$order = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$order->execute([$order_id]);
$o = $order->fetch();

$items = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$items->execute([$order_id]);
$order_items = $items->fetchAll();

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Receipt for Order #' . $order_id, 0, 1);
$pdf->Cell(0, 10, 'Total: $' . $o['total'], 0, 1);
foreach ($order_items as $item) {
    $pdf->Cell(0, 10, $item['name'] . ' x' . $item['quantity'] . ' @ $' . $item['price'], 0, 1);
}
$pdf->Output('receipt.pdf', 'I');
?>