<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

class User_model extends CI_model {

	public function dataLogin($email) {
		return $this->db->get_where('user', ['email' => $email ])->row_array();
	}

	public function changePassword($newPassword, $email) {
		$this->db->set('password', $newPassword);
		$this->db->where('email', $email);
		$this->db->update('user');
	}
}