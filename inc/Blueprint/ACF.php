<?php
namespace Blueprint;

abstract class ACF
{
	public static function get_banner_background_fields($acf_group = 'banner')
	{
		return array(
			array (
				'key' => 'field_' . $acf_group . '_background_type',
				'label' => 'Background Type',
				'name' => $acf_group . '_background_type',
				'type' => 'radio',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array (
					'image' => 'Image',
					'video' => 'Video'
				),
				'other_choice' => 0,
				'save_other_choice' => 0,
				'default_value' => 'image',
				'layout' => 'horizontal',
			),
			array (
				'key' => 'field_' . $acf_group . '_background_image',
				'label' => 'Image',
				'name' => $acf_group . '_background_image',
				'instructions' => 'The featured image attached to this page/post will be used if this field is empty',
				'type' => 'image',
				'required' => 0,
				'conditional_logic' => array (
					array (
						array (
							'field' => 'field_' . $acf_group . '_background_type',
							'operator' => '==',
							'value' => 'image',
						),
					),
				),
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'object',       // array | url | id
				'preview_size' => 'medium',
				'library' => 'all',       // all | uploadedTo
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
			),
			array (
				'key' => 'field_' . $acf_group . '_background_image_pos_horz',
				'label' => 'Horizontal Position',
				'name' => $acf_group . '_background_image_pos_horz',
				'type' => 'number',
				'instructions' => 'Adjust the horizontal image position from 0 (left aligned) to 100 (right aligned).',
				'required' => 0,
				'conditional_logic' => array (
					array (
						array (
							'field' => 'field_' . $acf_group . '_background_type',
							'operator' => '==',
							'value' => 'image',
						),
					),
				),
				'wrapper' => array (
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'default_value' => 50,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => 0,
				'max' => 100,
				'step' => 1,
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'field_' . $acf_group . '_background_image_pos_vert',
				'label' => 'Vertical Position',
				'name' => $acf_group . '_background_image_pos_vert',
				'type' => 'number',
				'instructions' => 'Adjust the vertical image position from 0 (top aligned) to 100 (bottom aligned).',
				'required' => 0,
				'conditional_logic' => array (
					array (
						array (
							'field' => 'field_' . $acf_group . '_background_type',
							'operator' => '==',
							'value' => 'image',
						),
					),
				),
				'wrapper' => array (
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'default_value' => 50,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => 0,
				'max' => 100,
				'step' => 1,
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'field_' . $acf_group . '_background_video_message',
				'label' => 'A note about background videos',
				'name' => $acf_group . '_background_video_message',
				'type' => 'message',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array (
					array (
						array (
							'field' => 'field_' . $acf_group . '_background_type',
							'operator' => '==',
							'value' => 'video',
						),
					),
				),
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => 'For the best performance on mobile and tablet devices please provide appropriately sized videos for each device size. Here are the recommendations on video dimensions and file size for each device type:<ul><li><strong>Mobile</strong> - maximum video height of 480px and keep the file size at 3MB or less</li><li><strong>Tablet</strong> - maximum video height of 720px and keep the file size less than 8MB</li><li><strong>Desktop</strong> - maximum video height of 1080px and keep the file size less than 20MB</li></ul>A video will only play on a specific device size if it has been given a video URL.<br><br><strong>NOTE:</strong> The video URL must be a direct link to a <code>.mp4</code> file. Links to YouTube, Vimeo, or any other types of video embeds will not work.',
				'new_lines' => 'wpautop',    // wpautop | br | ''
				'esc_html' => 0,             // uses the WordPress esc_html function
			),
			array (
				'key' => 'field_' . $acf_group . '_background_video_url_desktop',
				'label' => 'Video URL (desktop)',
				'name' => $acf_group . '_background_video_url_desktop',
				'type' => 'text',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => array (
					array (
						array (
							'field' => 'field_' . $acf_group . '_background_type',
							'operator' => '==',
							'value' => 'video',
						),
					),
				),
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'formatting' => 'none',       // none | html
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'field_' . $acf_group . '_background_video_url_tablet',
				'label' => 'Video URL (tablet)',
				'name' => $acf_group . '_background_video_url_tablet',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array (
					array (
						array (
							'field' => 'field_' . $acf_group . '_background_type',
							'operator' => '==',
							'value' => 'video',
						),
					),
				),
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'formatting' => 'none',       // none | html
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'field_' . $acf_group . '_background_video_url_mobile',
				'label' => 'Video URL (mobile)',
				'name' => $acf_group . '_background_video_url_mobile',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array (
					array (
						array (
							'field' => 'field_' . $acf_group . '_background_type',
							'operator' => '==',
							'value' => 'video',
						),
					),
				),
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'formatting' => 'none',       // none | html
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'field_' . $acf_group . '_background_video_poster',
				'label' => 'Poster Image',
				'name' => $acf_group . '_background_video_poster',
				'instructions' => 'This image will be displayed while the video is loading. It can be helpful to use the first frame of the video here to smooth the transition.',
				'type' => 'image',
				'required' => 1,
				'conditional_logic' => array (
					array (
						array (
							'field' => 'field_' . $acf_group . '_background_type',
							'operator' => '==',
							'value' => 'video',
						),
					),
				),
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'object',       // array | url | id
				'preview_size' => 'medium',
				'library' => 'all',       // all | uploadedTo
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
			),
		);
	}
}
