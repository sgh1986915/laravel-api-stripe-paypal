<?php
	require_once __DIR__ .'/util/number_util.php';
	require_once __DIR__ .'/models/custom_whois_database_product.php';
	$custom_wdb_ids = $_REQUEST['custom_wdb_ids'];
	$customDBProduct = new CustomWhoisDatabaseProduct();
?>

<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center" width="100%">
              <tr>      
                <td align="right" class="header" colspan=2>Description</td>        
                <td align="right" class="header">Price</td>
              </tr>
              <?php 
              	//print_r($custom_db_products);
              	$i=0;
              	$custom_db_products = $customDBProduct->get_products();
              	$ordered_custom_db_product_items=$customDBProduct->get_product_items_by_ids($custom_wdb_ids);
              	$total_price = "";
        		if($ordred_custom_db_product_items && count($ordred_custom_db_product_items)>0) $total_price = CustomWhoisDatabase::get_product_items_price($ordered_custom_db_product_items);
              	foreach($custom_db_products as $pkey=>$product_group){
              		

              		$cellstyle=($i++%2==0?"evcell":"oddcell");
              		
              	?> 
              	<tr>
              		<td colspan="3" class="oddcell">
              			<?=$pkey?>
              		</td>
              	</tr>
              	
            	
            		<?php 
            			foreach($product_group as $p){
            				$id=$p['id'];
            				$name=$p['name'];
              				$description=$p['description'];
              				$detail=$p['detail'];
              				$price=format_price($p['price']);
              				$checked = ($custom_wdb_ids ? in_array($id, $custom_wdb_ids) : false);
            		?>	
            		<tr>
            			<?php if($custom_db_show_input){ ?>
            				<td align="right" class="evcell">
              					<input type="checkbox" value="<?php echo $id?>" name="custom_wdb_ids[]" <?=($checked ?"checked":"")?> price="<?=$p['price']?>"/>
              				</td>
              			<?php }?>
                		<td align="right" class="evcell" <?php if(!$custom_db_show_input) echo "colspan=2";?> > <?=$description?></td>
                	
                		<td align="right" class="evcell"><?=$price?></td>
              	
              		</tr>
              		<?php }?>	
              <?php }
              	$cellstyle=($i++%2==0?"evcell":"oddcell");
              ?>
              <?php if($custom_db_show_input): ?>	
              <tr>      
                <td align="right" class="header" colspan=2>Total Price: </td>        
                <td align="right" class="header" id="custom_db_total_price"><?=$total_price?></td>
              </tr>              	    
              <?php endif; ?>		
</table>
<?php if($custom_db_show_input): ?>
	<script type="text/javascript">
		$(document).ready(function(){
			var inputs=$('input:checkbox[name=custom_wdb_ids[]]');
			inputs.click(function(){
				update_custom_whois_db_price();
			});
			
		});
		function update_custom_whois_db_price(){
			var inputs=$('input:checked[name=custom_wdb_ids[]]');
			var total_price = PriceUtil.compute_custom_wdb_items_total_price(inputs);
			$('#custom_db_total_price').html('$'+total_price);
		}
	</script>
	
<?php endif;?>            
            