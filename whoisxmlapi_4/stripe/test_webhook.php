<?php

require_once __DIR__ . "/../business_def.php";
require_once __DIR__ . "/../util/string_util.php";
require_once __DIR__ . "/../payment/payment_util.php";
require_once(__DIR__."/../../stripe-php/stripe-php-1.6.5/lib/Stripe.php");
require_once __DIR__ ."/../invoice/invoice.php";
require_once __DIR__ ."/../domain_name_data/DomainNameDataAccountManager.php";
	
			$item_name="domain_name_data_enterprise";
	
			$start_date_epoc=1423380751;
			$start_date=gmdate('H:m:s F j, Y T', $start_date_epoc);
			
			$customer_email="topcoder1@gmail.com";
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
                        	$end_date=DomainNameDataAccountManager::calculateSubscriptionEndDateFromItemName($start_date, $item_name);
                        	 
                            notifyMeOrderProcessed(array('subject'=>"plan to delete newly registered domains account on $end_date",
                                'body'=>'params: '.print_r($params,1) . "\n"
                                    ."output error: ".print_r($output,1)
                            ));
                        }
                        $to=$p->ipn_data['payer_email'];
                        emailOrderCancelled($to, $params,'domain_data');
			echo "finished";
                  
                    
                    ?>