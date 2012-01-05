<?php include('header.php'); ?>
<style type="text/css">
	.sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
	.sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; height: 18px; }
	.sortable li>span { position: absolute; margin-left: -1.3em; margin-top:.4em; }
</style>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
	$(".sortable").sortable();
	$(".sortable > span").disableSelection();
	//if the image already exists (phpcheck) enable the selector

	<?php if($id) : ?>
	//options related
	var ct	= $('#option_list').children().size();
	//create_sortable();
	set_accordion();
	
	// set initial count
	option_count = <?php echo count($product_options); ?>;
	
	<?php endif; ?>
	
	$( ".add_option" ).button().click(function(){
		add_option($(this).attr('rel'));
	});
	$( "#add_buttons" ).buttonset();
	
	photos_sortable();
});

function add_product_image(data)
{
	p	= data.split('.');
	
	var photo = '<?php add_image("'+p[0]+'", "'+p[0]+'.'+p[1]+'", '', '');?>';
	$('#gc_photos').append(photo);
	$('#gc_photos').sortable('destroy');
	photos_sortable();
	
	$('.button').button();
}

function remove_image(img)
{
	if(confirm('Are you sure you want to remove this image?'))
	{
		var id	= img.attr('rel')
		$('#gc_photo_'+id).remove();
	}
}

function photos_sortable()
{
	$('#gc_photos').sortable({	
		handle : '.gc_thumbnail',
		items: '.gc_photo',
		axis: 'y',
		scroll: true
	});
}

function add_option(type)
{
	
	if(jQuery.trim($('#option_name').val()) != '')
	{
		//increase option_count by 1
		option_count++;
		
		$('#options_accordion').append('<?php add_option("'+$('#option_name').val()+'", "'+option_count+'", "'+type+'");?>');
		
		
		//eliminate the add button if this is a text based option
        if(type == 'textarea' || type == 'textfield')
		{
			$('#add_item_'+option_count).remove();
			
		}
		
		add_item(type, option_count);
		
		//reset the option_name field
		$('#option_name').val('');
		reset_accordion();
		
	}
	else
	{
		alert('You must give this Detail a name');
	}
	
}

function add_item(type, id)
{
	
	var count = $('#option_items_'+id+'>li').size()+1;
	
	append_html = '';
	
	if(type!='textfield' && type != 'textarea')
	{
		append_html = append_html + '<li id="value-'+id+'-'+count+'"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><a onclick="if(confirm(\'Are you sure you want to remove this value?\')) $(\'#value-'+id+'-'+count+'\').remove()" class="ui-state-default ui-corner-all" style="float:right;"><span class="ui-icon ui-icon-circle-minus"></span></a>';
	}
	
	append_html += '<div style="margin:2px"><span>Name: </span> <input class="req gc_tf2" type="text" name="option['+id+'][values]['+count+'][name]" value="" /> '+
	'<span>Value: </span> <input class="req gc_tf2" type="text" name="option['+id+'][values]['+count+'][value]" value="" /> '+
	'<span>Weight: </span> <input class="req gc_tf2" type="text" name="option['+id+'][values]['+count+'][weight]" value="" /> '+
	'<span>Price: </span> <input class="req gc_tf2" type="text" name="option['+id+'][values]['+count+'][price]" value="" />';
	
    if(type == 'textfield')
	{
		append_html += ' <span>Limit: </span> <input class="req gc_tf2" type="text" name="option['+id+'][values]['+count+'][limit]" value="" />';
	}

	append_html += '</div> ';
	
	if(type!='textfield' && type != 'textarea')
	{
		append_html += '</li>';
	}
	
	
	$('#option_items_'+id).append(append_html);	
	
	$(".sortable").sortable();
	$(".sortable > span").disableSelection();
	
	
}

function remove_option(id)
{
	if(confirm('Are you sure you want to remove this option?'))
	{
		$('#option-'+id).remove();
		
		option_count --;
		
		reset_accordion();
	}
}

function reset_accordion()
{
	$( "#options_accordion" ).accordion('destroy');
	$('.option_item_form').sortable('destroy');
	set_accordion();
}

function set_accordion(){
	
	var stop = false;
	$( "#options_accordion h3" ).click(function( event ) {
		if ( stop ) {
			event.stopImmediatePropagation();
			event.preventDefault();
			stop = false;
		}
	});
	
	$( "#options_accordion" ).accordion({
		autoHeight: false,
		active: option_count-1,
		header: "> div > h3"
	}).sortable({
		axis: "y",
		handle: "h3",
		stop: function() {
			stop = true;
		}
	});
	

	$('.option_item_form').sortable({
		axis: 'y',
		handle: 'span',
		stop: function() {
			stop = true;
		}
	});
	
	
}
function delete_product_option(id)
{
	//remove the option if it exists. this function is also called by the lightbox when an option is deleted
	$('#options-'+id).remove();
}
//]]>
</script>
<? echo form_open('products/form/'.$id, 'id="product_form"'); ?>
<div class="button_set">
	<input name="submit" type="submit" value="Save Record" />
