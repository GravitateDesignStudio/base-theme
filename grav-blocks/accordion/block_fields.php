<?php

/*
*
* Accordion Block
*
* Available Variables:
* $block 					= Name of Block Folder
* $block_backgrounds 		= Array for Background Options
* $block_background_image = Array for Background Image Option
*
* This file must return an array();
*
*/

$block_fields = array(
    array(
        'key' => 'field_' . $block . '_accordion  ',
        'label' => 'Accordion',
        'name' => $block . '_accordion',
        'type' => 'repeater',
        'layout' => 'row',
        'button_label' => 'Add Item',
        'sub_fields' => array(
            array (
                'key' => 'field_'.$block.'_heading',
                'label' => 'Heading',
                'name' => $block.'_heading',
                'type' => 'text',
            ),
            array (
                'key' => 'field_'.$block.'_content',
                'label' => 'Content',
                'name' => $block.'_content',
                'type' => 'wysiwyg',
                'full' => 'basic',  // full | basic
            )
        ),
    )
);

return array(
    'label' => 'Accordion',
    'name' => $block,
    'display' => 'block',
    'min' => '',
    'max' => '',
    'sub_fields' => $block_fields,
    'grav_blocks_settings' => array(
        'icon' => 'gravicon-gallery'
    ),
);
