<?php namespace Pressor\Contracts\Framework\Request;

interface Context {

	/**
	 * is this a wordpress admin-side request
	 * @return boolean
	 */
	public function isAdmin();

	/**
	 * is this a wordpress client-side request
	 * @return boolean
	 */
	public function isClient();

	/**
	 * is this a wordpress ajax request
	 * @return boolean
	 */
	public function isAjax();

}
