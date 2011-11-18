<?php
	//-- Create sample PDF and embed in browser. --//

	// Include WKPDF class.
	require_once('includes/html2pdf.php');
	// Create PDF object.
	$pdf=new WKPDF();
	// Set PDF's HTML
	$pdf->set_html('Hello <b>Mars</b>!');
	// Convert HTML to PDF
	$pdf->render();
	// Output PDF. The file name is suggested to the browser.
	$pdf->output(WKPDF::$PDF_EMBEDDED,'sample.pdf');	
?>