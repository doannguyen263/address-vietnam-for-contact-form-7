<?php

add_action('wp_ajax_district_handler', 'district_handler' );
add_action('wp_ajax_nopriv_district_handler', 'district_handler' );
function district_handler()
{
    $code = $_POST['data'];
    echo CTF7_ADDRESS_VN_ROOT_URI.('database/vietnam/quan-huyen/'.$code.'.json');
    if($code && is_numeric($code)){
	   	$str = file_get_contents(CTF7_ADDRESS_VN_ROOT_URI.('/database/vietnam/quan-huyen/'.$code.'.json'));
		$json = json_decode($str, true);

		echo '<option value="">Quận/ Huyện</option>';
		foreach ($json as $key => $value) {
		    echo '<option value="'.$value['name'].'" data-code="'.$key.'">'.$value['name'].'</option>';		}
	}
    die();
}

/*GET Phuong/ Xa*/
add_action('wp_ajax_ward_handler', 'ward_handler' );
add_action('wp_ajax_nopriv_ward_handler', 'ward_handler' );
function ward_handler()
{
    $code = $_POST['data'];
    if($code && is_numeric($code)){
	   	$str = file_get_contents(CTF7_ADDRESS_VN_ROOT_URI.('/database/vietnam/xa-phuong/'.$code.'.json'));
		$json = json_decode($str, true);

		echo '<option value="">Phường/ Xã</option>';
		foreach ($json as $key => $value) {
		    echo '<option value="'.$key.'">'.$value['name'].'</option>';
		}
	}
    die();
}