<?php

namespace BestProject\Wordpress\PostType;

interface FieldInterface {

	/**
	 * This method should return field input HTML.
	 */
	public function getInput();

}