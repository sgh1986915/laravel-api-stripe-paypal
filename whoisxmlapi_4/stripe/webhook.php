<?php
	require_once __DIR__ . "/../business_def.php";
	require_once __DIR__ . "/../util/string_util.php";
	require_once __DIR__ . "/../payment/payment_util.php";
	require_once(__DIR__."/../../stripe-php/stripe-php-1.6.5/lib/Stripe.php");
	require_once __DIR__ ."/../invoice/invoice.php";
	require_once __DIR__ ."/../domain_name_data/DomainNameDataAccountManager.php";
	
	$SEND_MONTHLY_EMAIL=0;
	foreach ($STRIP_API_SECRET_KEYS as $secret_key){
		$STRIP_API_CURRENT_SECRET_KEY=$secret_key;
		receive_event();
	}
	function receive_event(){
		global $STRIP_API_CURRENT_SECRET_KEY;
		//echo "key is $STRIP_API_CURRENT_SECRET_KEY\n";
		
		Stripe::setApiKey($STRIP_API_CURRENT_SECRET_KEY);
 
 		// retrieve the request's body and parse it as JSON
  		$body = @file_get_contents('php://input');
  		//$body = file_get_contents('test_webhook_cancel_sub.json');
 		$event = json_decode($body);
 		
 		
 		payment_log($body);

 		if($event)process_payment($event);
 		
	}
	function process_payment($event){
		global $SEND_MONTHLY_EMAIL;
		if($event->type=='invoice.payment_succeeded'){
			$data=$event->data->object->lines;

			if($data){
				if(is_array($data->data)){
					foreach($data->data as $sub){
						$plan = $sub->plan;
						process_plan($plan,$event);
					}
				}
				else{
					if(is_array($data->subscriptions)){
						foreach($data->subscriptions as $sub){
							$plan = $sub->plan;
							process_plan($plan,$event);
						}
						
					}
				}
			}
			

        }
        else if($event->type=='customer.subscription.deleted'){
        	cancel_subscription($event);
        }

	}
	
	function cancel_subscription($event){

		
		try{
			$data=$event->data;
			if(!$data)return;
			$object=$data->object;
			if(!$object)return;
			$plan=$object->plan;
			if(!$plan)return;
			if($plan->interval!='month')return;
			$item_name=$plan->id;
			$customer_id=$object->customer;
			$customer=Stripe_Customer::retrieve($customer_id);
			if(!$customer)return;
			
			//subscr_date=09:56:35 Jan 13, 2014 PST 
			$start_date_epoc=$object->start;
			$start_date=gmdate('H:m:s F j, Y T', $start_date_epoc);
			$end_date_epoc=$object->current_period_end;
			$end_date=gmdate('H:m:s F j, Y T', $end_date_epoc);
			
			$customer_email=$customer->email;
                        $params=array(
                            'item_name'=>$item_name,
                            'subscr_date'=>$start_date,
                            'payer_email'=>$customer_email
                        );

                        $output=array();
                        if(!DomainNameDataAccountManager::deleteAccountFromOrderItem($params, $output)){
                            notifyMeOrderProcessed(array('subject'=>'failed to delete newly registered domains account',
                                'body'=>'params: '.print_r($params,1) . "\n"
                                ."output error: ".print_r($output,1)
                            ));
                        }
                        else{
                        	
                        	//$end_date=DomainNameDataAccountManager::calculateSubscriptionEndDateFromItemName($start_date, $item_name);
                        	 $end_date=DomainNameDataAccountManager::convertDateFormat($end_date);
                            notifyMeOrderProcessed(array('subject'=>"plan to delete newly registered domains account on $end_date",
                                'body'=>'params: '.print_r($params,1) . "\n"
                                    ."output error: ".print_r($output,1)
                            ));
                        }
                        $to=$p->ipn_data['payer_email'];
                        emailOrderCancelled($to, $params,'domain_data');

                    } catch (Exception $e) {
                        error_log('Caught exception: '.  $e->getMessage(). "\n");
                    }
	}
	function process_plan($plan,$event){
		global $SEND_MONTHLY_EMAIL;
		$valid_process=0;
			if(is_whoisapi_subscription_plan($plan)){
						$customer_id=$event->data->object->customer;
						$membership = get_whois_api_subscription_plan($plan, $customer_id);

						$payperiod=$membership['payperiod'];
						$query_amount=$membership['query_amount'];
						$order_username=$membership['order_username'];
						$customer=$membership['customer'];

						$description=ucwords($payperiod) . "ly membership subscription of $query_amount queries/month for $order_username";
						if($customer){
                            $valid_process=1;
							$customer_email=$customer->email;
							payment_log("fill customer $customer_email, username is $order_username\n");
                            
							$fulfillRes = whoisFillQuantity($query_amount, $order_username, array('membership_payperiod'=>$payperiod));
							print_r($fulfillRes);
							if($fulfillRes && $SEND_MONTHLY_EMAIL){
								try{
									
								 emailOrderProcessed($customer_email, array('accountUserName'=>$order_username,
								'orderQuantity'=>$query_amount, 'item_name'=>$description,
								'extraBody' =>"Your account's query balance will be reset to $query_amount every month on day ".date('d') ."."
								));
								}catch(Exception $ex){
									print_r($ex->getMessage());
								}
							}

								
						}
			}
			
			
 		if($valid_process){

            markInvoiceToGenerate($event, array('username'=>$order_username,"customer"=>$customer));
        }
       
	}
	
	function markInvoiceToGenerate($cc_event,  $invoice_data){
		$cc_data=$cc_event->data;
		$customer=$invoice_data['customer'];
		
		$username=$invoice_data['username'];
		$to = $customer->email;
		
		$invoice_num="cc_".$cc_data->object->id;
		$invoice_desc="";
		$invoice_file_path=Invoice::generateInvoiceFilePath(array('username'=>$username, 'email_to'=>$to, 'invoice_num'=>$invoice_num));
	
		//content data
		$sendTo=$to;

		$paymentGross= $cc_data->object->total;
		$paymentGross = "$". $paymentGross/100;
		
		$paymentDate=Invoice::normalizeInvoiceDate($cc_event->created);

		$invoiceContent = array(
			"invoiceNumber"=>$invoice_num,
			"invoiceDate"=>$paymentDate,
			"sendTo"=>$sendTo,
			
			"subtotal"=>$paymentGross,
			"totalPrice"=>$paymentGross
		);
		
		
		
		$data=$cc_event->data->object->lines;
			
		$itemLineData= array();
		
		if($data){
			
			if(is_array($data->data) && count($data->data)>0){
				$data_items=$data->data;
				
			}
			else if(is_array($data->subscriptions) && count($data->subscriptions)>0){
				$data_items=$data->subscriptions;
				
			}
			foreach($data_items as $sub){

				$plan = $sub->plan;
                $description = $plan->name;
                if($username){
                    $description=$description. " for $username";
                }
				$itemLineData[]=array(
					"Quantity"=> "1",
					"Number"=>"1",
					"Description"=>$description ,
					"UnitPrice"=>$plan->amount/100,
					"Price"=>$plan->amount/100,
				);
			}
		}
		
		if(count($itemLineData)>0){
				
			$i=1;
			foreach($itemLineData as $item){
				foreach($item as $itemKey=>$itemVal){
					$invoiceContent["item$i". $itemKey] = $itemVal;
				}
				$i++;
			}
			$res = array('invoice_num'=>$invoice_num, 
										'invoice_desc'=>$invoice_desc, 
										'username'=>$username, 
										'invoice_content'=>json_encode($invoiceContent),
										 'invoice_file_path'=>$invoice_file_path,
										 'email_to'=>$to
							);

            print_r($res);
			Invoice::markInvoiceToCreate($res);
		}
	}

	function is_whoisapi_subscription_plan($plan){
		$id=$plan->id;
		return StringUtil::startsWith($id,"whoisapi_month") || StringUtil::startsWith($id,"whoisapi_year");
	}
	function get_whois_api_subscription_plan($plan, $customer_id){
		
		try{	
			$customer=Stripe_Customer::retrieve($customer_id);

			$description=$customer->description;
		
			$occ="ly membership subscription of ";
			$index = stripos($description, $occ);

			if($index >0){
				$order_username = trim(substr($description, strrpos($description, " for ") + strlen(" for ")));

				$payperiod = substr($description, 0, $index);
				$quantity = substr($description, $index + strlen($occ), strrpos($description, "queries")-strlen($occ)-$index-1);
				$res=array('order_username'=>$order_username, 'payperiod'=>$payperiod, 'query_amount'=>$quantity, 'customer'=>$customer);
				return $res;
			}
		}catch(Exception $e)
		{
		
		}		
		return false;
	}
	
	function payment_log($s){
   $text = '['.date('m/d/Y g:i A').'] - ';
  $f=fopen("payment.log","a") or error_log("can't open file");
  if($f){
    fwrite($f,"$text $s\n");
    fclose($f);
  }
}

?>