<?php
	ob_start('ob_gzhandler');
	session_start();
	require_once 'bibli_generale.php';
	require_once 'fpdf181/fpdf.php';
	error_reporting(E_ALL); 

	class PDF extends FPDF
	{
		function BasicTable($header, $data)
		{
		    // En-tête
		    foreach($header as $col)
		        $this->Cell(80,7,$col,1);
		    $this->Ln();
		    // Données
		    foreach($data as $row)
		    {
		        foreach($row as $col)
		            $this->Cell(80,6,$col,1);
		        $this->Ln();
		    }
		}
	}

	function toPDF($sql)
	{
		$bd = bd_connect();
		$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		$data = array();

		while($tableau = mysqli_fetch_assoc($res)){
			$data[] = array($tableau['cf_ordre'].". ".$tableau['op_contenu'],$tableau['h_ro_res']);
			$titre = $tableau['fi_designation']." - ".$tableau['mo_designation']." ".$tableau['ou_code']; 
		}

		$pdf = new PDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',16);

		$pdf->Cell(30,10,utf8_decode($titre));
		$pdf->Ln();

		$pdf->BasicTable(array('Operation','Resultat'),$data);
		$pdf->Output();	
	}

	if(isset($_POST['sql']))
	{
		toPDF($_POST['sql']);
	}
?>