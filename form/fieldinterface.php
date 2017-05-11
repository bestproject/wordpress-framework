<?php

namespace BestProject\Wordpress\Form;

interface FieldInterface {

	/**
	 * This method should return field input HTML.
	 */
	public function getInput();

}