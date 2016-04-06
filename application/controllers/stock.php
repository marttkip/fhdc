<?php session_start();
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->helper('url');
		
		$this->load->library('grocery_CRUD');	
	}
	
	public function index()
	{
		echo "HELLO WORLD";
	}
	
	public function control_panel(){
		$this->load->view("control_panel");
	}
	
	public function clerk_control(){
		$this->load->view("clerk_control");
	}
	
	function stock($output = null)
	{
		$this->load->view('stock.php',$output);	
	}
	
	public function get_patient($patient_id)
	{
		$table = "patients";
		$where = "patient_id = $patient_id";
		$items = "*";
		$order = "patient_surname";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	
	public function calculate_time($value, $row)
	{
  		$value = $this->dateDiff(date('y-m-d  h:i'), $row->visit_time, 'hour');
		
		return $row->visit_time;
	}
	
	public function soap($primary_key)
	{
		$data['visit_id'] = $primary_key;
		$this->load->view('doctor/soap', $data);	
	}
	
	public function dateDiff($time1, $time2, $interval) {
    // If not numeric then convert texts to unix timestamps
    if (!is_int($time1)) {
      $time1 = strtotime($time1);
    }
    if (!is_int($time2)) {
      $time2 = strtotime($time2);
    }
 
    // If time1 is bigger than time2
    // Then swap time1 and time2
    if ($time1 > $time2) {
      $ttime = $time1;
      $time1 = $time2;
      $time2 = $ttime;
    }
 
    // Set up intervals and diffs arrays
    $intervals = array('year','month','day','hour','minute','second');
    if (!in_array($interval, $intervals)) {
      return false;
    }
 
    $diff = 0;
    // Create temp time from time1 and interval
    $ttime = strtotime("+1 " . $interval, $time1);
    // Loop until temp time is smaller than time2
    while ($time2 >= $ttime) {
      $time1 = $ttime;
      $diff++;
      // Create new temp time from time1 and interval
      $ttime = strtotime("+1 " . $interval, $time1);
    }
 
    return $diff;
  }
  
  
	public function inventory()
    {
		$crud = new grocery_CRUD();
 
        $crud->set_subject('Drug');
        $crud->set_table('drugs');
		$crud->callback_column('33% Markup',array($this,'markup'));
  		$crud->callback_column('10% Markdown',array($this,'markdown'));
  		$crud->callback_column('Profit Margin',array($this,'profit_margin'));
  		$crud->callback_column('Purchases',array($this,'item_purchases'));
  		$crud->callback_column('Deductions',array($this,'item_deductions'));
  		//$crud->callback_column('Sales',array($this,'get_requisitions_total'));
  		$crud->callback_column('In Stock',array($this,'item_in_stock'));
		$crud->columns('drugs_name','drug_size','drug_size_type','drugs_dose','drug_dose_unit_id','drug_type_id','drugs_unitprice','33% Markup','10% Markdown', 'Profit Margin','Purchases','Sales','Deductions','In Stock');
		//$crud->fields('drugs_code','drugs_name','drug_size','drug_size_type','drugs_dose','drug_dose_unit_id','drug_consumption_id','drug_administration_route_id','brand_id','generic_id','class_id','drug_type_id','drugs_unitprice');
  		$crud->set_relation('drug_administration_route_id', 'drug_administration_route', '{drug_administration_route_name}');
    	$crud->set_relation('drug_consumption_id', 'drug_consumption', '{drug_consumption_name}');
    	$crud->set_relation('brand_id', 'brand', '{brand_name}');
    	$crud->set_relation('generic_id', 'generic', '{generic_name}');
    	$crud->set_relation('class_id', 'class', '{class_name}');
    	$crud->set_relation('drug_type_id', 'drug_type', '{drug_type_name}');
    	$crud->set_relation('drug_dose_unit_id', 'drug_dose_unit', '{drug_dose_unit_name}');
		//$crud->add_action('Sales History', base_url('img/new/icon-48-media.png'), 'stock/sale_history');
		$crud->add_action('Deduct', base_url('img/new/icon-48-themes.png'), 'stock/deductions');
		$crud->add_action('Purchase', base_url('img/new/icon-48-category-add.png'), 'stock/purchases');
		$crud->required_fields('drugs_name');
        $crud->display_as('drug_administration_route_id','Administration Route');
        $crud->display_as('drug_consumption_id','Consumption Method');
		
    	$crud->display_as('drug_size','Drug Package Size E.g 100'."<br /> <strong style='background:red'>".'Applies Syrup bottles, cream, spray etc'."</strong>");
		$crud->set_relation('drug_size_type', 'drug_dose_unit', '{drug_dose_unit_name}');
	    $crud->display_as('drug_size_type','Package Size Unit E.g ml'."<br /> <strong style='background:red'>".'Applies Syrup bottles, cream, spray etc'."</strong>");
		$crud->display_as('drugs_code','Code');
		$crud->display_as('drugs_name','Drug');
        $crud->display_as('drugs_dose','Dose');
        $crud->display_as('drug_dose_unit_id','Units');
        $crud->display_as('brand_id','Brand');
        $crud->display_as('generic_id','Generic');
        $crud->display_as('class_id','Class');
        $crud->display_as('drug_type_id','Type');
        $crud->display_as('drugs_unitprice','Unit Price');
		$output = $crud->render();
 		$this->stock($output);
    }
	
	public function markup($value, $row)
	{
		$margin = $row->drugs_unitprice * 1.33;
		return round($margin, 0);
	}
	
	public function markdown($value, $row)
	{
		$margin = ($row->drugs_unitprice * 1.33) * 0.9;
		return round($margin, 0);
	}
	
	public function profit_margin($value, $row)
	{
		$margin = (($row->drugs_unitprice * 1.33) * 0.9) - $row->drugs_unitprice;
		return round($margin, 0);
	}
	
	public function get_requisitions_total($value, $row)
	{
		$table = "requisition, requisition_item, item";
		$where = "item.item_id = ".$row->item_id." ";
		$items = "requisition_item.requisition_item_pack_size, requisition_item.requisition_item_quantity";
		$order = "requisition_item_pack_size";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		echo $result;
		
		$total = 0;
		
		if(count($result) > 0){
			
			foreach ($result as $row2)
			{
				$requisition_pack_size = $row2->requisition_item_pack_size;
				$requisition_quantity = $row2->requisition_item_quantity;
				$total = $total + ($requisition_pack_size * $requisition_quantity);
			}
		}
		return $total;
	}
	
	public function inventory_report()
	{
	}
	
	public function item_in_stock($value, $row)
	{
		/*$table = "requisition, requisition_item, item";
		$where = "item.item_id = ".$row->item_id." AND requisition_item.item_id = item.item_id AND requisition_item.requisition_id = requisition.requisition_id";
		$items = "requisition_item.requisition_item_pack_size, requisition_item.requisition_item_quantity";
		$order = "requisition_item_pack_size";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		$total = 0;
		
		if(count($result) > 0){
			
			foreach ($result as $row2)
			{
				$requisition_pack_size = $row2->requisition_item_pack_size;
				$requisition_quantity = $row2->requisition_item_quantity;
				$total = $total + ($requisition_pack_size * $requisition_quantity);
			}
		}
		$total = $_SESSION['purchases'] - $total;*/
		$purchases = $row->Purchases;
		$deductions = $row->Deductions;
		return ($purchases - $deductions);
	}
	
	public function item_purchases($value, $row)
	{
  		$table = "purchase, drugs";
		$where = "drugs.drugs_id = ".$row->drugs_id." AND purchase.drugs_id = drugs.drugs_id";
		$items = "purchase.purchase_pack_size, purchase.purchase_quantity";
		$order = "purchase_pack_size";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		$total = 0;
		
		if(count($result) > 0){
			
			foreach ($result as $row2)
			{
				$purchase_pack_size = $row2->purchase_pack_size;
				$purchase_quantity = $row2->purchase_quantity;
				$total = $total + ($purchase_pack_size * $purchase_quantity);
			}
		}
		$_SESSION['purchases'] = $total;
		return $total;
	}
	
	public function item_deductions($value, $row)
	{
  		$table = "stock_deductions, drugs";
		$where = "drugs.drugs_id = ".$row->drugs_id." AND stock_deductions.drugs_id = drugs.drugs_id";
		$items = "stock_deductions.stock_deductions_pack_size, stock_deductions.stock_deductions_quantity";
		$order = "stock_deductions_pack_size";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		$total = 0;
		
		if(count($result) > 0){
			
			foreach ($result as $row2)
			{
				$stock_deductions_pack_size = $row2->stock_deductions_pack_size;
				$stock_deductions_quantity = $row2->stock_deductions_quantity;
				$total = $total + ($stock_deductions_pack_size * $stock_deductions_quantity);
			}
		}
		$_SESSION['stock_deductions'] = $total;
		return $total;
	}
		public function item_sale_deductions($value, $row)
	{
  		$table = "stock_deductions, drugs";
		$where = "drugs.drugs_id = ".$row->drugs_id." AND stock_deductions.drugs_id = drugs.drugs_id";
		$items = "stock_deductions.stock_deductions_pack_size, stock_deductions.stock_deductions_quantity";
		$order = "stock_deductions_pack_size";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		$total = 0;
		
		if(count($result) > 0){
			
			foreach ($result as $row2)
			{
				$stock_deductions_pack_size = $row2->stock_deductions_pack_size;
				$stock_deductions_quantity = $row2->stock_deductions_quantity;
				$total = $total + ($stock_deductions_pack_size * $stock_deductions_quantity);
			}
		}
		$_SESSION['stock_deductions'] = $total;
		return $total;
	}
	
	public function order()
    {
		$crud = new grocery_CRUD();
 		
        $crud->set_subject('Order');
        $crud->set_table('orders');
    	$crud->set_relation('personnel_id', 'personnel', '{personnel_fname} {personnel_onames}');
    	$crud->set_relation('supplier_id', 'supplier', '{supplier_name}');
		$crud->add_action('Items', base_url('img/icon-48-media.png'), 'welcome/add_item');
		$crud->add_action('Purchase', base_url('img/icon-48-themes.png'), 'welcome/purchases');
		$crud->required_fields('orders_number', 'supplier_id');
		$crud->unset_fields('orders_date');
        $crud->display_as('orders_date','Date');
        $crud->display_as('personnel_id','Ordered By');
        $crud->display_as('orders_number','Order Number');
        $crud->display_as('supplier_id','Supplier');
        
        $output = $crud->render();
 
        $this->stock($output);
    }
	
	public function requisition()
    {
		$crud = new grocery_CRUD();
 		
        $crud->set_subject('Requisition');
        $crud->set_table('requisition');
    	$crud->set_relation('personnel_id', 'personnel', '{personnel_fname} {personnel_onames}');
    	$crud->set_relation('department_id', 'department', '{department_name}');
		$crud->add_action('Items', base_url('img/icon-48-media.png'), 'welcome/add_requisition_item');
		$crud->required_fields('requisition_number', 'supplier_id');
		$crud->unset_fields('requisition_date');
        $crud->display_as('requisition_date','Date');
        $crud->display_as('personnel_id','Requested By');
        $crud->display_as('requisition_number','Requisition Number');
        $crud->display_as('department_id','Department');
        
        $output = $crud->render();
 
        $this->stock($output);
    }
	
	public function add_requisition_item($primary_key)
	{
		
		$crud = new grocery_CRUD();
 		
		$crud->where('requisition_item.requisition_id', $primary_key);
        $crud->set_subject('Item');
        $crud->set_table('requisition_item');
    	$crud->set_relation('requisition_id', 'requisition', '{requisition_number}');
    	$crud->set_relation('item_id', 'item', '{item_name}');
    	$crud->set_relation('container_type_id', 'container_type', '{container_type_name}');
		$crud->required_fields('item_id', 'requisition_item_quantity', 'container_type_id', 'requisition_item_pack_size', 'requisition_item_quantity');
		$crud->add_action('Requisition', base_url('img/icon-48-media.png'), 'welcome/requisition');
        $crud->display_as('requisition_id','Requisition Number');
        $crud->display_as('item_id','Stock Item');
        $crud->display_as('container_type_id','Container');
        $crud->display_as('requisition_item_quantity','Quantity');
        $crud->display_as('requisition_item_pack_size','Pack Size');
		$crud->field_type('requisition_id', 'hidden', $primary_key);
        $output = $crud->render();
 
        $this->stock($output);
	}
	
	public function add_item($primary_key)
	{
		
		$crud = new grocery_CRUD();
 		
		$crud->where('order_item.orders_id', $primary_key);
        $crud->set_subject('Item');
        $crud->set_table('order_item');
    	$crud->set_relation('orders_id', 'orders', '{orders_number}');
    	$crud->set_relation('item_id', 'item', '{item_name}');
    	$crud->set_relation('container_type_id', 'container_type', '{container_type_name}');
		$crud->required_fields('item_id', 'order_item_quantity', 'container_type_id', 'order_item_pack_size');
		$crud->add_action('Order', base_url('img/icon-48-media.png'), 'welcome/order');
        $crud->display_as('order_id','Order Number');
        $crud->display_as('item_id','Stock Item');
        $crud->display_as('container_type_id','Container');
        $crud->display_as('order_item_quantity','Quantity');
        $crud->display_as('order_item_pack_size','Pack Size');
		$crud->field_type('orders_id', 'hidden', $primary_key);
        $output = $crud->render();
 
        $this->stock($output);
	}
	
	public function purchases($primary_key)
	{
		
		$crud = new grocery_CRUD();
 		
		$crud->where('purchase.drugs_id', $primary_key);
        $crud->set_subject('Purchase');
        $crud->set_table('purchase');
    	$crud->set_relation('drugs_id', 'drugs', '{drugs_name}');
    	$crud->set_relation('container_type_id', 'container_type', '{container_type_name}');
		$crud->required_fields('drugs_id', 'purchase_quantity', 'container_type_id', 'purchase_pack_size');
		$crud->unset_fields("purchase_date");
        $crud->display_as('drugs_id','Drug');
        $crud->display_as('container_type_id','Container');
        $crud->display_as('purchase_quantity','Quantity');
        $crud->display_as('purchase_pack_size','Pack Size');
		$crud->field_type('drugs_id', 'hidden', $primary_key);
        $output = $crud->render();
 
        $this->stock($output);
	}
	
	public function deductions($primary_key)
	{
		
		$crud = new grocery_CRUD();
 		
		$crud->where('stock_deductions.drugs_id', $primary_key);
        $crud->set_subject('Deduction');
        $crud->set_table('stock_deductions');
    	$crud->set_relation('drugs_id', 'drugs', '{drugs_name}');
    	$crud->set_relation('container_type_id', 'container_type', '{container_type_name}');
		$crud->required_fields('drugs_id', 'stock_deductions_quantity', 'container_type_id', 'stock_deductions_pack_size');
		$crud->unset_fields("stock_deductions_date");
        $crud->display_as('drugs_id','Drug');
        $crud->display_as('container_type_id','Container');
        $crud->display_as('stock_deductions_quantity','Quantity');
        $crud->display_as('stock_deductions_pack_size','Pack Size');
        $crud->display_as('stock_deductions_date','Deduction Date');
		$crud->field_type('drugs_id', 'hidden', $primary_key);
        $output = $crud->render();
 
        $this->stock($output);
	}
	
	public function container_type()
    {
		$crud = new grocery_CRUD();
 
        $crud->set_subject('Container');
        $crud->set_table('container_type');
		$crud->required_fields('container_type_name');
        $crud->display_as('container_type_name','Container');
        
        $output = $crud->render();
 
        $this->stock($output);
    }
	
	public function supplier()
    {
		$crud = new grocery_CRUD();
 
        $crud->set_subject('Suppliers');
        $crud->set_table('supplier');
		$crud->required_fields('supplier_name');
        
        $crud->display_as('supplier_email','Email');
        $crud->display_as('supplier_name','Supplier Name');
        $crud->display_as('supplier_phone','Phone Number');
		$crud->display_as('supplier_physical_address','Physical Address');
        $crud->display_as('supplier_contact_person','Contact Person');
        $crud->display_as('supplier_tender_number','Tender Number');
		$crud->display_as('supplier_tender_details','Tender Details');
       
        
        $output = $crud->render();
 
        $this->stock($output);
    }
	
	public function drug_units()
    {
		$crud = new grocery_CRUD();
 
        $crud->set_subject('Unit');
        $crud->set_table('drug_dose_unit');
		$crud->required_fields('drug_dose_unit_name');
        $crud->display_as('drug_dose_unit_name','Unit');
        
        $output = $crud->render();
 
        $this->stock($output);
    }
	
	public function brands()
    {
		$crud = new grocery_CRUD();
 
        $crud->set_subject('Brand');
        $crud->set_table('brand');
		$crud->required_fields('brand_name');
        $crud->display_as('brand_name','Brand');
        
        $output = $crud->render();
 
        $this->stock($output);
    }
	
	public function generics()
    {
		$crud = new grocery_CRUD();
 
        $crud->set_subject('Generic');
        $crud->set_table('generic');
		$crud->required_fields('generic_name');
        $crud->display_as('generic_name','Generic');
        
        $output = $crud->render();
 
        $this->stock($output);
    }
	
	public function classes()
    {
		$crud = new grocery_CRUD();
 
        $crud->set_subject('Class');
        $crud->set_table('class');
		$crud->required_fields('class_name');
        $crud->display_as('class_name','Class');
        
        $output = $crud->render();
 
        $this->stock($output);
    }
	
	public function drug_type()
    {
		$crud = new grocery_CRUD();
 
        $crud->set_subject('Type');
        $crud->set_table('drug_type');
		$crud->required_fields('drug_type_name');
        $crud->display_as('drug_type_name','Type');
        
        $output = $crud->render();
 
        $this->stock($output);
    }
}
