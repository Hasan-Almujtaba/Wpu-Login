<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_model {

	public function dataLogin($email) {
		return $this->db->get_where('user', ['email' => $email ])->row_array();
	}

	public function showAllRole() {
		return $this->db->get('user_role')->result_array();
	}

	public function showRole($role_id) {
		return $this->db->get_where('user_role', ['id'  => $role_id ])->row_array();
	}

	public function showAllMenu() {
		$this->db->where('id !=', 1);
		return $this->db->get('user_menu')->result_array();
	}

	public function getAccess($data) {
		

		return $this->db->get_where('user_access_menu', $data);
	}

	public function addAccess($data) {
		

		$this->db->insert('user_access_menu', $data);
	}

	public function removeAccess($data) {
		

		$this->db->delete('user_access_menu', $data);
	}
}
