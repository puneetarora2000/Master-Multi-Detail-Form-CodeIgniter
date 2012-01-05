<?php
class Products extends CI_Controller {	
	
	function __construct()
	{		
		parent::__construct();
		
		$this->load->model('Product_model');
		$this->load->helper('form');
	}	
	// my function form 
	function form($id = false, $duplicate = false)
	{
		$this->product_id	= $id;
		$this->load->library('form_validation');
		$this->load->model('Option_model');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$data['product_list']	= $this->Product_model->get_products();

		//default values are empty if the product is new
		$data['id']					= '';
		$data['sku']				= '';
		$data['name']				= '';
		$data['slug']				= '';
		$data['excerpt']			= '';
		$data['price']				= '';
		$data['saleprice']			= '';
		$data['weight']				= '';
		$data['in_stock'] 			= '';
		$data['images']				= array();
		//create the photos array for later use
		$data['photos']		= array();
		if ($id)
		{	
			$data['product_options']	= $this->Option_model->get_product_options($id);
			$product					= $this->Product_model->get_product($id);

			//if the product does not exist, redirect them to the product list with an error
			if (!$product)
			{
			$this->session->set_flashdata('error', 'The requested product could not be found.');
				redirect('products/form');
			}
			//helps us with the slug generation
			$this->product_name	= $this->input->post('slug', $product->slug);
     		//if we're duplicating the product, then this should not be set
   			if(!$duplicate)
			{
				$data['page_title']	= 'Edit Product';
			}			
			//set values to db values
			$data['id']					= $id;
			$data['sku']				= $product->sku;
			$data['name']				= $product->name;
			$data['slug']				= $product->slug;
			$data['price']				= $product->price;
			$data['saleprice']			= $product->saleprice;
			$data['weight']				= $product->weight;
			$data['in_stock'] 			= $product->in_stock;
			//make sure we haven't submitted the form yet before we pull in the images/related products from the database
			if(!$this->input->post('submit'))
			{
				$data['images']				= (array)json_decode($product->images);
			}
		}
		//no error checking on these
		$this->form_validation->set_rules('caption', 'Caption');
		$this->form_validation->set_rules('primary_photo', 'Primary');
     	$this->form_validation->set_rules('sku', 'SKU', 'trim');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('slug', 'slug', 'trim');
		$this->form_validation->set_rules('price', 'Price', 'trim|numeric');
		$this->form_validation->set_rules('saleprice', 'Sale Price', 'trim|numeric');
		$this->form_validation->set_rules('weight', 'Weight', 'trim|numeric');
		$this->form_validation->set_rules('in_stock', 'In Stock', 'trim|numeric');
		/*
		if we've posted already, get the photo stuff and organize it
		if validation comes back negative, we feed this info back into the system
		if it comes back good, then we send it with the save item
		
		submit button has a value, so we can see when it's posted
		*/
		if($duplicate)
		{
			$data['id']	= false;
		}
		if($this->input->post('submit'))
		{
			//reset the product options that were submitted in the post
			$data['product_options']	= $this->input->post('option');
			$data['images']				= $this->input->post('images');
		}		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('product_form', $data);
		}
		else
		{
			//first check the slug field
			$slug = $this->input->post('slug');
			
			//if it's empty assign the name field
			if(empty($slug) || $slug=='')
			{
				$slug = $this->input->post('name');
			}
			$slug	= url_title($slug, 'dash', TRUE);
			
            // save the products
			$save['id']					= $id;
			$save['sku']				= $this->input->post('sku');
			$save['name']				= $this->input->post('name');
			$save['price']				= floatval($this->input->post('price'));
			$save['saleprice']			= floatval($this->input->post('saleprice'));
			$save['weight']				= floatval($this->input->post('weight'));
			$save['in_stock']			= $this->input->post('in_stock');
			$post_images				= $this->input->post('images');
			
			$save['slug']				= $slug;
			
			if($primary	= $this->input->post('primary_image'))
			{
				if($post_images)
				{
					foreach($post_images as $key => &$pi)
					{
						if($primary == $key)
						{
							$pi['primary']	= true;
							continue;
						}
					}	
				}
				
			}
			
			$save['images']				= json_encode($post_images);
			
			$options	= array();
			if($this->input->post('option'))
			{
				foreach ($this->input->post('option') as $option)
				{
					$options[]	= $option;
				}
			}	
            $product_id	= $this->Product_model->save($save, $options);
			
			if (!$id)
			{
			$this->session->set_flashdata('message', 'The "'.$this->input->post('name').'" product has been added.');
			//echo('The "'.$this->input->post('name').'" product has been added.');
			}
			else
			{
			$this->session->set_flashdata('message', 'Information for the "'.$this->input->post('name').'" product has been updated.');
			//echo('Information for the "'.$this->input->post('name').'" product has been updated.');
			}
			redirect('products/form');
		}
	}
	
	function product_image_form()
	{
		$data['file_name'] = false;
		$data['error']	= false;
		$this->load->view('iframe/product_image_uploader', $data);
	}
	
	function product_image_upload()
	{
		$data['file_name'] = false;
		$data['error']	= false;
		
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= $this->config->item('size_limit');
		$config['upload_path'] = 'uploads/images/full';
		$config['encrypt_name'] = true;
		$config['remove_spaces'] = true;

		$this->load->library('upload', $config);
		
		if ( $this->upload->do_upload())
		{
			$upload_data	= $this->upload->data();
			
			$this->load->library('image_lib');
			/*
			
			I find that ImageMagick is more efficient that GD2 but not everyone has it
			if your server has ImageMagick then change out the line
			
			$config['image_library'] = 'gd2';
			
			with
			
			$config['library_path']		= '/usr/bin/convert';
			$config['image_library']	= 'ImageMagick';
			*/			
			
			//this is the larger image
			$config['image_library'] = 'gd2';
			$config['source_image'] = 'uploads/images/full/'.$upload_data['file_name'];
			$config['new_image']	= 'uploads/images/medium/'.$upload_data['file_name'];
			$config['maintain_ratio'] = TRUE;
			$config['width'] = 600;
			$config['height'] = 500;
			$this->image_lib->initialize($config);
			$this->image_lib->resize();
			$this->image_lib->clear();

			//small image
			$config['image_library'] = 'gd2';
			$config['source_image'] = 'uploads/images/medium/'.$upload_data['file_name'];
			$config['new_image']	= 'uploads/images/small/'.$upload_data['file_name'];
			$config['maintain_ratio'] = TRUE;
			$config['width'] = 235;
			$config['height'] = 235;
			$this->image_lib->initialize($config); 
			$this->image_lib->resize();
			$this->image_lib->clear();

			//cropped thumbnail
			$config['image_library'] = 'gd2';
			$config['source_image'] = 'uploads/images/small/'.$upload_data['file_name'];
			$config['new_image']	= 'uploads/images/thumbnails/'.$upload_data['file_name'];
			$config['maintain_ratio'] = TRUE;
			$config['width'] = 150;
			$config['height'] = 150;
			$this->image_lib->initialize($config); 	
			$this->image_lib->resize();	
			$this->image_lib->clear();

			$data['file_name']	= $upload_data['file_name'];
		}
		
		if($this->upload->display_errors() != '')
		{
			$data['error'] = $this->upload->display_errors();
		}
		$this->load->view('iframe/product_image_uploader', $data);
	}
	
}