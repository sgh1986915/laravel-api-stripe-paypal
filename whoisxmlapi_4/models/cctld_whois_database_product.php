<?php
	require_once __DIR__ . "/../util/product_util.php";
	require_once __DIR__ . "/../models/whois_database_product.php";

	class CCTLDWhoisDatabaseProduct extends WhoisDatabaseProduct{
		public static $order_description="The following cctlds are offered as a one-time download only.  You may choose to buy domain names list only or domains with whois records. <br/> Bulk discounts up to 50% off are given when you purchase 2 or more
	databases.  <br/>For cctld where the whois database is not immediately available, you may wait anywhere between 3 business days to a month for it to be delievered.  All number of domains are estimation only.  <br/> <a target=\"_blank\" href=\"mailto:general@whoisxmlapi.com\" class=\"ignore_jssm\">Contact us</a> if you want a custom cctld whois database that's not listed here or want a combo quote to get all the cctlds listed. ";
		private static $cctld_db_products=array(
			'.at database'=>array(
										array('id'=>'at_2013_01',
											'name'=>'.at database (Austria)',
											'description'=> "collected in April 2013",
											'detail'=>'',
											'num_domains'=>1071853,
											'total_domains'=>1181098
										)
									),
			'.au database'=>array(
										array('id'=>'au_2013_01',
											'name'=>'.au database (Australia)',
											'description'=> "collect in April 2013",
											'detail'=>'',
											'num_domains'=>797337,
											'total_domains'=>2546726
										)
									),

				'.be database'=>array(
										array('id'=>'be_2014_02',
											'name'=>'.be database (Belgium)',
											'description'=> "collected in Feb 2014",
											'detail'=>'',
											'num_domains'=> 673113,
											'total_domains'=>1328013
										)	,

										array('id'=>'be_2013_01',
											'name'=>'.be database (Belgium)',
											'description'=> "collected in November 2013",
											'detail'=>'',
											'num_domains'=> 400082,
											'total_domains'=>1328013
										)

								),

					'.br database'=>array(
										array('id'=>'br_2014_01',
											'name'=>'.br database (Brazil)',
											'description'=> "collected in January 2014",
											'detail'=>'',
											'num_domains'=>  1072751
										)
								),
			'.ca database'=>array(
										array('id'=>'ca_2012_04',
											'name'=>'.ca database (Canada)',
											'description'=>'collected in April 2012',
											'detail'=>'',
											'num_domains'=>1115647,
											'total_domains'=>1918188
										)
									),
			'.ch database'=>array(
										array('id'=>'ch_2013_06',
											'name'=>'.ch database (Switzerland)',
											'description'=> "collected in June 2013",
											'detail'=>'',
											'num_domains'=>1064308,
											'total_domains'=>1734170,
										),
										array('id'=>'ch_2014_07',
											'name'=>'.ch database (Switzerland)',
											'description'=> "collected in July 2014",
											'detail'=>'',
											'num_domains'=> 844667,
											'total_domains'=>1734170,
										)
									),
			'.cn database'=>array(
										array('id'=>'cn_2013_06',
											'name'=>'.cn database (China)',
											'description'=> "collected in June 2013",
											'detail'=>'',
											'num_domains'=>2872018,
											'total_domains'=>5709234
										),
                                        array('id'=>'cn_2013_11',
                                            'name'=>'.cn database (China)',
                                            'description'=> "collected in November 2013",
                                            'detail'=>'',
                                            'num_domains'=>1042785,
                                            'total_domains'=>5709234
                                        )
            ),
			'.co database'=>array(
										array('id'=>'co_2013_06',
											'name'=>'.co database (Columbia)',
											'description'=> "collected in June 2013",
											'detail'=>'',
											'num_domains'=>881251,
											'total_domains'=>1350000
										)
									),
			'.co.uk database'=> array(
									array('id'=>'co_uk_2013_11',
										'name'=>'.co.uk database (United Kingdom)',
										'description'=>'collected in November,2013',
										'detail'=>'',
										'num_domains'=>'3850690',
										'total_domains'=>'10179485'
									),
									array('id'=>'co_uk_2012_07',
										'name'=>'.co.uk database (United Kingdom)',
										'description'=>'collected in July,2012',
										'detail'=>'',
										'parsed_whois_price'=>200,
										'num_domains'=>'5479562',
										'total_domains'=>'10179485'
									),
									array('id'=>'co_uk_2012_01',
										'name'=>'.co.uk database (United Kingdom)',
										'description'=>'collected in January, 2012',
										'detail'=>'',
										'domain_names_price'=>100,
										'parsed_whois_price'=>200,
										'num_domains'=>'4659264',
										'total_domains'=>'9909835'
									)
								),

			'.cz database'=>array(
										array('id'=>'cz_2013_01',
											'name'=>'.cz database (Czech Republic)',
											'description'=> "collected in November, 2013",
											'detail'=>'',
											'num_domains'=>   210336 ,
											'total_domains'=>993450

										)
									),
			'.dk database'=>array(
										array('id'=>'dk_2013_06',
											'name'=>'.dk database (Denmark)',
											'description'=>'collected in June 2013',
											'detail'=>'',
											'num_domains'=>552530
										)
									),
			'.de database'=>array(
										array('id'=>'de_2013_01',
											'name'=>'.de database (Germany)',
											'description'=>'collected in October and November, 2013',
											'detail'=>'',
											'num_domains'=>8019424,
											'total_domains'=>15091087
										),
										array('id'=>'de_2012_06',
											'name'=>'.de database (Germany)',
											'description'=>'collected in June and July, 2012',
											'detail'=>'',

											'num_domains'=>'4141359',
											'total_domains'=>15091087
										)
									),


			'.eu database'=>array(
										array('id'=>'eu_2013_05',
											'name'=>'.eu database (European Union)',
											'description'=> "collected in April and Mar 2012",
											'detail'=>'',
											'num_domains'=> 2193520,
											'total_domains'=>3690671
										)
									),
			'.fr database'=>array(
										array('id'=>'fr_2012_09',
											'name'=>'.fr database (France)',
											'description'=>'collected in Sept and Oct, 2012',
											'detail'=>'',
											'num_domains'=>778317,
											'total_domains'=>2415077
										)
									),
			'.ie database'=>array(
										array('id'=>'ie_2012_11',
											'name'=>'.ie database (Ireland)',
											'description'=>'collected in Jan 26th, 2013',
											'detail'=>'',
											'num_domains'=>127623,
											'total_domains'=>182567

										)
									),
			'.in database'=>array(
										array('id'=>'in_2013_01',
											'name'=>'.in database (India)',
											'description'=>'collected in Jan 6th, 2013',
											'detail'=>'',
											'num_domains'=>419435

										)
									),
			'.io database'=>array(
										array('id'=>'io_2014_06',
											'name'=>'.io database (British Indian Ocean Territory)',
											'description'=>'collected in June, 2014',
											'detail'=>'',
											'num_domains'=>4023

										)
									),


			'.it database'=>array(
										array('id'=>'it_2013_06',
											'name'=>'.it database (Italy)',
											'description'=> "collected in June 2013",
											'detail'=>'',
											'num_domains'=>783621,
											'total_domains'=>2470037
										)
									),

			'.jp database'=>array(
										array('id'=>'jp_2013_01',
											'name'=>'.jp database (Japan)',
											'description'=> "collected in August 2013",
											'detail'=>'',
											'num_domains'=> 385733,
											'total_domains'=>1315399
										)
									),
			'.mx database'=>array(
										array('id'=>'mx_2014_07',
											'name'=>'.mx database (mexico)',
											'description'=> "collected in July 2014",
											'detail'=>'',
											'num_domains'=> 279787
										)
									),
			'.nl database'=>array(
										array('id'=>'nl_2013_06',
											'name'=>'.nl database (Netherland)',
											'description'=> "collected in June 2013",
											'detail'=>'',
											'num_domains'=> 1349398,
											'total_domains'=>5081379

										)
									),
			'.no database'=>array(
										array('id'=>'no_2013_10',
											'name'=>'.no database (Norway)',
											'description'=> "collected in October 2013",
											'detail'=>'',
											'num_domains'=> 90904,
											'total_domains'=>600000

										)
									),

            '.nz database'=>array(
                array('id'=>'nz_2013_01',
                    'name'=>'.nz database (New Zealand)',
                    'description'=> "collected between October 2013 and January 2014",
                    'detail'=>'',
                    'num_domains'=> 259176,
                    'total_domains'=>512935
                )
            ),


            '.pl database'=>array(
										array('id'=>'pl_2013_06',
											'name'=>'.pl database (Poland)',
											'description'=> "collected in June 2013",
											'detail'=>'',
											'num_domains'=> 1231216

										)
									),
			'.pt database'=>array(
										array('id'=>'pt_2013_01',
											'name'=>'.pt database (Portugal)',
											'description'=> "",
											'detail'=>'',
											'num_domains'=>  99382

										)
									),
			'.ru database'=>array(
										array('id'=>'ru_2013_01',
											'name'=>'.ru database (Russia)',
											'description'=> "collected in March 2013",
											'detail'=>'',
											'num_domains'=>3863931,
											'total_domains'=>4083772
										)
									),

			'.se database'=>array(
										array('id'=>'se_2013_01',
											'name'=>'.se database (Sweden)',
											'description'=> "",
											'detail'=>'',
											'num_domains'=>491545,
											'total_domains'=>1263911
										)
									),
			'.sk database'=>array(
										array('id'=>'se_2013_01',
											'name'=>'.se database (Slovakia)',
											'description'=> "",
											'detail'=>'',
											'num_domains'=>109745
										)
									),

			//whois not available yet


									/*
			'.br database'=>array(
										array('id'=>'br_2013_01',
											'name'=>'.br database',
											'description'=> "",
											'detail'=>'',
											'num_domains'=>815337,
											'total_domains'=>3073441,
											'whois_unavailable'=>true
										)
									),*/






	/*
			'.es database'=>array(
										array('id'=>'es_2013_01',
											'name'=>'.es database',
											'description'=> "",
											'detail'=>'',
											'num_domains'=>914618,
											'total_domains'=>1612733,
											'whois_unavailable'=>true
										)
									),
*/






		);
		private static $base_price = array('domain_names_price'=>200, 'parsed_whois_price'=>500);
		public static function init(){
			self::init_price();
		}
		private static function init_price(){
			foreach(self::$cctld_db_products as &$p){
				foreach($p as $key=>&$item){
					if(!isset($item['domain_names_price'])){
						$item['domain_names_price']= self::$base_price['domain_names_price'];
					}
					if(!isset($item['parsed_whois_price'])){
						$item['parsed_whois_price'] = self::$base_price['parsed_whois_price'];
					}
					if(isset($item['whois_unavailable']) && $item['whois_unavailable']){ // modified
						$item['parsed_whois_price'] = 900;
					}

				}
			}
		}

		private $apply_discount=true;
		public function get_products(){
			return self::$cctld_db_products;
		}
		public function get_product_items_price($items, $price_field_name='price'){
			$total_price = ProductUtil::get_product_items_total($items,$price_field_name);
			return $this->apply_discount ? $this->apply_discount($total_price,$items) : $total_price;
		}
		private function apply_discount($total_price, $items){
			$max_discount_rate=0.5;
			$discount = min($max_discount_rate, 0.1*count($items));
			if(count($items)>1){
				$total_price = $total_price * (1-$discount);
			}

			return $total_price;
		}
	}
	CCTLDWhoisDatabaseProduct::init();
?>