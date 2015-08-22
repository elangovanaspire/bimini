<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_activity_guide',
		'title' => 'activity_guide',
		'fields' => array (
			array (
				'key' => 'field_546b454e15735',
				'label' => 'Month',
				'name' => 'month',
				'type' => 'select',
				'required' => 1,
				'choices' => array (
					'January' => 'January',
					'February' => 'February',
					'March' => 'March',
					'April' => 'April',
					'May' => 'May',
					'June' => 'June',
					'July' => 'July',
					'August' => 'August',
					'September' => 'September',
					'October' => 'October',
					'November' => 'November',
					'December' => 'December',
				),
				'default_value' => '',
				'allow_null' => 0,
				'multiple' => 1,
			),
			array (
				'key' => 'field_5489371345627',
				'label' => 'Year',
				'name' => 'year',
				'type' => 'select',
				'required' => 1,
				'choices' => array (
					2014 => 2014,
					2015 => 2015,
					2016 => 2016,
					2017 => 2017,
					2018 => 2018,
					2019 => 2019,
					2020 => 2020,
				),
				'default_value' => '',
				'allow_null' => 0,
				'multiple' => 0,
			),
			array (
				'key' => 'field_5489374c45628',
				'label' => 'Day',
				'name' => 'day',
				'type' => 'checkbox',
				'required' => 1,
				'choices' => array (
					'Everyday' => 'Everyday',
					'Monday' => 'Monday',
					'Tuesday' => 'Tuesday',
					'Wednesday' => 'Wednesday',
					'Thursday' => 'Thursday',
					'Friday' => 'Friday',
					'Saturday' => 'Saturday',
					'Sunday' => 'Sunday',
				),
				'default_value' => '',
				'layout' => 'vertical',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'activity-guide',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));	
	register_field_group(array (
		'id' => 'acf_additional_amenities',
		'title' => 'additional_amenities',
		'fields' => array (
			array (
				'key' => 'field_547577c49e4b1',
				'label' => 'Uid',
				'name' => 'uid',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 80,
			),
			array (
				'key' => 'field_547577e49e4b2',
				'label' => 'Room Number',
				'name' => 'room_number',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 5,
			),
			array (
				'key' => 'field_547577f79e4b3',
				'label' => 'Name',
				'name' => 'name',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 40,
			),
			array (
				'key' => 'field_547578099e4b4',
				'label' => 'Date Time',
				'name' => 'date_time',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 20,
			),
			array (
				'key' => 'field_547578219e4b5',
				'label' => 'Email',
				'name' => 'email',
				'type' => 'email',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_547578309e4b6',
				'label' => 'Message',
				'name' => 'message',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 250,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'additional-amenities',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_area_attractions',
		'title' => 'area_attractions',
		'fields' => array (
			array (
				'key' => 'field_5481a8a7231d2',
				'label' => 'List Page - Image ',
				'name' => 'front_banner_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 340px X 276px	and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_5481a8a7231d2',
					'name' => 'front_banner_image',
					'_name' => 'front_banner_image',
					'id' => 'acf-field-front_banner_image',
					'value' => '',
					'field_group' => 72,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'340\' || $height<>\'276\'){
			 $message="Upload image with pixels 340*276";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_54814b84689c5',
				'label' => 'List Page - View Map',
				'name' => 'map',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png files upto 2MB size',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_54814b84689c5',
					'name' => 'map',
					'_name' => 'map',
					'id' => 'acf-field-map',
					'value' => '',
					'field_group' => 72,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'none',
				'pattern' => '',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_546b46741174d',
				'label' => 'Description',
				'name' => 'description',
				'type' => 'wysiwyg',
				'required' => 1,
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'no',
			),
			array (
				'key' => 'field_546b47fe1174f',
				'label' => 'Detailed Page - Banner Image',
				'name' => 'banner_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1441px X 450px	and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b47fe1174f',
					'name' => 'banner_image',
					'_name' => 'banner_image',
					'id' => 'acf-field-banner_image',
					'value' => '',
					'field_group' => 72,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'1441\' || $height<>\'450\'){
			 $message="Upload image with pixels 1441*450";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Upload Banner Image',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_546b484a11750',
				'label' => 'Detailed Page - Logo',
				'name' => 'logo',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 468px X 228px and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b484a11750',
					'name' => 'logo',
					'_name' => 'logo',
					'id' => 'acf-field-logo',
					'value' => '',
					'field_group' => 72,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'468\' || $height<>\'228\'){
			 $message="Upload image with pixels 468*228";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'area-attractions',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_area_map',
		'title' => 'area_map',
		'fields' => array (
			array (
				'key' => 'field_546b45a2fd203',
				'label' => 'Map',
				'name' => 'map',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file upto 2MB size',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b45a2fd203',
					'name' => 'map',
					'_name' => 'map',
					'id' => 'acf-field-map',
					'value' => '',
					'field_group' => 71,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'none',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'1441\' || $height<>\'450\'){
			 $message="Upload image with pixels 1441*450";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'area-map',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_banner_image',
		'title' => 'banner_image',
		'fields' => array (
			array (
				'key' => 'field_54895f1ca51c4',
				'label' => 'Banner Image',
				'name' => 'banner_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1441px X 450px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_54895f1ca51c4',
					'name' => 'banner_image',
					'_name' => 'banner_image',
					'id' => 'acf-field-banner_image',
					'value' => '',
					'field_group' => 745,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'1441\' || $height<>\'450\'){
			 $message="Upload image with pixels 1441*450";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
                    array (
				'key' => 'field_5497c465c4748',
				'label' => 'Description',
				'name' => 'description',
				'type' => 'wysiwyg',
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'no',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'banner-image',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_dining_tray',
		'title' => 'dining_tray',
		'fields' => array (
			array (
				'key' => 'field_5477150ce416a',
				'label' => 'Uid',
				'name' => 'uid',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5477151de416b',
				'label' => 'Room Number',
				'name' => 'room_number',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5477153ee416c',
				'label' => 'Name',
				'name' => 'name',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5477154ee416d',
				'label' => 'Date Time',
				'name' => 'date_time',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5477155fe416e',
				'label' => 'Email',
				'name' => 'email',
				'type' => 'email',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_5477156ae416f',
				'label' => 'Message',
				'name' => 'message',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'dining-tray',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_fitness_center',
		'title' => 'fitness_center',
		'fields' => array (
			array (
				'key' => 'field_546b47cd875e5',
				'label' => 'Detailed Page - Banner Image',
				'name' => 'banner_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1441px X 450px',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b47cd875e5',
					'name' => 'banner_image',
					'_name' => 'banner_image',
					'id' => 'acf-field-banner_image',
					'value' => '',
					'field_group' => 74,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'1441\' || $height<>\'450\'){
			 $message="Upload image with pixels 1441*450";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_546b4815875e6',
				'label' => 'Detailed Page - Logo',
				'name' => 'logo',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 468px X 228px and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b4815875e6',
					'name' => 'logo',
					'_name' => 'logo',
					'id' => 'acf-field-logo',
					'value' => '',
					'field_group' => 74,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'468\' || $height<>\'228\'){
			 $message="Upload image with pixels 468*228";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_546b46e2875e3',
				'label' => 'Description',
				'name' => 'description',
				'type' => 'wysiwyg',
				'required' => 1,
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'no',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'fitness-center',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_food_category',
		'title' => 'food_category',
		'fields' => array (
			array (
				'key' => 'field_54817c09d91d1',
				'label' => 'Image',
				'name' => 'image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 349px X 512px	and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_54817c09d91d1',
					'name' => 'image',
					'_name' => 'image',
					'id' => 'acf-field-image',
					'value' => '',
					'field_group' => 586,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'none',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if(!$value){
			 $message="Upload image with pixels 349*512".$value ;
			 return false;
	 }else {
			 $message="Upload image with pixels 349*512".$value ;
			 return false;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'ef_taxonomy',
					'operator' => '==',
					'value' => 'food_category',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_inroom_dining_view_menu',
		'title' => 'inroom_dining_view_menu',
		'fields' => array (
			array (
				'key' => 'field_54813e5fdc11e',
				'label' => 'Menu',
				'name' => 'menu',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_54813e5fdc11e',
					'name' => 'menu',
					'_name' => 'menu',
					'id' => 'acf-field-menu',
					'value' => '',
					'field_group' => 562,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'none',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if(!empty($width) && !empty($height) && ($width<>\'413\' || $height<>\'197\')){
			 $message="Upload image with pixels 413*197";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'inroom-dining-menu',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));	
	register_field_group(array (
		'id' => 'acf_mailid',
		'title' => 'mailid',
		'fields' => array (
			array (
				'key' => 'field_546b10c2d1a7b',
				'label' => 'To',
				'name' => 'to_emailaddress',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_546b1783fdb04',
				'label' => 'Subject',
				'name' => 'subject',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_546b1795fdb05',
				'label' => 'Message(Body)',
				'name' => 'message_header',
				'type' => 'wysiwyg',
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'no',
			),
			array (
				'key' => 'field_546b337693fad',
				'label' => 'Message(Footer)',
				'name' => 'message_footer',
				'type' => 'wysiwyg',
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'no',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'email-template',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_make_up_room',
		'title' => 'make_up_room',
		'fields' => array (
			array (
				'key' => 'field_546c3b4755a5b',
				'label' => 'Uid',
				'name' => 'uid',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 80,
			),
			array (
				'key' => 'field_546c3b5b55a5c',
				'label' => 'Room Number',
				'name' => 'room_number',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 10,
			),
			array (
				'key' => 'field_546c3b6a55a5d',
				'label' => 'Name',
				'name' => 'name',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 40,
			),
			array (
				'key' => 'field_546c3b7855a5e',
				'label' => 'Date Time',
				'name' => 'date_time',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 20,
			),
			array (
				'key' => 'field_546c3c0455a5f',
				'label' => 'Email',
				'name' => 'email',
				'type' => 'email',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_546c3c1555a60',
				'label' => 'Message',
				'name' => 'message',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 250,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'make-up-room',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_night_life',
		'title' => 'night_life',
		'fields' => array (
			array (
				'key' => 'field_546b480f479d4',
				'label' => 'Logo',
				'name' => 'logo',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 468px X 228px and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b480f479d4',
					'name' => 'logo',
					'_name' => 'logo',
					'id' => 'acf-field-logo',
					'value' => '',
					'field_group' => 73,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'468\' || $height<>\'228\'){
			 $message="Upload image with pixels 468*228";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_546b46cc479d1',
				'label' => 'Description',
				'name' => 'description',
				'type' => 'wysiwyg',
				'required' => 1,
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'no',
			),
			array (
				'key' => 'field_546b47c0479d3',
				'label' => 'Detailed Page - Banner Image',
				'name' => 'banner_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1441px X 450px	and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b47c0479d3',
					'name' => 'banner_image',
					'_name' => 'banner_image',
					'id' => 'acf-field-banner_image',
					'value' => '',
					'field_group' => 73,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'1441\' || $height<>\'450\'){
			 $message="Upload image with pixels 1441*450";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'night-life',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_planning_a_meeting',
		'title' => 'planning_a_meeting',
		'fields' => array (
			array (
				'key' => 'field_548968496037e',
				'label' => 'Description',
				'name' => 'description',
				'type' => 'wysiwyg',
				'required' => 1,
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'no',
			),
			array (
				'key' => 'field_5489686c6037f',
				'label' => 'Detailed Page - Banner Image',
				'name' => 'banner_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1441px X 450px	and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_5489686c6037f',
					'name' => 'banner_image',
					'_name' => 'banner_image',
					'id' => 'acf-field-banner_image',
					'value' => '',
					'field_group' => 748,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'1441\' || $height<>\'450\'){
			 $message="Upload image with pixels 1441*450";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'planning-a-meeting',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_pool',
		'title' => 'pool',
		'fields' => array (
			array (
				'key' => 'field_546b47d7c90ea',
				'label' => 'Detailed Page - Banner Image',
				'name' => 'banner_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1441px X 450px	and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b47d7c90ea',
					'name' => 'banner_image',
					'_name' => 'banner_image',
					'id' => 'acf-field-banner_image',
					'value' => '',
					'field_group' => 75,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'1441\' || $height<>\'450\'){
			 $message="Upload image with pixels 1441*450";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_546b4822c90eb',
				'label' => 'Logo',
				'name' => 'logo',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 468px X 228px and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b4822c90eb',
					'name' => 'logo',
					'_name' => 'logo',
					'id' => 'acf-field-logo',
					'value' => '',
					'field_group' => 75,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'468\' || $height<>\'228\'){
			 $message="Upload image with pixels 468*228";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_546b46e7c90e8',
				'label' => 'Description',
				'name' => 'description',
				'type' => 'wysiwyg',
				'required' => 1,
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'no',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'pool',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_promotions',
		'title' => 'promotions',
		'fields' => array (
			array (
				'key' => 'field_5481b3047b4a2',
				'label' => 'List Page - Image',
				'name' => 'front_banner_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 340px X 276px	and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_5481b3047b4a2',
					'name' => 'front_banner_image',
					'_name' => 'front_banner_image',
					'id' => 'acf-field-front_banner_image',
					'value' => '',
					'field_group' => 76,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'340\' || $height<>\'276\'){
			 $message="Upload image with pixels 340*276";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_546b47e02c489',
				'label' => 'Detailed Page - Banner Image',
				'name' => 'banner_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1441px X 450px	and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b47e02c489',
					'name' => 'banner_image',
					'_name' => 'banner_image',
					'id' => 'acf-field-banner_image',
					'value' => '',
					'field_group' => 76,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'1441\' || $height<>\'450\'){
			 $message="Upload image with pixels 1441*450";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_546b48282c48a',
				'label' => 'Detailed Page - Logo',
				'name' => 'logo',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 468px X 228px and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b48282c48a',
					'name' => 'logo',
					'_name' => 'logo',
					'id' => 'acf-field-logo',
					'value' => '',
					'field_group' => 76,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'468\' || $height<>\'228\'){
			 $message="Upload image with pixels 468*228";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_546b46ec2c487',
				'label' => 'Description',
				'name' => 'description',
				'type' => 'wysiwyg',
				'required' => 1,
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'no',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'promotions',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_property_map',
		'title' => 'property_map',
		'fields' => array (
			array (
				'key' => 'field_546b457e1b522',
				'label' => 'Map',
				'name' => 'map',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file upto 2MB size',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b457e1b522',
					'name' => 'map',
					'_name' => 'map',
					'id' => 'acf-field-map',
					'value' => '',
					'field_group' => 70,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'none',
				'pattern' => '',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'property-map',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_request_car',
		'title' => 'request_car',
		'fields' => array (
			array (
				'key' => 'field_54771a098c1a3',
				'label' => 'Uid',
				'name' => 'uid',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 80,
			),
			array (
				'key' => 'field_54771a258c1a4',
				'label' => 'Room Number',
				'name' => 'room_number',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 10,
			),
			array (
				'key' => 'field_54771a378c1a5',
				'label' => 'Name',
				'name' => 'name',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 40,
			),
			array (
				'key' => 'field_54771a4a8c1a6',
				'label' => 'Date Time',
				'name' => 'date_time',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 20,
			),
			array (
				'key' => 'field_547ff10a3eb65',
				'label' => 'Formatted Date Time',
				'name' => 'formatted_date_time',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 20,
			),
			array (
				'key' => 'field_54771a5a8c1a7',
				'label' => 'Email',
				'name' => 'email',
				'type' => 'email',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_54771a688c1a8',
				'label' => 'Message',
				'name' => 'message',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 250,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'request-car',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_request_lugguage',
		'title' => 'request_lugguage',
		'fields' => array (
			array (
				'key' => 'field_5477143bdea2b',
				'label' => 'Uid',
				'name' => 'uid',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 80,
			),
			array (
				'key' => 'field_54771468dea2e',
				'label' => 'Room Number',
				'name' => 'room_number',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 10,
			),
			array (
				'key' => 'field_54771446dea2c',
				'label' => 'Name',
				'name' => 'name',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 40,
			),
			array (
				'key' => 'field_547714bbdea2f',
				'label' => 'Date Time',
				'name' => 'date_time',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 20,
			),
			array (
				'key' => 'field_54771451dea2d',
				'label' => 'Email',
				'name' => 'email',
				'type' => 'email',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_547714c6dea30',
				'label' => 'Message',
				'name' => 'message',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 250,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'request-lugguage',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_request_pillows',
		'title' => 'request_pillows',
		'fields' => array (
			array (
				'key' => 'field_5475758468734',
				'label' => 'Uid',
				'name' => 'uid',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 80,
			),
			array (
				'key' => 'field_547575a668735',
				'label' => 'Room Number',
				'name' => 'room_number',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 10,
			),
			array (
				'key' => 'field_547575b668736',
				'label' => 'Name',
				'name' => 'name',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 40,
			),
			array (
				'key' => 'field_547575cb68737',
				'label' => 'Date Time',
				'name' => 'date_time',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 20,
			),
			array (
				'key' => 'field_547575e968738',
				'label' => 'Email',
				'name' => 'email',
				'type' => 'email',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_547575fa68739',
				'label' => 'Message',
				'name' => 'message',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 80,
			),
			array (
				'key' => 'field_547720d29a6e7',
				'label' => 'pillows',
				'name' => 'pillows',
				'type' => 'number',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'request-pillows',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_restaurants',
		'title' => 'restaurants',
		'fields' => array (
			array (
				'key' => 'field_546b346311912',
				'label' => 'Description',
				'name' => 'description',
				'type' => 'wysiwyg',
				'required' => 1,
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'no',
			),
			array (
				'key' => 'field_546b349311914',
				'label' => 'Detailed Page - Banner Image',
				'name' => 'banner_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1441px X 450px and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b349311914',
					'name' => 'banner_image',
					'_name' => 'banner_image',
					'id' => 'acf-field-banner_image',
					'value' => '',
					'field_group' => 51,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width>\'1441\' || $height>\'450\'){
			 $message="Upload image with pixels 1441*450";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_548157402e154',
				'label' => 'Logo',
				'name' => 'logo',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 468px X 228px and size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_548157402e154',
					'name' => 'logo',
					'_name' => 'logo',
					'id' => 'acf-field-logo',
					'value' => '',
					'field_group' => 51,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'468\' || $height<>\'228\'){
			 $message="Upload image with pixels 468*228";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_54868fc241319',
				'label' => 'Detailed Page - Menu',
				'name' => 'menu',
				'type' => 'validated_field',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_54868fc241319',
					'name' => 'menu',
					'_name' => 'menu',
					'id' => 'acf-field-menu',
					'value' => '',
					'field_group' => 51,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'none',
				'pattern' => '',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_54869068bde91',
				'label' => 'Detailed Page - Reserve Online',
				'name' => 'external_link',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'restaurants-and-bars',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_restaurants_bars_menu',
		'title' => 'restaurants_bars_menu',
		'fields' => array (
			array (
				'key' => 'field_548159f2541fb',
				'label' => 'Menu',
				'name' => 'menu',
				'type' => 'validated_field',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_548159f2541fb',
					'name' => 'menu',
					'_name' => 'menu',
					'id' => 'acf-field-menu',
					'value' => '',
					'field_group' => 578,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'none',
				'pattern' => '',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'restaurants-barsmenu',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_room_order',
		'title' => 'room_order',
		'fields' => array (
			array (
				'key' => 'field_546b304b982fe',
				'label' => 'description',
				'name' => 'description',
				'type' => 'textarea',
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => '',
				'formatting' => 'br',
			),
			array (
				'key' => 'field_546b3067982ff',
				'label' => 'quatity',
				'name' => 'quatity',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_546b308098300',
				'label' => 'price',
				'name' => 'price',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'in_room_ordering',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_set_donot_disturb',
		'title' => 'set_donot_disturb',
		'fields' => array (
			array (
				'key' => 'field_54772461dee64',
				'label' => 'Uid',
				'name' => 'uid',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 80,
			),
			array (
				'key' => 'field_54772477dee65',
				'label' => 'Room Number',
				'name' => 'room_number',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 10,
			),
			array (
				'key' => 'field_54772487dee66',
				'label' => 'Name',
				'name' => 'name',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 40,
			),
			array (
				'key' => 'field_54772499dee67',
				'label' => 'Date Time',
				'name' => 'date_time',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 20,
			),
			array (
				'key' => 'field_547ff17026bb7',
				'label' => 'Formatted Date Time',
				'name' => 'formatted_date_time',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 20,
			),
			array (
				'key' => 'field_547724aadee68',
				'label' => 'Email',
				'name' => 'email',
				'type' => 'email',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_547724b8dee69',
				'label' => 'Message',
				'name' => 'message',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 250,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'set-donot-disturb',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_set_wakeup_call',
		'title' => 'set_wakeup_call',
		'fields' => array (
			array (
				'key' => 'field_547723807f2cb',
				'label' => 'Uid',
				'name' => 'uid',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 80,
			),
			array (
				'key' => 'field_547723997f2cc',
				'label' => 'Room Number',
				'name' => 'room_number',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 10,
			),
			array (
				'key' => 'field_547723b27f2cd',
				'label' => 'Name',
				'name' => 'name',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 40,
			),
			array (
				'key' => 'field_547723c37f2ce',
				'label' => 'Date Time',
				'name' => 'date_time',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 20,
			),
			array (
				'key' => 'field_547ff140705bd',
				'label' => 'Formatted Date Time',
				'name' => 'formatted_date_time',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 20,
			),
			array (
				'key' => 'field_547723d57f2cf',
				'label' => 'Email',
				'name' => 'email',
				'type' => 'email',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_547723e37f2d0',
				'label' => 'Message',
				'name' => 'message',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 250,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'set-wake-up-call',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_shopping',
		'title' => 'shopping',
		'fields' => array (
			array (
				'key' => 'field_546b483d6ae50',
				'label' => 'Logo',
				'name' => 'logo',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 468px X 228px and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b483d6ae50',
					'name' => 'logo',
					'_name' => 'logo',
					'id' => 'acf-field-logo',
					'value' => '',
					'field_group' => 78,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'468\' || $height<>\'228\'){
			 $message="Upload image with pixels 468*228";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_546b46f66ae4d',
				'label' => 'Description',
				'name' => 'description',
				'type' => 'wysiwyg',
				'required' => 1,
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'no',
			),
			array (
				'key' => 'field_546b47f36ae4f',
				'label' => 'Detailed Page - Banner Image',
				'name' => 'banner_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1441px X 450px	and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b47f36ae4f',
					'name' => 'banner_image',
					'_name' => 'banner_image',
					'id' => 'acf-field-banner_image',
					'value' => '',
					'field_group' => 78,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'1441\' || $height<>\'450\'){
			 $message="Upload image with pixels 1441*450";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'shopping',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_spa',
		'title' => 'spa',
		'fields' => array (
			array (
				'key' => 'field_546b47ea7d471',
				'label' => 'Detailed Page - Banner Image',
				'name' => 'banner_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1441px X 450px	and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b47ea7d471',
					'name' => 'banner_image',
					'_name' => 'banner_image',
					'id' => 'acf-field-banner_image',
					'value' => '',
					'field_group' => 77,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'1441\' || $height<>\'450\'){
			 $message="Upload image with pixels 1441*450";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_546b48317d472',
				'label' => 'Detailed Page - Logo',
				'name' => 'logo',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 468px X 228px and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b48317d472',
					'name' => 'logo',
					'_name' => 'logo',
					'id' => 'acf-field-logo',
					'value' => '',
					'field_group' => 77,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'468\' || $height<>\'228\'){
			 $message="Upload image with pixels 468*228";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_546b48797d473',
				'label' => 'Detailed Page - Menu',
				'name' => 'menu',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b48797d473',
					'name' => 'menu',
					'_name' => 'menu',
					'id' => 'acf-field-menu',
					'value' => '',
					'field_group' => 77,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'none',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if(!empty($width) && !empty($height) && ($width<>\'413\' || $height<>\'197\')){
			 $message="Upload image with pixels 413*197";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_54814deeab530',
				'label' => 'Detailed Page - Reserve Online',
				'name' => 'external_link',
				'type' => 'validated_field',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'text',
					'key' => 'field_54814deeab530',
					'name' => 'external_link',
					'_name' => 'external_link',
					'id' => 'acf-field-external_link',
					'value' => '',
					'field_group' => 77,
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'html',
					'maxlength' => '',
				),
				'mask' => '',
				'function' => 'none',
				'pattern' => '',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_546b46f17d46f',
				'label' => 'Description',
				'name' => 'description',
				'type' => 'wysiwyg',
				'required' => 1,
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'no',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'spa',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_testing',
		'title' => 'testing',
		'fields' => array (
			array (
				'key' => 'field_54912171fb2a5',
				'label' => 'Test Text Field',
				'name' => 'test_text_field',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_549121e4caebc',
				'label' => 'Test Image',
				'name' => 'test_image',
				'type' => 'validated_field',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_549121e4caebc',
					'name' => 'test_image',
					'_name' => 'test_image',
					'id' => 'acf-field-test_image',
					'value' => '',
					'field_group' => 863,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'1441\' || $height<>\'450\'){
			 $message="Upload image with pixels 1441*450";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'page',
					'operator' => '==',
					'value' => '2',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_todays_events',
		'title' => 'todays_events',
		'fields' => array (
			array (
				'key' => 'field_548ed500e4082',
				'label' => 'Date',
				'name' => 'start_date',
				'type' => 'validated_field',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'date_picker',
					'key' => 'field_548ed500e4082',
					'name' => 'start_date',
					'_name' => 'start_date',
					'id' => 'acf-field-start_date',
					'value' => '',
					'field_group' => 750,
					'date_format' => 'yy-mm-dd',
					'display_format' => 'mm/dd/yy',
					'first_day' => 1,
				),
				'mask' => '',
				'function' => 'none',
				'pattern' => '',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_54896b5061be4',
				'label' => 'Start Time',
				'name' => 'start_time',
				'type' => 'date_time_picker',
				'required' => 1,
				'show_date' => 'false',
				'date_format' => 'm/d/y',
				'time_format' => 'h:mm TT',
				'show_week_number' => 'false',
				'picker' => 'select',
				'save_as_timestamp' => 'true',
				'get_as_timestamp' => 'false',
			),
			array (
				'key' => 'field_54896b8261be5',
				'label' => 'End Time',
				'name' => 'end_time',
				'type' => 'validated_field',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'date_time_picker',
					'key' => 'field_54896b8261be5',
					'name' => 'end_time',
					'_name' => 'end_time',
					'id' => 'acf-field-end_time',
					'value' => '',
					'field_group' => 750,
					'show_date' => 'false',
					'date_format' => 'm/d/y',
					'time_format' => 'h:mm TT',
					'show_week_number' => 'false',
					'picker' => 'select',
					'save_as_timestamp' => 'true',
					'get_as_timestamp' => 'false',
				),
				'mask' => '',
				'function' => 'none',
				'pattern' => 'if (	$value != "05" )
	{ $message = sprint_f( "trying".$prev_value ); 
	return false; }
			?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_54896ba9ed5e2',
				'label' => 'Venue',
				'name' => 'venue',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 50,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'todays-events',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_turn_down_room',
		'title' => 'turn_down_room',
		'fields' => array (
			array (
				'key' => 'field_547576ed84651',
				'label' => 'Uid',
				'name' => 'uid',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 80,
			),
			array (
				'key' => 'field_547576fd84652',
				'label' => 'Room Number',
				'name' => 'room_number',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 10,
			),
			array (
				'key' => 'field_5475771284653',
				'label' => 'Name',
				'name' => 'name',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 40,
			),
			array (
				'key' => 'field_5475772184654',
				'label' => 'Date Time',
				'name' => 'date_time',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 20,
			),
			array (
				'key' => 'field_5475773b84655',
				'label' => 'Email ',
				'name' => 'email_',
				'type' => 'email',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_5475774884656',
				'label' => 'Message',
				'name' => 'message',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => 250,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'turn-down-room',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
        
        
	register_field_group(array (
		'id' => 'acf_inroom_ordering',
		'title' => 'inroom_ordering',
		'fields' => array (
			array (
				'key' => 'field_548178ac602f8',
				'label' => 'Category',
				'name' => 'category',                                
				'type' => 'post_object',
				'required' => 1,
				'post_type' => array (
					0 => 'menu-category',
				),
				'taxonomy' => array (
					0 => 'all',
				),
				'allow_null' => 0,
				'multiple' => 0,
			),
			array (
				'key' => 'field_548150c04aff4',
				'label' => 'Description',
				'name' => 'description',
				'type' => 'textarea',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => 100,
				'rows' => '',
				'formatting' => 'br',
			),
			array (
				'key' => 'field_548152174aff5',
				'label' => 'Price',
				'name' => 'price',
				'type' => 'number',
				'instructions' => 'Please enter currency in $',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'in-room-ordering',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_menu-category',
		'title' => 'Menu Category',
		'fields' => array (
			array (
				'key' => 'field_5492703c0d25d',
				'label' => 'Description',
				'name' => 'content',
				'type' => 'validated_field',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'wysiwyg',
					'key' => 'field_5492703c0d25d',
					'name' => 'content',
					'_name' => 'content',
					'id' => 'acf-field-content',
					'value' => '',
					'field_group' => 900,
					'default_value' => '',
					'toolbar' => 'full',
					'media_upload' => 'no',
				),
				'mask' => '',
				'function' => 'none',
				'pattern' => '',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_54927106049b5',
				'label' => 'Image',
				'name' => 'image',
				'type' => 'validated_field',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_54927106049b5',
					'name' => 'image',
					'_name' => 'image',
					'id' => 'acf-field-image',
					'value' => '',
					'field_group' => 900,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'349\' || $height<>\'512\'){
			 $message="Upload image with pixels 349*512";
			 return false;
	 }else {
			 return true;
	 }',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'menu-category',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
        
        register_field_group(array (
		'id' => 'acf_players_club',
		'title' => 'players_club',
		'fields' => array (
			array (
				'key' => 'field_546b4a0b0a705',
				'label' => 'Description',
				'name' => 'description',
				'type' => 'wysiwyg',
				'required' => 1,
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'no',
			),
			array (
				'key' => 'field_546b4a410a706',
				'label' => 'Detailed Page - Banner Image',
				'name' => 'banner_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1441px X 450px	and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b4a410a706',
					'name' => 'banner_image',
					'_name' => 'banner_image',
					'id' => 'acf-field-banner_image',
					'value' => '',
					'field_group' => 81,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'1441\' || $height<>\'450\'){
			 $message="Upload image with pixels 1441*450";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'players-club',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_responsible_gaming',
		'title' => 'responsible_gaming',
		'fields' => array (
			array (
				'key' => 'field_546b49ed98944',
				'label' => 'Description',
				'name' => 'description',
				'type' => 'wysiwyg',
				'required' => 1,
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'yes',
			),
			array (
				'key' => 'field_546b4a3798945',
				'label' => 'Detailed Page - Banner Image',
				'name' => 'banner_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1441px X 450px	and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b4a3798945',
					'name' => 'banner_image',
					'_name' => 'banner_image',
					'id' => 'acf-field-banner_image',
					'value' => '',
					'field_group' => 80,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'1441\' || $height<>\'450\'){
			 $message="Upload image with pixels 1441*450";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_548eb8d45a3cb',
				'label' => 'Speed Dial',
				'name' => 'speed_dial',
				'type' => 'number',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'responsible-gaming',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_slots',
		'title' => 'slots',
		'fields' => array (
			array (
				'key' => 'field_546b372335172',
				'label' => 'Description',
				'name' => 'description',
				'type' => 'wysiwyg',
				'required' => 1,
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'no',
			),
			array (
				'key' => 'field_546b373b35173',
				'label' => 'Detailed Page - Banner Image',
				'name' => 'banner_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1441px X 450px	and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b373b35173',
					'name' => 'banner_image',
					'_name' => 'banner_image',
					'id' => 'acf-field-banner_image',
					'value' => '',
					'field_group' => 61,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'1441\' || $height<>\'450\'){
			 $message="Upload image with pixels 1441*450";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'slots',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_table_games',
		'title' => 'table_games',
		'fields' => array (
			array (
				'key' => 'field_546b49dfec95b',
				'label' => 'Description',
				'name' => 'description',
				'type' => 'wysiwyg',
				'required' => 1,
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'no',
			),
			array (
				'key' => 'field_546b4a2dec95c',
				'label' => 'Detailed Page - Banner Image',
				'name' => 'banner_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1441px X 450px	and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b4a2dec95c',
					'name' => 'banner_image',
					'_name' => 'banner_image',
					'id' => 'acf-field-banner_image',
					'value' => '',
					'field_group' => 79,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'1441\' || $height<>\'450\'){
			 $message="Upload image with pixels 1441*450";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'table-games',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_tutorials',
		'title' => 'tutorials',
		'fields' => array (
			array (
				'key' => 'field_546b4a191ea9b',
				'label' => 'Description',
				'name' => 'description',
				'type' => 'wysiwyg',
				'required' => 1,
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'no',
			),
			array (
				'key' => 'field_546b4a4a1ea9c',
				'label' => 'Detailed Page - Banner Image',
				'name' => 'banner_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1441px X 450px	and max size upto 2MB',
				'required' => 1,
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_546b4a4a1ea9c',
					'name' => 'banner_image',
					'_name' => 'banner_image',
					'id' => 'acf-field-banner_image',
					'value' => '',
					'field_group' => 82,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if($width<>\'1441\' || $height<>\'450\'){
			 $message="Upload image with pixels 1441*450";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'tutorials',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
        
	register_field_group(array (
		'id' => 'acf_frames',
		'title' => 'frames',
		'fields' => array (
			array (
				'key' => 'field_549821c50a3d2',
				'label' => 'Landscape 1920x1080',
				'name' => 'landscape_1920_1080',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1920px X 1080px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_549821c50a3d2',
					'name' => 'landscape_1920_1080',
					'_name' => 'landscape_1920_1080',
					'id' => 'acf-field-landscape_1920_1080',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if(((!empty($width)) && $width<>\'1920\') || ((!empty($height)) && $height<>\'1080\')){
			 $message="Upload image with pixels 1920*1080";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_5498227b0a3d3',
				'label' => 'Landscape 1334x750',
				'name' => 'landscape_1334_750',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1334px X 750px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_5498227b0a3d3',
					'name' => 'landscape_1334_750',
					'_name' => 'landscape_1334_750',
					'id' => 'acf-field-landscape_1334_750',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
		if(((!empty($width)) && $width<>\'1334\') || ((!empty($height)) && $height<>\'750\')){
			 $message="Upload image with pixels 1334*750";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_549822ab0a3d4',
				'label' => 'Landscape 1136x640',
				'name' => 'landscape_1136_640',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1136px X 640px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_549822ab0a3d4',
					'name' => 'landscape_1136_640',
					'_name' => 'landscape_1136_640',
					'id' => 'acf-field-landscape_1136_640',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
		if(((!empty($width)) && $width<>\'1136\') || ((!empty($height)) && $height<>\'640\')){
			 $message="Upload image with pixels 1136*640";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_549822d990b7a',
				'label' => 'Landscape 960x640',
				'name' => 'landscape_960_640',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 960px X 640px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_549822d990b7a',
					'name' => 'landscape_960_640',
					'_name' => 'landscape_960_640',
					'id' => 'acf-field-landscape_960_640',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
		if(((!empty($width)) && $width<>\'960\') || ((!empty($height)) && $height<>\'640\')){
			 $message="Upload image with pixels 960*640";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_549822f190b7b',
				'label' => 'Landscape 568x320',
				'name' => 'landscape_568_320',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 568px X 320px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_549822f190b7b',
					'name' => 'landscape_568_320',
					'_name' => 'landscape_568_320',
					'id' => 'acf-field-landscape_568_320',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
		if(((!empty($width)) && $width<>\'568\') || ((!empty($height)) && $height<>\'320\')){
			 $message="Upload image with pixels 568*320";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_549822fb90b7c',
				'label' => 'Landscape 480x320',
				'name' => 'landscape_480_320',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 480px X 320px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_549822fb90b7c',
					'name' => 'landscape_480_320',
					'_name' => 'landscape_480_320',
					'id' => 'acf-field-landscape_480_320',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
		if(((!empty($width)) && $width<>\'480\') || ((!empty($height)) && $height<>\'320\')){
			 $message="Upload image with pixels 480*320";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_549822ff90b7d',
				'label' => 'Landscape 2048x1536 (iPad)',
				'name' => 'landscape_iPad_2048_1536',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 2048px X 1536px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_549822ff90b7d',
					'name' => 'landscape_iPad_2048_1536',
					'_name' => 'landscape_iPad_2048_1536',
					'id' => 'acf-field-landscape_iPad_2048_1536',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
		if(((!empty($width)) && $width<>\'2048\') || ((!empty($height)) && $height<>\'1536\')){
			 $message="Upload image with pixels 2048*1536";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_5498230490b7e',
				'label' => 'Landscape 1024x768 (iPad)',
				'name' => 'landscape_iPad_1024_768',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1024px X 768px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_5498230490b7e',
					'name' => 'landscape_iPad_1024_768',
					'_name' => 'landscape_iPad_1024_768',
					'id' => 'acf-field-landscape_iPad_1024_768',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
		if(((!empty($width)) && $width<>\'1024\') || ((!empty($height)) && $height<>\'768\')){
			 $message="Upload image with pixels 1024*768";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_5498230890b7f',
				'label' => 'Portrait 1080x1920',
				'name' => 'portrait_1080_1920',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1080px X 1920px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_5498230890b7f',
					'name' => 'portrait_1080_1920',
					'_name' => 'portrait_1080_1920',
					'id' => 'acf-field-portrait_1080_1920',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
		if(((!empty($width)) && $width<>\'1080\') || ((!empty($height)) && $height<>\'1920\')){
			 $message="Upload image with pixels 1080*1920";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_549823d590b80',
				'label' => 'Portrait 750x1334',
				'name' => 'portrait_750_1334',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 750px X 1334px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_549823d590b80',
					'name' => 'portrait_750_1334',
					'_name' => 'portrait_750_1334',
					'id' => 'acf-field-portrait_750_1334',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
		if(((!empty($width)) && $width<>\'750\') || ((!empty($height)) && $height<>\'1334\')){
			 $message="Upload image with pixels 750*1334";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_549823d990b81',
				'label' => 'Portrait 640x1136',
				'name' => 'portrait_640_1136',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 640px X 1136px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_549823d990b81',
					'name' => 'portrait_640_1136',
					'_name' => 'portrait_640_1136',
					'id' => 'acf-field-portrait_640_1136',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
		if(((!empty($width)) && $width<>\'640\') || ((!empty($height)) && $height<>\'1136\')){
			 $message="Upload image with pixels 640*1136";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_549823dc90b82',
				'label' => 'Portrait 640x960',
				'name' => 'portrait_640_960',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 640px X 960px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_549823dc90b82',
					'name' => 'portrait_640_960',
					'_name' => 'portrait_640_960',
					'id' => 'acf-field-portrait_640_960',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
	 if(((!empty($width)) && $width<>\'640\') || ((!empty($height)) && $height<>\'960\')){
			 $message="Upload image with pixels 640*960";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_549823e090b83',
				'label' => 'Portrait 320x568',
				'name' => 'portrait_320_568',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 320px X 568px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_549823e090b83',
					'name' => 'portrait_320_568',
					'_name' => 'portrait_320_568',
					'id' => 'acf-field-portrait_320_568',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
		if(((!empty($width)) && $width<>\'320\') || ((!empty($height)) && $height<>\'568\')){
			 $message="Upload image with pixels 320*568";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_549823e490b84',
				'label' => 'Portrait 320x480',
				'name' => 'portrait_320_480',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 320px X 480px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_549823e490b84',
					'name' => 'portrait_320_480',
					'_name' => 'portrait_320_480',
					'id' => 'acf-field-portrait_320_480',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
		if(((!empty($width)) && $width<>\'320\') || ((!empty($height)) && $height<>\'480\')){
			 $message="Upload image with pixels 320*480";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_549823e890b85',
				'label' => 'Portrait 1536x2048 (iPad)',
				'name' => 'portrait_iPad_1536_2048',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1536px X 2048px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_549823e890b85',
					'name' => 'portrait_iPad_1536_2048',
					'_name' => 'portrait_iPad_1536_2048',
					'id' => 'acf-field-portrait_iPad_1536_2048',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
		if(((!empty($width)) && $width<>\'1536\') || ((!empty($height)) && $height<>\'2048\')){
			 $message="Upload image with pixels 1536*2048";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_549823ec90b86',
				'label' => 'Portrait 768x1024 (iPad)',
				'name' => 'portrait_iPad_768_1024',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 768px X 1024px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_549823ec90b86',
					'name' => 'portrait_iPad_768_1024',
					'_name' => 'portrait_iPad_768_1024',
					'id' => 'acf-field-portrait_iPad_768_1024',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
		if(((!empty($width)) && $width<>\'768\') || ((!empty($height)) && $height<>\'1024\')){
			 $message="Upload image with pixels 768*1024";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_549824e790b87',
				'label' => 'Portrait Up 768x1024',
				'name' => 'portrait_up_768_1024',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 768px X 1024px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_549824e790b87',
					'name' => 'portrait_up_768_1024',
					'_name' => 'portrait_up_768_1024',
					'id' => 'acf-field-portrait_up_768_1024',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
		if(((!empty($width)) && $width<>\'768\') || ((!empty($height)) && $height<>\'1024\')){
			 $message="Upload image with pixels 768*1024";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_549824fe90b88',
				'label' => 'Portrait Down 768x1024',
				'name' => 'portrait_down_768_1024',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 768px X 1024px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_549824fe90b88',
					'name' => 'portrait_down_768_1024',
					'_name' => 'portrait_down_768_1024',
					'id' => 'acf-field-portrait_down_768_1024',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
		if(((!empty($width)) && $width<>\'768\') || ((!empty($height)) && $height<>\'1024\')){
			 $message="Upload image with pixels 768*1024";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_5498251190b89',
				'label' => 'Landscape Left 768x1024',
				'name' => 'landscape_left_768_1024',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 768px X 1024px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_5498251190b89',
					'name' => 'landscape_left_768_1024',
					'_name' => 'landscape_left_768_1024',
					'id' => 'acf-field-landscape_left_768_1024',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
		if(((!empty($width)) && $width<>\'768\') || ((!empty($height)) && $height<>\'1024\')){
			 $message="Upload image with pixels 768*1024";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_5498252590b8a',
				'label' => 'Landscape Right 768x1024',
				'name' => 'landscape_right_768_1024',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 768px X 1024px	and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_5498252590b8a',
					'name' => 'landscape_right_768_1024',
					'_name' => 'landscape_right_768_1024',
					'id' => 'acf-field-landscape_right_768_1024',
					'value' => '',
					'field_group' => 928,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
	$width=$meta_values[0][\'width\'];
	$height=$meta_values[0][\'height\'];
		if(((!empty($width)) && $width<>\'768\') || ((!empty($height)) && $height<>\'1024\')){
			 $message="Upload image with pixels 768*1024";
			 return false;
	 }else {
			 return true;
	 }
	?>',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'frames',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
        
        register_field_group(array (
		'id' => 'acf_social_sharing',
		'title' => 'social_sharing',
		'fields' => array (
			array (
				'key' => 'field_5499405590613',
				'label' => 'User Name',
				'name' => 'user_name',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'social-sharing',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
        register_field_group(array (
		'id' => 'acf_weather_api',
		'title' => 'weather_api',
		'fields' => array (
			array (
				'key' => 'field_549d41265ca4f',
				'label' => 'Weather url',
				'name' => 'weather_url',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_549d41625ca50',
				'label' => 'Location',
				'name' => 'location',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'weather-api',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
        register_field_group(array (
		'id' => 'acf_dashboard',
		'title' => 'dashboard',
		'fields' => array (			
                        array (
				'key' => 'field_549a6c13436c5',
				'label' => 'Screen ID',
				'name' => 'screen_id',
				'type' => 'validated_field',
                                'required' => 1,
				'read_only' => 'true',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'text',
					'key' => 'field_54a2868172d34',
					'name' => 'tetsing',
					'_name' => 'tetsing',
					'id' => 'acf-field-tetsing',
					'value' => '',
					'field_group' => 943,
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'html',
					'maxlength' => '',
				),
				'mask' => '',
				'function' => 'none',
				'pattern' => '',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_549a6c3b436c6',
				'label' => 'Carousel Image',
				'name' => 'carousel_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 656 px X 969 px and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_549a6c3b436c6',
					'name' => 'carousel_image',
					'_name' => 'carousel_image',
					'id' => 'acf-field-carousel_image',
					'value' => '',
					'field_group' => 944,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
		$width=$meta_values[0][\'width\'];
		$height=$meta_values[0][\'height\'];
		 if(((!empty($width)) && $width<>\'656\') || ((!empty($height)) && $height<>\'969\')){
				 $message="Upload image with pixels 656*969";
				 return false;
		 }else {
				 return true;
		 }',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_549a6c57436c7',
				'label' => 'Slider Image',
				'name' => 'slider_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 358 px X 345 px and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_549a6c57436c7',
					'name' => 'slider_image',
					'_name' => 'slider_image',
					'id' => 'acf-field-slider_image',
					'value' => '',
					'field_group' => 944,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
		$width=$meta_values[0][\'width\'];
		$height=$meta_values[0][\'height\'];
		 if(((!empty($width)) && $width<>\'358\') || ((!empty($height)) && $height<>\'345\')){
				 $message="Upload image with pixels 358*345";
				 return false;
		 }else {
				 return true;
		 }',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
			array (
				'key' => 'field_549a6c70436c8',
				'label' => 'Screen Image',
				'name' => 'screen_image',
				'type' => 'validated_field',
				'instructions' => 'Use jpg or png file with resolution 1536 px X 684 px and max size upto 2MB',
				'read_only' => 'false',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'image',
					'key' => 'field_549a6c70436c8',
					'name' => 'screen_image',
					'_name' => 'screen_image',
					'id' => 'acf-field-screen_image',
					'value' => '',
					'field_group' => 944,
					'save_format' => 'object',
					'preview_size' => 'full',
					'library' => 'all',
				),
				'mask' => '',
				'function' => 'php',
				'pattern' => '$meta_values = get_post_meta($value,\'_wp_attachment_metadata\');
		$width=$meta_values[0][\'width\'];
		$height=$meta_values[0][\'height\'];
		 if(((!empty($width)) && $width<>\'1536\') || ((!empty($height)) && $height<>\'684\')){
				 $message="Upload image with pixels 1536*684";
				 return false;
		 }else {
				 return true;
		 }',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'page-templates/full-width.php',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'page-templates/contributors.php',
					'order_no' => 0,
					'group_no' => 1,
				),
			),
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'default',
					'order_no' => 0,
					'group_no' => 2,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_dashboard',
		'title' => 'dashboard',
		'fields' => array (                    
                    array (
				'key' => 'field_549d7582d0387',
				'label' => 'iOS Tag Name',
				'name' => 'ios_tag_name',
				'type' => 'validated_field',
				'read_only' => 'true',
				'drafts' => 'true',
				'sub_field' => array (
					'type' => 'text',
					'key' => 'field_54a2868172d34',
					'name' => 'tetsing',
					'_name' => 'tetsing',
					'id' => 'acf-field-tetsing',
					'value' => '',
					'field_group' => 943,
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'html',
					'maxlength' => '',
				),
				'mask' => '',
				'function' => 'none',
				'pattern' => '',
				'message' => 'Validation failed.',
				'unique' => 'non-unique',
				'unique_statuses' => array (
					0 => 'publish',
					1 => 'future',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'page-templates/full-width.php',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'page-templates/contributors.php',
					'order_no' => 0,
					'group_no' => 1,
				),
			),
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'default',
					'order_no' => 0,
					'group_no' => 2,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));


}
