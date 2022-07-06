<?php


class Signin_model extends CI_Model {
    
	public function check_mobile($mobile) {
		$this->db->where(['mobile' => $mobile]);
		$query 	= $this->db->get('user_info');
		$result = $query->num_rows();
		return $result;
	}

	public function update_otp($mobile, $data) {
		return $this->db->update('user_info', $data, ["mobile"=>$mobile]);
	}

	public function verify($mobile, $otp) {
		$data = [];
		$this->db->where([mobile => $mobile, otp => $otp]);
		$query = $this->db->get('user_info');
		$result = $query->row();
		if($result) {
			$data = [
				'login_id' 	=> $result->id,
				'login_name' 	=> $result->name,
				'login_mobile' 	=> $result->mobile,
				'login_status' 	=> TRUE,
			];
		}
		return $data;
	}
}

?>