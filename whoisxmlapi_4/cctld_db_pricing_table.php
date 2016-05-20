<?php
	require_once __DIR__ .'/util/number_util.php';
	require_once __DIR__ .'/models/cctld_whois_database_product.php';
	$cctld_wdb_ids = $_REQUEST['cctld_wdb_ids'];
	$cctld_whois_db_type = $_REQUEST['cctld_whois_db_type'];
	if(!$cctld_whois_db_type) $cctld_whois_db_type = "whois_records";
	
	$CCTLDDBProduct = new CCTLDWhoisDatabaseProduct();
?>

<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center" width="100%">
              <tr>      
              	<td align="right" class="header" colspan=1><input class="select_all" type="checkbox" title="select all"/></td>       
                <td align="right" class="header" colspan=1>Description</td>        
                <td align="right" class="header">Price (Domains)
                <?php if($cctld_db_show_input): ?>
                	<input type="radio" name="cctld_whois_db_type" value="domain_names" <?=$cctld_whois_db_type=='domain_names'?'checked':''?>>
                <?php endif ?>	
                </td>
                <td align="right" class="header">Price (Whois)
                <?php if($cctld_db_show_input): ?>
                	<input type="radio" name="cctld_whois_db_type" value="whois_records" <?=$cctld_whois_db_type=='whois_records'?'checked':''?>>
                <?php endif?>
                </td>
              </tr>
              <?php 
              	//print_r($cctld_db_products);
              	$i=0;
              	$cctld_db_products = $CCTLDDBProduct->get_products();
              	$ordered_cctld_db_product_items=$CCTLDDBProduct->get_product_items_by_ids($cctld_wdb_ids);
              	$total_price = "";
        		if($ordred_cctld_db_product_items && count($ordred_cctld_db_product_items)>0) $total_price = $CCTLDDBProduct->get_product_items_price($ordered_cctld_db_product_items, 'domain_names_price');
              	foreach($cctld_db_products as $pkey=>$product_group){
              		

              		$cellstyle=($i++%2==0?"evcell":"oddcell");
              		$available=false;
              		foreach($product_group as $p){
            				
            			if(!$p['whois_unavailable'])$available=true;
            				
              		}
              	?> 
              	<tr>
              		<td colspan="4" class="oddcell">
              			<?php echo $available?"<b>":""?> <?=$pkey?>
              		</td>
              	</tr>
              	
            	
            		<?php 
            			foreach($product_group as $p){
            				$id=$p['id'];
            				$name=$p['name'];
              				$description= number_format($p['num_domains']);
              				if($p['total_domains']){
              					$percent_coverage= round(100*($p['num_domains'] / $p['total_domains']));
              					$description .= " / ". number_format($p['total_domains']) . "($percent_coverage% coverage)";
              				}
              				$description .= " domains<br/> ". $p['description'];
              				
              				
              				if($p['whois_unavailable']){
              					$description .= 'whois data to be delivered within 3 business days to 1 month upon order';
              				}
              				
              				$detail=$p['detail'];
              				$domains_price=format_price($p['domain_names_price']);
              				$parsed_whois_price=format_price($p['parsed_whois_price']);
              				$checked = ($cctld_wdb_ids ? in_array($id, $cctld_wdb_ids) : false);
            		?>	
            		<tr>
            			<?php if($cctld_db_show_input){ ?>
            				<td align="right" class="evcell">
              					<input type="checkbox" value="<?php echo $id?>" name="cctld_wdb_ids[]" <?=($checked ?"checked":"")?> domain_names_price="<?=$p['domain_names_price']?>"  whois_records_price="<?=$p['parsed_whois_price']?>"/>
              				</td>
              			<?php }?>
                		<td align="right" class="evcell" <?php if(!$cctld_db_show_input) echo "colspan=2";?> > <?=$description?></td>
                	
                		<td align="right" class="evcell"><?=$domains_price?></td>
              			<td align="right" class="evcell"><?=$parsed_whois_price?></td>
              		</tr>
              		<?php }?>	
              <?php }
              	$cellstyle=($i++%2==0?"evcell":"oddcell");
              ?>
              
              
              <?php if($cctld_db_show_input): ?>	
              <tr>      
              	<td align="right" class="header" colspan=1><input class="select_all" type="checkbox" title="select all"/></td>
                <td align="right" class="header" colspan=1>Total Price: </td>        
                <td align="right" class="header" id="cctld_db_domain_names_total_price" <?php if($cctld_whois_db_type!='domain_names') echo 'style=\"color:#D3D3D3\"'; ?> > <?=$total_price?></td>
                <td align="right" class="header" id="cctld_db_whois_records_total_price" <?php if($cctld_whois_db_type!='whois_records') echo 'style=\"color:#D3D3D3\"'; ?> ><?=$total_price?></td>
              </tr>              	    
              <?php endif; ?>		
</table>
<?php if($cctld_db_show_input): ?>
	<script type="text/javascript">
		$(document).ready(function(){
			var inputs=$('input:checkbox[name=cctld_wdb_ids[]]');
			inputs.click(function(){
				update_cctld_whois_db_price();
			});
			var type_radio=$('input:radio[name=cctld_whois_db_type]');
			type_radio.click(function(){
				update_cctld_whois_db_price();
			});
			
			$('.select_all').click(function(){
				var $this = $(this);
  
    			if ($this.is(':checked')) {
        			$('input:checkbox[name=cctld_wdb_ids[]]').attr('checked','checked');
    			} else {
    				$('input:checkbox[name=cctld_wdb_ids[]]').attr('checked','');
    			}
    			update_cctld_whois_db_price();
			});
		});
		function update_cctld_whois_db_price(){
			var inputs=$('input:checked[name=cctld_wdb_ids[]]');
			var type=$('input:checked[name=cctld_whois_db_type]').val();
			var types=['domain_names','whois_records'];
			for(var i=0;i<types.length;i++){
				var total_price = PriceUtil.compute_cctld_wdb_items_total_price(inputs, types[i]);
				var field=$('#cctld_db_'+types[i]+'_total_price');
				field.html('$'+total_price);
				if(types[i]==type){
					field.css("color","white");
				}
				else field.css("color","#D3D3D3");
			}
		}
	</script>
	
<?php endif;?>            
            