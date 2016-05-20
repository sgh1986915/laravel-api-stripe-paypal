<?php 

	include __DIR__."/../price.php";

	class PriceCalc
	{
		public static function calculateWhoisQueryPrice($input_units, $option)
		{
			global $extendedMembershipPrices, $extendedQueryPrices;

            $membershipPrices = $extendedMembershipPrices;
            $queryPrices = $extendedQueryPrices;

			$units = $input_units;
			$prices = $membershipPrices;

			if ($option['one_time']) {
				$prices=$queryPrices;
			}

			$prev_price = 0;
			$prev_units = 0;
			$resPrice = 0;

			foreach ($prices as $numQueries=>$price) {
				//echo "$numQueries $price<br/>";
				if ($numQueries >= $units) { 
					$resPrice += $prev_price;
					$units -= $prev_units;
					$resPrice += $units * ($price-$prev_price) / ($numQueries-$prev_units);
					//echo "formula is resPrice=$prev_price+$units * ($price-$prev_price)/($numQueries-$prev_units)<br/>";
					break;
				}
		
				$prev_price = $price;
				$prev_units = $numQueries;
			}

			$resPrice = round($resPrice);

			return $resPrice;
		}
	}
?>