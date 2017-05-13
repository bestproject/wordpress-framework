<?php

namespace BestProject\Wordpress\Form;

defined('ABSPATH') or die;

interface FieldInterface {

	/**
	 * This method should return field input HTML.
	 */
	public function getInput();

}