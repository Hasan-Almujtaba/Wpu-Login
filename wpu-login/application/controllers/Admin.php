<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('Admin_model');

		is_logged_in();

	}

	public function index() {
		$email = $this->session->userdata('email');
		$roleId = $this->session->userdata('role_id');
		$data['user'] = $this->Admin_model->dataLogin($email);
		$data['title'] = 'Dashboard';
		
		$this->load->view('templates/header', $data);	
		$this->load->view('templates/sidebar', $data);	
		$this->load->view('templates/topbar', $data);	
		$this->load->view('admin/index', $data);
		$this->load->view('templates/footer',$data);

	}

	public function role() {
		$email = $this->session->userdata('email');
		$roleId = $this->session->userdata('role_id');
		$data['user'] = $this->Admin_model->dataLogin($email);
		$data['title'] = 'Role';
		$data['role'] = $this->Admin_model->showAllRole();
		
		$this->load->view('templates/header', $data);	
		$this->load->view('templates/sidebar', $data);	
		$this->load->view('templates/topbar', $data);	
		$this->load->view('admin/role', $data);
		$this->load->view('templates/footer', $data);

	}

	public function roleAccess($role_id) {
		$email = $this->session->userdata('email');
		$roleId = $this->session->userdata('role_id');
		$data['user'] = $this->Admin_model->dataLogin($email);
		$data['title'] = 'Role Access';
		$data['role'] = $this->Admin_model->showRole($role_id);
		$data['menu'] = $this->Admin_model->showAllMenu();
		
		$this->load->view('templates/header', $data);	
		$this->load->view('templates/sidebar', $data);	
		$this->load->view('templates/topbar', $data);	
		$this->load->view('admin/role-access', $data);
		$this->load->view('templates/footer', $data);

	}

	public function changeAccess() {
		$menu_id = $this->input->post('menuId');
		$role_id = $this->input->post('roleId');

		$data = [
			'role_id' => $role_id,
			'menu_id' => $menu_id
		];

		// $result = $this->db->get_where('user_access_menu', $data);

		// if($result->num_rows() < 1 ) {
		// 	$this->db->insert('user_access_menu', $data);
		// } else {
		// 	$this->db->delete('user_access_menu', $data);
		// }

		$result = $this->Admin_model->getAccess($data);

				if( $result->num_rows() < 1 ) {
					$this->Admin_model->addAccess($data);
				}
				else {
					$this->Admin_model->removeAccess($data);
				}

		$this->session->set_flashdata('message', '<div class="alert alert-success text-center" role="alert">Access Changed!</div>');
 	}
}

 ?>