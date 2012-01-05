<?php
Class Product_model extends CI_Model
{
		
	// we will store the group discount formula here
	// and apply it to product prices as they are fetched 
	var $group_discount_formula = false;
	
	function __construct()
	{
		parent::__construct();
		
	}

	function get_products($category_id = false, $limit = false, $offset = false)
	{
			//sort by alphabetically by default
			$this->db->order_by('name', 'ASC');
			$result	= $this->db->get('products');
			//apply group discount
			$return = $result->result();
			
			return $return;		
	}

	
	function get_product($id, $sub=true)
	{
		$result	= $this->db->get_where('products', array('id'=>$id))->row();
		if(!$result)
		{
			return false;
		}		

		return $result;
	}
	


	function get_slug($id)
	{
		return $this->db->get_where('products', array('id'=>$id))->row()->slug;
	}

	function check_slug($str, $id=false)
	{
		$this->db->select('slug');
		$this->db->from('products');
		$this->db->where('slug', $str);
		if ($id)
		{
			$this->db->where('id !=', $id);
		}
		$count = $this->db->count_all_results();

		if ($count > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function save($product, $options=false, $categories=false)
	{
		if ($product['id'])
		{
			$this->db->where('id', $product['id']);
			$this->db->update('products', $product);

			$id	= $product['id'];
		}
		else
		{
			$this->db->insert('products', $product);
			$id	= $this->db->insert_id();
		}

		//loop through the product options and add them to the db
		if($options !== false)
		{
			$obj =& get_instance();
			$obj->load->model('Option_model');

			// wipe the slate
			$obj->Option_model->clear_options($id);

			// save edited values
			$count = 1;
			foreach ($options as $option)
			{
				$values = $option['values'];
				unset($option['values']);
				$option['product_id'] = $id;
				$option['sequence'] = $count;

				$obj->Option_model->save_option($option, $values);
				$count++;
			}
		}
		
		//return the product id
		return $id;
	}
	
	function delete_product($id)
	{
		// delete product 
		$this->db->where('id', $id);
		$this->db->delete('products');

		// delete product in table options 
		$this->db->where('product_id', $id);
		$this->db->delete('options');
		

	}

}