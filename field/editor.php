<?php

namespace BestProject\Wordpress\Field;

use BestProject\Wordpress\Field;
/**
 * TODO: Fixind displaying of widget interface.
 */
class Editor extends Field
{

	/**
	 * This this be a minimal editor.
	 * 
	 * @var	Boolean
	 */
	public $teeny = false;

	/**
	 * Get this fields input
	 */
	public function getInput()
	{


		ob_start();

		add_action( 'media_buttons', [$this, 'removeFilters']);
		add_action( 'tiny_mce_before_init', [$this, 'fixEditorSettings']);

		wp_editor($this->value, $this->id, array(
			'teeny'=>$this->teeny,
			'textarea_name'=>$this->name,
		));


		return ob_get_clean();
	}

	public function removeFilters(){
		remove_filter( 'the_editor_content', 'wp_htmledit_pre' );
		remove_filter( 'the_editor_content', 'wp_richedit_pre' );
	}

	public function fixEditorSettings($init){
		$init = array_merge($init, array(
			'convert_fonts_to_spans' => false,
			'verify_html' => false,
			'fix_list_elements' => false,
			'forced_root_block' => false,
			'invalid_elements' => '',
			'invalid_styles' => '',
			'keep_styles' => true,
		));

		return $init;
	}
}