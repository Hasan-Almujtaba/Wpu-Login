<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('Auth_model');
	}

	public function index() {

		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');

		if( $this->form_validation->run() == false ) {
			$data['title'] = 'Login';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/login', $data);
			$this->load->view('templates/auth_footer', $data);
		}
		else {
			$this->_login();
		}

		already_logged_in();

	}

	private function _login() {
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$user = $this->Auth_model->dataLogin($email);

		// user ada
		if( $user ) {
			
			// user aktif
			if( $user['is_active'] == 1 ) {

				// cek password
				if( password_verify($password, $user['password']) ) {

					$data = [
						'email' => $user['email'],
						'role_id' => $user['role_id']
					];

					$this->session->set_userdata($data);

					if( $user['role_id'] == 1 ) {
						redirect('admin');
					} else {
						redirect('user');
					}

				}
				else{
					$this->session->set_flashdata('message', '<div class="alert alert-danger text-center" role="alert">Wrong Password</div>');
					redirect('auth');
				}

			}
			else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger text-center" role="alert">Account has not been activated</div>');
				redirect('auth');
			}
		}
		else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger text-center" role="alert">Email is not registered</div>');
			redirect('auth');
		}
	}

	public function registration() {

		$this->form_validation->set_rules('name', 'name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
			'is_unique' => 'This email has already registered!'
		]);
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[3]|matches[passwordConfirm]');
		$this->form_validation->set_rules('passwordConfirm', 'Confirm Password', 'required|trim|matches[password]');


		if( $this->form_validation->run() == false ) {
			$data['title'] = 'Registration';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/registration', $data);
			$this->load->view('templates/auth_footer', $data);
		}
		else {

			$token = base64_encode(random_bytes(32));
			$user_token = [
				'email' => $this->input->post('email', true),
				'token' => $token,
				'date_created' => time()
			];

			$this->Auth_model->register($user_token);

			$this->_sendEmail($token, 'verify');
			$this->session->set_flashdata('message', '<div class="alert alert-success text-center" role="alert">Your account has already created. Please verify your account</div>');
			redirect('auth');
		}

		already_logged_in();
	}

	private function _sendEmail($token, $type) {
		$config = [
			'protocol'  => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_user' => 'inhihsan@gmail.com',
			'smtp_pass' => 'Helloworld321',
			'smtp_port' => 465,
			'mailtype'  => 'html',
			'charset'   => 'utf-8',
			'newline'   => "\r\n",
		];

		$this->load->library('email', $config);
		$this->email->initialize($config);

		$this->email->from('inhihsan@gmail.com', 'WPU Login System');
		$this->email->to($this->input->post('email'));
		
		if( $type == 'verify' ) {
			$this->email->subject('Account Verification');
			$this->email->message('Click this link to verify your account : <a href="' . base_url() . 'auth/verify/?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '" >Activate</a> ');
		} else if( $type == 'forgot' ) {
			$this->email->subject('Reset Password');
			$this->email->message('Click this link to reset your Password : <a href="' . base_url() . 'auth/resetpassword/?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '" >reset password</a> ');
		}
		
		if ( $this->email->send() ) {
			return true;
		} else {
			echo $this->email->print_debugger();
			die;
		}


	}

	public function verify() {
		$email = $this->input->get('email');
		$token = $this->input->get('token');

		$user = $this->Auth_model->dataLogin($email);

		if($user) {

			$user_token = $this->Auth_model->getToken($token);

			if ($user_token) {
				if( time() - $user_token['date_created'] < (60 * 60 * 24) ) {

					$this->Auth_model->activateAccount($email);

					$this->session->set_flashdata('message', '<div class="alert alert-success text-center" role="alert">Account Activation Success! Please Login</div>');
					redirect('auth');

				} else {
					$this->db->delete('user', ['email' => $email ]);
					$this->db->delete('user_token', ['email' => $email ]);

					$this->session->set_flashdata('message', '<div class="alert alert-danger text-center" role="alert">Account Activation Failed! Token Expired</div>');
					redirect('auth');
				}

			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger text-center" role="alert">Account Activation Failed! Token Invalid</div>');
				redirect('auth');
			}

		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger text-center" role="alert">Account Activation Failed! Wrong Email</div>');
			redirect('auth');
		}
	}

	public function logout() {
			$this->session->unset_userdata('email');
			$this->session->unset_userdata('role_id');

			$this->session->set_flashdata('message', '<div class="alert alert-success text-center" role="alert">You have been logged out</div>');
			redirect('auth');
	}

	public function blocked() {

		$data['title'] = 'Forbidden';
		$this->load->view('auth/blocked', $data);
	}

	public function forgotPassword() {

		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

		if($this->form_validation->run() == false ) {
			$data['title'] = 'Forgot Password';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/forgot-password', $data);
			$this->load->view('templates/auth_footer', $data);
		} else {
			$email = $this->input->post('email');

			$user = $this->Auth_model->getUserData($email);

			if( $user ) {
				$token = base64_encode(random_bytes(32));

				$user_token = [
					'email' => $this->input->post('email', true),
					'token' => $token,
					'date_created' => time()
				];

				$this->Auth_model->passToken($user_token);

				$this->_sendEmail($token, 'forgot');

				$this->session->set_flashdata('message', '<div class="alert alert-success text-center" role="alert">Check Your Email to reset your password</div>');
				redirect('auth');

			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger text-center" role="alert">Email not registered or account not been activated</div>');
				redirect('auth/forgotpassword');
			}

		}
	}

	public function resetPassword() {
		$email = $this->input->get('email');
		$token = $this->input->get('token');

		$user = $this->Auth_model->dataLogin($email);

		if($user) {

			$user_token = $this->Auth_model->getToken($token);

			if($user_token) {

				$this->session->set_flashdata('reset_email', $email);
				$this->changePassword();

			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger text-center" role="alert">Token Invalid</div>');
				redirect('auth');
			}

		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger text-center" role="alert">Wrong Email</div>');
			redirect('auth');
		}
	}

	public function changePassword() {

		if( !$this->session->userdata('reset_email') ) {
			redirect('auth');
		} else {
			$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[3]|matches[confirmPassword]');
			$this->form_validation->set_rules('confirmPassword', 'Confirm Password', 'required|trim|min_length[3]|matches[password]');

			if( $this->form_validation->run() == false ) {
				$data['title'] = 'Change Password';
				$this->load->view('templates/auth_header', $data);
				$this->load->view('auth/change-password', $data);
				$this->load->view('templates/auth_footer', $data);
			} else {
				$password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
				$email = $this->session->userdata('reset_email');

				$this->Auth_model->changePassword($email, $password);

				$this->session->unset_userdata('reset_email');
				$this->session->set_flashdata('message', '<div class="alert alert-success text-center" role="alert">Password Successfully Changed</div>');
				redirect('auth');
			}
		}
	}

}