</div>
<div id="gc_tabs">
	<ul>
		<li><a href="#gc_product_options">Form</a></li>
		<li><a href="#gc_product_photos">Images</a></li>
	</ul>
	<div id="gc_product_options">
	<!-- Master data --> 
		<div class="gc_field2">
		<label for="name">Name: </label>
		<?php
		$data	= array('id'=>'name', 'size'=>'40px', 'name'=>'name',  'value'=>set_value('name', $name), 'class'=>'gc_tf1');
		echo form_input($data);
		?>
		</div>
		<div class="gc_field2">
		<label for="sku">SKU: </label>
		<?php
		$data	= array('id'=>'sku', 'size'=>'25px', 'name'=>'sku', 'value'=>set_value('sku', $sku), 'class'=>'gc_tf1');
		echo form_input($data);
		?>
		</div>
		<div class="gc_field2">
		<label for="price">Price: </label>
		<?php
		$data	= array('id'=>'price', 'name'=>'price', 'size'=>'25px', 'value'=>set_value('price', $price), 'class'=>'gc_tf1');
		echo form_input($data);
		?>
		</div>
		<div class="gc_field2">
		<label for="price">Sale: </label>
		<?php
		$data	= array('id'=>'saleprice', 'name'=>'saleprice', 'size'=>'15px', 'value'=>set_value('saleprice', $saleprice), 'class'=>'gc_tf1');
		echo form_input($data);
		?>
		</div>
		<div class="gc_field2">
		<label for="weight">Weight: </label>
		<?php
		$data	= array('id'=>'weight', 'name'=>'weight', 'size'=>'20px', 'value'=>set_value('weight', $weight), 'class'=>'gc_tf1');
		echo form_input($data);
		?>
		</div>
		<div class="gc_field2">
		<label for="slug">Slug: </label>
		<?php
		$data	= array('id'=>'slug', 'name'=>'slug', 'size'=>'35px','value'=>set_value('slug', $slug), 'class'=>'gc_tf1');
		echo form_input($data);
		?>
		</div>
        <div class="gc_field2">
		<label for="slug">In Stock: </label>
		<?php
		 	$options = array(
                  '1'  => 'In Stock',
                  '0'    => 'Out of Stock'
                );

			echo form_dropdown('in_stock', $options, set_value('in_stock',$in_stock));
		?>
		</div>		
		<!-- Detail data --> 
		<div id="selected_options" class="option_form">
			
				<span id="add_buttons" style="float:right;">
					<input class="gc_tf2" id="option_name" style="width:200px;" type="text" name="option_name" />
					<button type="button" class="add_option" rel="Detail">Add Detail</button>
				</span>

			<br style="clear:both;"/>
			<div id="options_accordion">
			<?php 
				$count	= 0;
				if(!empty($product_options)):
					//print_r($product_options);
					foreach($product_options as $option):
						//print_r($option);
						$option	= (object)$option;
						
						if(empty($option->required))
						{
							$option->required = false;
						}
					?>
						<div id="option-<?php echo $count;?>">
							<h3><a href="#"><?php echo $option->type.' > '.$option->name; ?> </a></h3>
							
							<div style="text-align: left">
								Option Name
								
									<a style="float:right" onclick="remove_option(<?php echo $count ?>)" class="ui-state-default ui-corner-all" ><span class="ui-icon ui-icon-circle-minus"></span></a>
								
								<input class="input gc_tf2" type="text" name="option[<?php echo $count;?>][name]" value="<?php echo $option->name;?>"/>
								
								<input type="hidden" name="option[<?php echo $count;?>][type]" value="<?php echo $option->type;?>" />
								<input class="checkbox" type="checkbox" name="option[<?php echo $count;?>][required]" value="1" <?php echo ($option->required)?'checked="checked"':'';?>/> required
								
								<div class="option_item_form">
						<?php if($option->type!='textarea' && $option->type!='textfield') { ?><ul class="sortable" id="option_items_<?php echo $count;?>"><?php } ?>
								<?php if(!empty($option->values))
											$valcount = 0;
											foreach($option->values as $value) : 
												$value = (object)$value;?>
									
						<?php if($option->type!='textarea' && $option->type!='textfield') { ?><li id="value-<?php echo $count;?>-<?php echo $valcount;?>"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?php } ?>
										<div  style="margin:2px"><span>Name: </span><input class="req gc_tf2" type="text" name="option[<?php echo $count;?>][values][<?php echo $valcount ?>][name]" value="<?php echo $value->name ?>" />
						
										<span>Value: </span><input class="req gc_tf2" type="text" name="option[<?php echo $count;?>][values][<?php echo $valcount ?>][value]" value="<?php echo $value->value ?>" />
										<span>Weight: </span><input class="req gc_tf2" type="text" name="option[<?php echo $count;?>][values][<?php echo $valcount ?>][weight]" value="<?php echo $value->weight ?>" />
										<span>Price: </span><input class="req gc_tf2" type="text" name="option[<?php echo $count;?>][values][<?php echo $valcount ?>][price]" value="<?php echo $value->price ?>" />
										<?php if($option->type == 'textfield'):?>
										<span>Limit: </span><input class="req gc_tf2" type="text" name="option[<?php echo $count;?>][values][<?php echo $valcount ?>][limit]" value="<?php echo $value->limit ?>" />
											<?php endif;?>
										<?php if($option->type!='textarea' && $option->type!='textfield') { ?>
										<a onclick="if(confirm('Are you sure you want to remove this value?')) $('#value-<?php echo $count;?>-<?php echo $valcount;?>').remove()" class="ui-state-default ui-corner-all" style="float:right;"><span class="ui-icon ui-icon-circle-minus"></span></a>
										<?php } ?>
										</div>
										<?php if($option->type!='textarea' && $option->type!='textfield') { ?>
										</li>
										<?php } ?>
										
									
								<?php	$valcount++;
								 		endforeach;  ?>
								 <?php if($option->type!='textarea' && $option->type!='textfield') { ?></ul><?php } ?>
								</div>								
							</div>
						</div>
					<?php 					
					$count++; 
					endforeach;
				endif;
				?>				
				</div>
		</div>
	</div>	
	<div id="gc_product_photos">
		<div class="gc_segment_content">
			<iframe src="<?php echo site_url('products/product_image_form');?>" style="height:25px; border:0px;">
			</iframe>
			<div id="gc_photos">
			<?php
			
			foreach($images as $photo_id=>$photo_obj)
			{
				if(!empty($photo_obj))
				{
					$photo = (array)$photo_obj;
					add_image($photo_id, $photo['filename'], $photo['alt'], $photo['caption'], isset($photo['primary']));
				}
				
			}
			?>
			</div>
		</div>
	</div>
	
