<?php
class Dental_model extends CI_Model 
{
	function submitvisitbilling($procedure_id,$visit_id,$suck){
		$visit_data = array('procedure_id'=>$procedure_id,'visit_id'=>$visit_id,'units'=>$suck);
		$this->db->insert('visit_procedure', $visit_data);
	}

	 function get_payment_info($visit_id)
	{
		$table = "visit";
		$where = "visit_id = '$visit_id'";
		$items = "payment_info";
		$order = "visit_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
}
?>