<?php
require_once('../libs/fpdf.php'); // Include the FPDF library
include('../db.php'); // Include database connection

// Get sale ID from URL
if (!isset($_GET['id'])) {
    die("No invoice ID provided.");
}

$sale_id = intval($_GET['id']); // Sanitize the sale ID

// Fetch sale info with customer and product details
$sql = "SELECT s.id, s.quantity, s.total_price, s.sale_date,
               p.name AS product_name, p.price AS unit_price,
               c.name AS customer_name, c.mobile AS customer_mobile
        FROM sales s
        JOIN products p ON s.product_id = p.id
        JOIN customers c ON s.customer_id = c.id
        WHERE s.id = $sale_id";

$result = $conn->query($sql);
if ($result->num_rows === 0) {
    die("Sale not found.");
}

$data = $result->fetch_assoc();

// Create PDF
$pdf = new fpdf();
$pdf->AddPage();

// Set up title for the PDF
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Invoice #' . $data['id'], 0, 1, 'C');

$pdf->Ln(10); // Add space after title
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 8, 'Date: ' . $data['sale_date'], 0, 1);
$pdf->Cell(100, 8, 'Customer: ' . $data['customer_name'], 0, 1);
$pdf->Cell(100, 8, 'Mobile: ' . $data['customer_mobile'], 0, 1);

$pdf->Ln(10); // Add space before the table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(80, 8, 'Product', 1);
$pdf->Cell(30, 8, 'Qty', 1);
$pdf->Cell(40, 8, 'Unit Price', 1);
$pdf->Cell(40, 8, 'Total', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(80, 8, $data['product_name'], 1);
$pdf->Cell(30, 8, $data['quantity'], 1);
$pdf->Cell(40, 8, number_format($data['unit_price'], 2), 1);
$pdf->Cell(40, 8, number_format($data['total_price'], 2), 1);
$pdf->Ln(15); // Add space after table

// Footer message
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Thank you for your purchase!', 0, 1, 'C');

// Output