</div>
</form>
<?php
function add_image($photo_id, $filename, $alt, $caption, $primary=false)
{	ob_start();
	?>
	<div class="gc_photo" id="gc_photo_<?php echo $photo_id;?>">
		<table cellspacing="0" cellpadding="0">
			<tr>
				<td style="width:81px;padding-right:10px;" rowspan="2">
					<input type="hidden" name="images[<?php echo $photo_id;?>][filename]" value="<?php echo $filename;?>"/>
					<img class="gc_thumbnail" src="<?php echo base_url('uploads/images/thumbnails/'.$filename);?>"/>
				</td>
				<td>
					<input type="radio" name="primary_image" value="<?php echo $photo_id;?>" <?php if($primary) echo 'checked="checked"';?>/> primary
					
					<a onclick="return remove_image($(this));" rel="<?php echo $photo_id;?>" class="button" style="float:right; font-size:9px;">Remove</a>
				</td>
			</tr>
			<tr>
				<td>
					<table>
						<tr>
							<td>Alt Tag</td>
							<td><input name="images[<?php echo $photo_id;?>][alt]" value="<?php echo $alt;?>" class="gc_tf2"/></td>
						</tr>
						<tr>
							<td>Caption</td>
							<td><textarea name="images[<?php echo $photo_id;?>][caption]"><?php echo $caption;?></textarea></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
	<?php
	$stuff = ob_get_contents();
	ob_end_clean();	
	echo replace_newline($stuff);
}
function add_option($name, $option_id, $type)
{
	ob_start();
	?>
	<div id="option-<?php echo $option_id;?>">
		<h3><a href="#"><?php echo $type.' > '.$name; ?></a></h3>
		<div style="text-align: left">
			Option Name
			<span style="float:right">
			
			<a onclick="remove_option(<?php echo $option_id ?>)" class="ui-state-default ui-corner-all" style="float:right;"><span class="ui-icon ui-icon-circle-minus"></span></a></span>
			<input class="input gc_tf1" type="text" name="option[<?php echo $option_id;?>][name]" value="<?php echo $name;?>"/>
			<input type="hidden" name="option[<?php echo $option_id;?>][type]" value="<?php echo $type;?>" />
			<input class="checkbox" type="checkbox" name="option[<?php echo $option_id;?>][required]" value="1"/> required
			
	
			<button id="add_item_<?php echo $option_id;?>" type="button" rel="<?php echo $type;?>"onclick="add_item($(this).attr(\'rel\'), <?php echo $option_id;?>);">Add Item</button>
		  
			<div class="option_item_form" >
				<ul class="sortable" id="option_items_<?php echo $option_id;?>">
				
				</ul>
			</div>
		</div>
	</div>
	<?php
	$stuff = ob_get_contents();
	ob_end_clean();
	echo replace_newline($stuff);
}
//this makes it easy to use the same code for initial generation of the form as well as javascript additions
function replace_newline($string) {
  return (string)str_replace(array("\r", "\r\n", "\n", "\t"), ' ', $string);
}
?>
<script type="text/javascript">
//<![CDATA[

var option_count	= $('#options_accordion>h3').size();
	
var count = <?php echo $count;?>;


function photos_sortable()
{
	$('#gc_photos').sortable({	
		handle : '.gc_thumbnail',
		items: '.gc_photo',
		axis: 'y',
		scroll: true
	});
}

//]]>
</script>
		</div>
	</div>
</div>
</body>
</html>