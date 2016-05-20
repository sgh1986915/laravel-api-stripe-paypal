<?php require_once dirname(__FILE__)."/ReverseWhoisReport.php";
  $reportSearchTerm = "godaddy";
  $username="root";
  $revWhoisReport = new ReverseWhoisReport($reportSearchTerm);
  $revWhoisReport->generateReport(array("save_to"=>$username));
?>