<?php 

function is_logged_in() {

	$ci = get_instance();
	if( !$ci->session->userdata('email') ) {
		redirect('auth');
	}
	else {
		$role_id = $ci->session->userdata('role_id'); //apakah user atau admin
		$menu = $ci->uri->segment(1); // user/admin/menu

		$queryMenu  = $ci->db->get_where('user_menu', ['menu' => $menu ])->row_array();

		$menu_id = $queryMenu['id'];

		$userAccess = $ci->db->get_where('user_access_menu', ['role_id' => $role_id, 'menu_id' => $menu_id ]);

		if( $userAccess->num_rows() < 1 ) {
			redirect('auth/blocked');
		}
	}

}

function already_logged_in() {

	$ci = get_instance();
	$role = $ci->session->userdata('role_id');

	if( $role == 1 ) {
		redirect('admin');
	}
	else if ( $role == 2 ) {
		redirect('user');
	}
}

function check_access($role_id, $menu_id) {

	$ci = get_instance();

	$ci->db->where('role_id', $role_id);
	$ci->db->where('menu_id', $menu_id);
	$result	= $ci->db->get('user_access_menu');

	if( $result->num_rows() > 0 ) {
		return  "checked" ;
	}
}

 ?>