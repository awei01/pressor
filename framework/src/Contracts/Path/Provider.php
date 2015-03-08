<?php namespace Pressor\Contracts\Path;

interface Provider {

	/**
	 * make a path from wordpress installation path
	 * @param  string $path
	 * @return string|null
	 */
	public function make($path = null);

}
