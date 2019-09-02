<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Auth_model extends CI_model {

	public function register($user_token) {

		$data = [
			'name' => htmlspecialchars($this->input->post('name', true)),
			'email' => htmlspecialchars($this->input->post('email', true)),
			'image' => 'default.jpg',
			'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
			'role_id' => 2,
			'is_active' => 0,
			'date_created' => time()
		];
		
		$this->db->insert('user', $data);
		$this->db->insert('user_token', $user_token);

	}

	public function dataLogin($email) {
		return $this->db->get_where('user', ['email' => $email ])->row_array();
	}

	public function getToken($token) {
		return $this->db->get_where('user_token', ['token' => $token ])->row_array(); 
	}

	public function activateAccount($email) {
		$this->db->set('is_active', 1);
		$this->db->where('email', $email);
		$this->db->update('user');

		$this->db->delete('user_token', ['email' => $email ]);
	}

	public function getUserData($email) {
		return $this->db->get_where('user', ['email' => $email, 'is_active' => 1 ])->row_array();
	}

	public function passToken($user_token) {
		$this->db->insert('user_token', $user_token);
	}

	public function changePassword($email, $password) {
		
		$this->db->set('password', $password);
		$this->db->where('email', $email);
		$this->db->update('user');

		$this->db->delete('user_token', ['email' => $email ]);
	}
}


 ?>