<?php

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

// Include the Composer autoload file
require_once __DIR__ . '/../vendor/autoload.php';

// Get HTML content from the POST request
$htmlContent = $_POST['htmlContent'];

// Create a new PhpWord instance
$phpWord = new PhpWord();

// Add a new section to the document
$section = $phpWord->addSection();

// Add HTML content to the section
\PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlContent);

// Save the document
$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save('barcodes.docx');

// Output success response
echo json_encode(['success' => true]);
