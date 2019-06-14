<?php
namespace Lucent\Hello\Controller\Index;

class Display extends \Magento\Framework\App\Action\Action
{
  public function __construct(\Magento\Framework\App\Action\Context $context)
  {
    return parent::__construct($context);
  }


  public function execute()
  {

  		$attribute_array = array();
		$objectManager =  \Magento\Framework\App\ObjectManager::getInstance();        
		$coll = $objectManager->create(\Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection::class);
		$coll->addIsFilterableFilter();
		$layeredNavAttributes = $coll->load()->getItems();
		$total_attributes =  sizeof($layeredNavAttributes);
		if($total_attributes > 0 ){
			foreach ($layeredNavAttributes AS $attr) {
				$attribute_data = $attr->getData();
				$options = $attr->getSource()->getAllOptions();
				//array_push($attribute_array, $attribute_data['attribute_code']);
				if(sizeof($options) > 1){
					$attribute_array[] = $attribute_data['attribute_code'];
				}
			}
		}

		//echo "<pre>";
		//print_r($attribute_array);
		//echo "</pre>";

		$data = array();
		$data['values'] = array();
		$data['values']['price'] =  '';


		for($aa=0;$aa<sizeof($attribute_array);$aa++){
			$attribute_title = $attribute_array[$aa];
			$attribute_label = $attribute_array[$aa].'Label';
			
				$data['values']['attribute'][$attribute_title] =  array();
				$data['values']['attribute'][$attribute_label] =  array();
				$data['values'][$attribute_title] =  array();
			
		}
		


		
		if(isset($_POST['filterpage']) && $_POST['filterpage']=='catalog'){
			for($bb=0;$bb<sizeof($attribute_array);$bb++){
				$attribute_title = $attribute_array[$bb];
				if(!isset($_SESSION['catpage']['attribute'][$attribute_title])){
					
						$_SESSION['catpage']['attribute'][$attribute_title]  = array();
					
				}
			}
	    }

	    if(isset($_POST['filterpage']) && $_POST['filterpage']=='catalogsearch'){
			for($cc=0;$cc<sizeof($attribute_array);$cc++){
				$attribute_title = $attribute_array[$cc];
				if(!isset($_SESSION['searchpage']['attribute'][$attribute_title])){
					
						$_SESSION['searchpage']['attribute'][$attribute_title]  = array();
					
				}
			}
	    }
	
	

		$objectManager =  \Magento\Framework\App\ObjectManager::getInstance();        
		$categoryFactory = $objectManager->get('\Magento\Catalog\Model\CategoryFactory');
		$categoryHelper = $objectManager->get('\Magento\Catalog\Helper\Category');
		$categoryRepository = $objectManager->get('\Magento\Catalog\Model\CategoryRepository');
		$store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();

	    $attributeCode = '';   
	    if(isset($_POST['attribute_code'])){
		    $attributeCode = $_POST['attribute_code'];             
	    }          
	    $option_id = '';             
	    if(isset($_POST['id'])){
		    $option_id = $_POST['id']; 
	    }   

		if(isset($_POST['filterpage']) && $_POST['filterpage']=='catalog'){
	    	if(!isset($_SESSION['catpage']['price'])){
				$_SESSION['catpage']['price']  = '';
			}
		} 
		if(isset($_POST['filterpage']) && $_POST['filterpage']=='catalogsearch'){
	    	if(!isset($_SESSION['searchpage']['price'])){
				$_SESSION['searchpage']['price']  = '';
			}
		} 
	    	
	    if(isset($_POST['filterpage']) && $_POST['filterpage']=='catalog'){         
			if(isset($_POST['action']) && $_POST['action']=='delete'){
				
				if($attributeCode=='price'){
					$_SESSION['catpage']['price'] = '';
				}
				
			}else{
				
				if($attributeCode=='price'){
					$_SESSION['catpage']['price'] = $option_id;
				}
				
			}
		}
		if(isset($_POST['filterpage']) && $_POST['filterpage']=='catalogsearch'){         
			if(isset($_POST['action']) && $_POST['action']=='delete'){
				
				if($attributeCode=='price'){
					$_SESSION['searchpage']['price'] = '';
				}
				
			}else{
				
				if($attributeCode=='price'){
					$_SESSION['searchpage']['price'] = $option_id;
				}
				
			}
		}      

	    if(isset($_POST['cid']) && $_POST['cid']!=''){
			$categoryId = $_POST['cid'];				// YOUR CATEGORY ID
			$category = $categoryFactory->create()->load($categoryId);
			$categoryProducts = $category->getProductCollection()
			                             ->addAttributeToSelect('*');
			$categoryProducts->addAttributeToFilter('visibility', 4);

		}

		if(isset($_POST['query']) && $_POST['query']!=''){
			$query = $_POST['query'];	
			if($attributeCode=='category'){
				if($_POST['cid']!=''){
					$categoryId = $_POST['cid'];				// YOUR CATEGORY ID
					$category = $categoryFactory->create()->load($categoryId);
					$categoryProducts = $category->getProductCollection()
					                             ->addAttributeToSelect('*');
					                             $categoryProducts->addAttributeToFilter('visibility', 4);
				}else{
					$categoryProducts = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection')
		           ->addAttributeToSelect('*');
		           $categoryProducts->addAttributeToFilter('visibility', 4);
				}
	
			}else{
				$categoryProducts = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection')
	           ->addAttributeToSelect('*');
	           $categoryProducts->addAttributeToFilter('visibility', 4);
			}
			$categoryProducts->addAttributeToFilter('name', array('like' => '%'.$query.'%'));

		}

		
			if(isset($_POST['filterpage']) && $_POST['filterpage']=='catalog'){
				if(isset($_POST['action']) && $_POST['action']=='delete'){
					for($cc=0;$cc<sizeof($attribute_array);$cc++){
						$attribute_title = $attribute_array[$cc];
						if($attribute_title == $attributeCode){
							$del_size_array[] = $option_id;
							$_SESSION['catpage']['attribute'][$attribute_title] = array_diff($_SESSION['catpage']['attribute'][$attribute_title], $del_size_array);
						}
					}
				}else{
					for($cc=0;$cc<sizeof($attribute_array);$cc++){
						$attribute_title = $attribute_array[$cc];
						if($attribute_title == $attributeCode){
							array_push($_SESSION['catpage']['attribute'][$attribute_title], $option_id);
						}
					}
				}
		    }

			if(isset($_POST['filterpage']) && $_POST['filterpage']=='catalogsearch'){
				if(isset($_POST['action']) && $_POST['action']=='delete'){
					for($cc=0;$cc<sizeof($attribute_array);$cc++){
						$attribute_title = $attribute_array[$cc];
						if($attribute_title == $attributeCode){
							$del_size_array[] = $option_id;
							$_SESSION['searchpage']['attribute'][$attribute_title] = array_diff($_SESSION['searchpage']['attribute'][$attribute_title], $del_size_array);
						}
					}
				}else{
					for($cc=0;$cc<sizeof($attribute_array);$cc++){
						$attribute_title = $attribute_array[$cc];
						if($attribute_title == $attributeCode){
							array_push($_SESSION['searchpage']['attribute'][$attribute_title], $option_id);
						}
					}
				}
		    }
			

			if(isset($_POST['filterpage']) && $_POST['filterpage']=='catalog'){ 
				for($dd=0;$dd<sizeof($attribute_array);$dd++){
					$attribute_title = $attribute_array[$dd];
					if($attributeCode!=''){
						

							
								$data['values']['attribute'][$attribute_title] =  array_values(array_unique($_SESSION['catpage']['attribute'][$attribute_title]));
							
						
					}


					$_SESSION['searchpage']['attribute'][$attribute_title]  = array();
				}

				$data['values']['price']   = $_SESSION['catpage']['price'];
				$_SESSION['searchpage']['price']  = '';
				
			}

			if(isset($_POST['filterpage']) && $_POST['filterpage']=='catalogsearch'){ 
				for($ee=0;$ee<sizeof($attribute_array);$ee++){
					$attribute_title = $attribute_array[$ee];
					
						$data['values']['attribute'][$attribute_title] =  array_values(array_unique($_SESSION['searchpage']['attribute'][$attribute_title]));
					

					$_SESSION['catpage']['attribute'][$attribute_title]  = array();
				}
				$data['values']['price']   = $_SESSION['searchpage']['price'];
				$_SESSION['catpage']['price']  = '';
				
			}
	   


		

		for($ff=0;$ff<sizeof($attribute_array);$ff++){
			$attribute_title = $attribute_array[$ff];

				
					$attribute_label = $attribute_array[$ff].'Label';
					$data['values'][$attribute_label] = array();
					$data_values  =$data['values']['attribute'][$attribute_title];
					if(!empty($data_values)){
						for($k=0;$k<sizeof($data_values);$k++){
							$optionId = $data_values[$k];
							$attributeOptionSingle = $objectManager->create(\Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection::class)
							                                       ->setPositionOrder('asc')
							                                       ->setIdFilter($optionId)
							                                       ->setStoreFilter()
							                                       ->load();
							  
							$optionLabel = $attributeOptionSingle->getData();
							$datalabel  =$optionLabel[0]['default_value'];
							array_push($data['values']['attribute'][$attribute_label], $datalabel);
						}
					}
				
	    }

		



					
		if(isset($_POST['cid']) && $_POST['cid']!=''){
			$cat_id = $_POST['cid'];
			if(!isset($_SESSION['sessionid'])){
				$_SESSION['sessionid'] = $_POST['cid'];
			}
			if($cat_id!=$_SESSION['sessionid']){
				$_SESSION['sessionid'] = $cat_id;	
				for($gg=0;$gg<sizeof($attribute_array);$gg++){
					$attribute_title = $attribute_array[$gg];

					$_SESSION['catpage']['attribute'][$attribute_title]  = array();
				}
				$_SESSION['catpage']['price']  = '';
			}
		}


		$data['values']['sessionid'] = $_SESSION['sessionid'];


		/*
		

		$total_sizes = sizeof($data['values']['size']);
		$selected_sizes = array_values($data['values']['size']);
		if($total_sizes > 0 ){
			$size_list = array();
			for($x=0;$x<$total_sizes;$x++){
				$size_list[$x]['finset'] = array($selected_sizes[$x]);
			}
			$categoryProducts->addAttributeToFilter('size',
			    $size_list 
			);
		}

		$total_brands = sizeof($data['values']['brand']);
		$selected_brands = array_values($data['values']['brand']);
		if($total_brands > 0 ){
			$brand_list = array();
			for($y=0;$y<$total_brands;$y++){
				$brand_list[$y]['finset'] = array($selected_brands[$y]);
			}
			$categoryProducts->addAttributeToFilter('brand',
			    $brand_list 
			);
		}


		$total_colors = sizeof($data['values']['color']);
		$selected_colors = array_values($data['values']['color']);
		if($total_colors > 0 ){
			$color_list = array();
			for($z=0;$z<$total_colors;$z++){
				$color_list[$z]['finset'] = array($selected_colors[$z]);
			}
			$categoryProducts->addAttributeToFilter('color',
			    $color_list 
			);
		}


		*/

			
			if($data['values']['price']!=''){
				$price_value = explode('-', $data['values']['price']);

				if($price_value[0] == ''){
					$price_filter = $price_value[1];	
					$filter_val = 'lt';	
					$categoryProducts->addAttributeToFilter(array(array('attribute' => 'price', $filter_val => $price_filter)));
				}else if($price_value[1] == ''){
					$price_filter = $price_value[0];
					$filter_val = 'gteq';	
					$categoryProducts->addAttributeToFilter('price', array( $filter_val => $price_filter ));
				}else{
					$from = $price_value[0];
					$to = $price_value[1] -1 ;
					$categoryProducts->addAttributeToFilter('price', array('from' => $from, 'to' => $to));
				}
			}
			
			
for($hh=0;$hh<sizeof($attribute_array);$hh++){
	$attribute_title = $attribute_array[$hh];
}


// for AND OR 
//http://blog.chapagain.com.np/magento-adding-or-and-and-query-to-collection/
//https://stackoverflow.com/questions/15040529/how-to-add-an-and-filter-condition-to-an-or-condition-in-magento
//https://www.offset101.com/magento-2-x-manage-multiple-conditions-filter-filtergroups/		
$categoryProducts->addAttributeToFilter(
    array(
          array('attribute'=>'size', 'finset'=> array('7')),
          array('attribute'=>'size', 'finset'=> array('8')),
          array('attribute'=>'size', 'finset'=> array('9')),
          //array('attribute'=>'some_attribute', 'finset'=>$v2),
    )
);

$categoryProducts->addFieldToFilter(
	array(
		array(
			'attribute'=>'color', 'finset'=> array('4')
		),
    )
);
/*
$categoryProducts->addFieldToFilter(array(
                              array('attribute' => 'description', 'like' => '%blah%'),
                              array('attribute' => 'description', 'like' => '%yes%')
                              ));
                              */
		//echo $categoryProducts->getSelect()->__toString();

		//pagination data    
	    $data['pagination']  = array();
	    $limit =  4;
	    $curpage =  1;
	    if(isset($_POST['limit']) && $_POST['limit']!=''){
		    $limit =  $_POST['limit'] ;
	    }
	    if(isset($_POST['page']) && $_POST['page']!=''){
		    $curpage = $_POST['page'];
	    }

	    $total_products = $categoryProducts->getSize();
		$total_pages = ceil($total_products / $limit);
		$products_Data  = $categoryProducts->setPageSize($limit)->setCurPage($curpage);

		$pagination_data = '';
		if($total_pages > 1 ){
			if($curpage == 1){
				$pagination_data .= '<a href="javascript:;" class="pagenumber_static pager" limit="'.$limit.'" data="1">Prev<a>';
			}else{
				$prevpage = $curpage  - 1;
				$pagination_data .= '<a href="javascript:;" class="pagenumber pager" limit="'.$limit.'" data="'.$prevpage.'">Prev<a>';
			}
		}
		for($pg=1;$pg<=$total_pages;$pg++){
			if($pg == $curpage){
				$pagination_data .= '<a href="javascript:;" class="pagenumber_static pager" limit="'.$limit.'" data="'.$pg.'">'.$pg.'<a>';
			}else{
				$pagination_data .= '<a href="javascript:;" class="pagenumber  pager" limit="'.$limit.'" data="'.$pg.'">'.$pg.'<a>';
			}
		}
		if($total_pages > 1 ){
			if($curpage == $total_pages){
				$pagination_data .= '<a href="javascript:;" class="pagenumber_static pager" limit="'.$limit.'" data="'.$total_pages.'">Next<a>';
			}else{
				$nextpage = $curpage  + 1;
				$pagination_data .= '<a href="javascript:;" class="pagenumber pager" limit="'.$limit.'" data="'.$nextpage.'">Next<a>';
			}
		}
		$data['pagination'] = $pagination_data;
		
		//print_r($_SESSION['pagecheck']);
		if($categoryProducts->count() > 0 ){
			$associatedProducts = array();
			foreach ($categoryProducts as $product) {

				$price_value = $product->getPrice();
				//for grouped product price 	
				$p  = $product->getData();
				if($product['type_id']=="grouped"){
				    $associatedProducts[] = $product->getTypeInstance(true)->getAssociatedProducts($product);
					$price =  array();
					foreach ($associatedProducts as $singleProduct) {
					    foreach ($singleProduct as $_p) {
					       $price[] =  $_p->getPrice();
					    }
					}
					sort($price);
					$price_value = $price[0];
				}else if($product['type_id']=="configurable"){
					$price_value = $product->getFinalPrice();
				}
				$price_value = 	number_format((float)$price_value, 2, '.', '');
				//image uploaded file path
				 $mediaPath = $objectManager->get('Magento\Store\Model\StoreManagerInterface')
	                    ->getStore()
	                    ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
				 $img_url = $mediaPath.'catalog/product'.$product->getImage();
				 
				//image by default placeholder image path
				 if(!$product->getImage()){
					 $imageHelper = \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Catalog\Helper\Image::class);
					 $img_url = $imageHelper->getDefaultPlaceholderUrl('image');      
				 }


				 $FormKey = $objectManager->get('Magento\Framework\Data\Form\FormKey'); 
				 $listBlock = $objectManager->get('\Magento\Catalog\Block\Product\ListProduct');
				 $addToCartUrl =  $listBlock->getAddToCartUrl($product);
				 $addtocart = '<form data-role="tocart-form" action="'.$addToCartUrl.'" method="post"><input type="hidden" name="product" value="'.$product->getId().'"><button type="submit" title="Add to Cart" class="action tocart"><span>Add to Cart</span></button></form>'; 	

				$addtocart =  '<a href="'.$product->getProductUrl().'" title="Add to Cart" class="action tocart"><span>View Product</span></a>';
				
				$productData = array(
										'id' => $product->getId(),
										'sku' => $product->getSku(),
										'name' => $product->getName(),
										'price' => $price_value,
										'addtocart' => $addtocart,
										'image' => $img_url,
										'url' => $product->getProductUrl(),
									);
			    $data['message']  	 = 0;
			    $data['products'][]  = $productData;
			}
		}else{
			    $data['message']  	 = 1;
			    $data['products']  = array();
		}
		
		echo json_encode($data);
  }
}
