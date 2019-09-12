<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('User_model');

		is_logged_in();

	}

	public function index() {
		$email = $this->session->userdata('email');
		$roleId = $this->session->userdata('role_id');
		$data['user'] = $this->User_model->dataLogin($email);
		$data['title'] = 'My Profile';
		
		$this->load->view('templates/header', $data);	
		$this->load->view('templates/sidebar', $data);	
		$this->load->view('templates/topbar', $data);	
		$this->load->view('user/index', $data);
		$this->load->view('templates/footer', $data);

	}

	public function edit() {
		$email = $this->session->userdata('email');
		$roleId = $this->session->userdata('role_id');
		$data['user'] = $this->User_model->dataLogin($email);
		$data['title'] = 'Edit Profile';

		$this->form_validation->set_rules('name', 'Full Name', 'required|trim');
		
		if ($this->form_validation->run() == false ) {
			$this->load->view('templates/header', $data);	
			$this->load->view('templates/sidebar', $data);	
			$this->load->view('templates/topbar', $data);	
			$this->load->view('user/edit', $data);
			$this->load->view('templates/footer', $data);
		} else {
			$name = $this->input->post('name');
			$email = $this->input->post('email');

			// cek apakah ada gambar yang akan diupload
			$upload_image = $_FILES['image']['name'];

			if( $upload_image ) {
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']     = '10240';
				$config['upload_path'] = './assets/img/profile/';

				$this->load->library('upload', $config);

				if( $this->upload->do_upload('image') ) {

					$old_image = $data['user']['image'];

					if( $old_image != 'default.jpg' ) {
						unlink(FCPATH . 'assets/img/profile/' . $old_image );
					}

					$new_image = $this->upload->data('file_name');
					$this->db->set('image', $new_image);

				} else {
					echo $this->upload->display_errors();
				}


			}

			$this->db->set('name', $name);
			$this->db->where('email', $email);
			$this->db->update('user');

			$this->session->set_flashdata('message', '<div class="alert alert-success text-center" role="alert">User Profile Changed!</div>');
			redirect('user');
		}
	}

	public function changePassword() {
		$email = $this->session->userdata('email');
		$roleId = $this->session->userdata('role_id');
		$data['user'] = $this->User_model->dataLogin($email);
		$data['title'] = 'Change Password';
		
		$this->form_validation->set_rules('currentPassword', 'Current Password', 'required|trim');

		$this->form_validation->set_rules('newPassword', 'New Password', 'required|trim|min_length[3]|matches[confirmNewPassword]');
		$this->form_validation->set_rules('confirmNewPassword', 'Confirm New Password', 'required|trim|min_length[3]|matches[newPassword]');

		if( $this->form_validation->run() == false ) {
			$this->load->view('templates/header', $data);	
			$this->load->view('templates/sidebar', $data);	
			$this->load->view('templates/topbar', $data);	
			$this->load->view('user/changepassword', $data);
			$this->load->view('templates/footer', $data);
		} else {

			$currentPassword = $this->input->post('currentPassword');
			$newPassword = $this->input->post('newPassword');

			if( !password_verify($currentPassword, $data['user']['password']) ) {
				$this->session->set_flashdata('message', '<div class="alert alert-danger text-center" role="alert">Wrong Current Password</div>');
				redirect('user/changepassword');
			} else {
				if( $currentPassword == $newPassword ) {
					$this->session->set_flashdata('message', '<div class="alert alert-danger text-center" role="alert">New Password Can\'t be the same as current password!</div>');
					redirect('user/changepassword');
				} else {
					// password sudah ok
					$password_hash = password_hash($newPassword, PASSWORD_DEFAULT);

					$this->User_model->changePassword($password_hash, $email);

					$this->session->set_flashdata('message', '<div class="alert alert-success text-center" role="alert">Password Successfully Changed!</div>');
					redirect('user');
				}
			}
		}

	}

}

 ?>