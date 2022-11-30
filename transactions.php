<?php

require_once('vendor/autoload.php');
require('banking.php');

session_start();
if (!isset($_SESSION["username"]) or !($_SESSION['authenticated'])) {
  header("Location: login.html");
  exit();
}



// reference the Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;
use Dompdf\Exception;


function  filter_order($order)
{
  if ($_REQUEST['order_type'] == 'asc' or $_REQUEST['order_type'] == 'desc') {
    return true;
  } else {
    return false;
  }
}

if (isset($_REQUEST['order_type'])) {
  $order = $_REQUEST['order_type'];
  filter_order($order);
}



// instantiate and use the dompdf class

$dompdf = new Dompdf();
$dompdf->loadHtml('
<html>
<style>
#class1 {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
  }
  
  #class1 td, #class1 th {
    border: 1px solid #ddd;
    padding: 8px;
  }
  
  #class1 tr:nth-child(even){background-color: #f2f2f2;}
  
  #class1 tr:hover {background-color: #ddd;}
  
  #class1 th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #161;
    color: white;
  }
</style>
<h1>Bank Statement</h1>
<p>Here is a list of your transactions</p>
<table id="class1">
<tr>
  <th>Date</th>
  <th>From</th>
  <th>To</th>
  <th>Amount</th>
</tr>
' . get_transactions($_SESSION['username'], $order) . '</table></html>');

$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream("sample.pdf");
