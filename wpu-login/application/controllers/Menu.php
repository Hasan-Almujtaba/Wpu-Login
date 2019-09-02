<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('Menu_model');

		is_logged_in();
	}

	public function index() {
		$email = $this->session->userdata('email');
		$roleId = $this->session->userdata('role_id');
		$data['user'] = $this->Menu_model->dataLogin($email);
		$data['title'] = 'Menu Management';
		$data['menu'] = $this->Menu_model->getUserMenu();

		$this->form_validation->set_rules('menu', 'Menu', 'required');
		
		if( $this->form_validation->run() == false ) {
			$this->load->view('templates/header', $data);	
			$this->load->view('templates/sidebar', $data);	
			$this->load->view('templates/topbar', $data);	
			$this->load->view('menu/index', $data);
			$this->load->view('templates/footer',);
		}
		else {
			$this->Menu_model->addMenu();
			$this->session->set_flashdata('message', '<div class="alert alert-success text-center" role="alert">New Menu Added</div>');
			redirect('menu');
		}
	}

	public function subMenu() {
		$email = $this->session->userdata('email');
		$roleId = $this->session->userdata('role_id');
		$data['user'] = $this->Menu_model->dataLogin($email);
		$data['title'] = 'SubMenu Management';

		$data['subMenu'] = $this->Menu_model->getSubMenu();
		$data['menu'] = $this->Menu_model->getUserMenu();

		$this->form_validation->set_rules('title', 'title', 'required');
		$this->form_validation->set_rules('menu_id', 'Menu', 'required');
		$this->form_validation->set_rules('url', 'Url', 'required');
		$this->form_validation->set_rules('icon', 'Icon', 'required');

		if( $this->form_validation->run() == false ) {
			$this->load->view('templates/header', $data);	
			$this->load->view('templates/sidebar', $data);	
			$this->load->view('templates/topbar', $data);	
			$this->load->view('menu/submenu', $data);
			$this->load->view('templates/footer',);
		}
		else {

			$this->Menu_model->addSubMenu();
			$this->session->set_flashdata('message', '<div class="alert alert-success text-center" role="alert">New SubMenu Added</div>');
			redirect('menu/submenu');

		}
	}



}