<?php namespace Pressor\Framework\Extensions;
use Pressor\Testing\TestCase;

class RegistryServiceProviderTest extends TestCase {

	protected $useApp = true;

	protected function makeProvider()
	{
		$this->prepareAppForServiceProvider();
		return new RegistryServiceProvider($this->app);
	}

	function test_construct_NoParams_SetsIsDeferredAsFalse()
	{
		$provider = $this->makeProvider();

		$this->assertFalse($provider->isDeferred());
	}
	function test_boot_NoParamsAfterRegister_PublishesConfigFilesToConfigPath()
	{
		$provider = $this->makeProvider();
		$provider->register();
		$provider->boot();

		$result = RegistryServiceProvider::pathsToPublish(__NAMESPACE__ . '\RegistryServiceProvider');

		$this->assertEquals(array(
			$this->extractSrcPath('Framework/Extensions/providers.php') => config_path('pressor.registry.php'),
		), $result);
	}
	function test_boot_NoParamsAfterRegister_CallsBootstrapOnPressorRegistryKeyWithNoArgs()
	{
		$provider = $this->makeProvider();
		$provider->register();
		$mockRegistry = $this->app['pressor.registry'] = $this->fakePressorRegistry();

		$mockRegistry->shouldReceive('bootstrap')->once()->withNoArgs();

		$provider->boot();
	}

	function test_register_NoParams_MergesPressorRegistryKeyWithKeysFile()
	{
		$provider = $this->makeProvider();

		$this->app['config']->shouldReceive('set')->once()->with('pressor.registry', require $this->extractSrcPath('Framework/Extensions/providers.php'));

		$provider->register();
	}
	function test_register_NoParams_SetsAppPressorRegistryKeyAsSingletonOfRegistry()
	{
		$provider = $this->makeProvider();
		$provider->register();
		$registry = $this->app['pressor.registry'];
		$second = $this->app['pressor.registry'];

		$result = array($registry instanceof Registry, $registry === $second);

		$this->assertEquals(array(true, true), $result);
	}
	function test_register_NoParamsWhenKeyMadeAndReturnsRegistry_SetsOnRegistryContainerAsAppAndPressorRegistryConfigsKeyAsProviders()
	{
		$provider = $this->makeProvider();
		$provider->register();
		$this->app['config']->shouldReceive('get')->with('pressor.registry')->andReturn(array('providers'));
		$registry = $this->app['pressor.registry'];

		$result = array($registry->getContainer(), $registry->getProviders());

		$this->assertEquals(array($this->app, array('providers')), $result);
	}

/*
*/
}
