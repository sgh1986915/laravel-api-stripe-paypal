<?php
	function getMembership(){
		$membership = false;
		foreach($_REQUEST as $rkey=>$rval){
			if($rkey!=null){
				if(stripos($rkey, "bill_yearly_") ===0){
					$membership = array();
					$membership['query_amount'] = substr($rkey,strlen("bill_yearly_"));
					$membership['payperiod']='year';
				}
				else if(stripos($rkey, "bill_monthly_") ===0){
					$membership = array();
					$membership['query_amount'] = substr($rkey,strlen("bill_monthly_"));
					$membership['payperiod']='month';
				}
			}
		}
		return $membership;
	}
	?>