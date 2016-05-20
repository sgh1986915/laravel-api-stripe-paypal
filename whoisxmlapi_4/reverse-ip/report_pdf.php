<?php
require_once('../../tcpdf/config/lang/eng.php');
require_once('../../tcpdf/tcpdf.php');

class report_pdf extends TCPDF {

   

    // Colored table
    public function ColoredTable($header, $response) {
        // Colors, line width and bold font
        $data = $response->rows;
        $this->SetFillColor(0, 93, 155);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(100, 100);
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');

        // Data
        $fill = 0;
        foreach($data as $row) {
            $cells = $row['cell'];   
            $num_col = count($cells);
            for($i=0;$i<$num_col;$i++){
              $link=false;
              if($i==0){
                $link = "http://whois.whoisxmlapi.com/".$cells[$i];
                $this->Cell($w[$i], 6, $cells[$i], 'LR', 0, 'L', $fill, $link);
              }
              else $this->Cell($w[$i], 6, $cells[$i], 'LR', 0, 'L', $fill, $link);
                  //$this->MultiCell($w[$i], 0, $cells[$i], 1, 'J', 1, 1, '', '', true, 0, false, true, 0);
            }
            $this->Ln();
            $fill=!$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
   
}
?>