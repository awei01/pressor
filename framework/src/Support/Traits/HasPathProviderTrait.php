<?php namespace Pressor\Support\Traits;
use Pressor\Contracts\Path\Provider as PathProvider;

trait HasPathProviderTrait {

	/**
	 * set the path provider
 	 * @var  Pressor\Contracts\Path\Provider
	 */
	protected $pathProvider;

	/**
	 * set the path provider
 	 * @param  Pressor\Contracts\Path\Provider
	 * @return void
	 */
	 public function setPathProvider(PathProvider $pathProvider = null)
	 {
 		$this->pathProvider = $pathProvider;
	 }

	/**
	 * get the path provider
	 * @return null|Pressor\Contracts\Path\Provider
	 */
	 public function getPathProvider()
	 {
 		return $this->pathProvider;
	 }

	/**
	 * make the wordpress path from the path provider
	 * @param  string $path
	 * @return null|string
	 */
	 public function makeWordpressPath($path = null)
	 {
 		return $this->pathProvider->make($path);
	 }

}
