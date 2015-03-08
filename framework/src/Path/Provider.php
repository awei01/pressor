<?php namespace Pressor\Path;
use Pressor\Contracts\Path\Provider as ProviderContract;
use Pressor\Support\Traits\HasContainerTrait;
use Illuminate\Container\Container;

class Provider implements ProviderContract {
	use HasContainerTrait;

	public function __construct(Container $container = null)
	{
		if ($container) $this->setContainer($container);
	}

	/**
	 * make a path from wordpress installation path without trailing slash
	 * @param  string $path
	 * @return string|null
	 */
	public function make($path = null)
	{
		if ($result = $this->makeFolderPath() and $path)
		{
			$result = realpath($result . DIRECTORY_SEPARATOR . $path);
		}

		return $result;
	}

	protected function makeFolderPath()
	{
		if (!$folder = $this->getPathFromContainer()) $folder = $this->getPathFromConstant();

		if ($folder and ends_with($folder, DIRECTORY_SEPARATOR))
		{
			$folder = substr($folder, 0, -1);
		}

		return $folder;
	}

	protected function getPathFromContainer()
	{
		if ($this->container and isset($this->container['path.wordpress']))
		{
			return $this->container['path.wordpress'];
		};
	}

	protected function getPathFromConstant()
	{
		$name = 'ABSPATH';
		return defined($name) ? : constant($name);
	}

}
