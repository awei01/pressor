<?php namespace Pressor\Plugins\UpdateDisabler;
use Pressor\Testing\TestCase;

class UpdateDisablerPluginProviderTest extends TestCase {

	protected $useApp = true;

	protected function makeProvider()
	{
		$this->prepareAppForPluginProvider();
		$this->app['pressor'] = $this->fakePressor();
		return new UpdateDisablerPluginProvider($this->app);
	}

	function test_construct_NoParams_SetsIsDeferredAsFalse()
	{
		$provider = $this->makeProvider();

		$this->assertFalse($provider->isDeferred());
	}
	function test_boot_NoParams_PublishesConfigFilesToConfigPath()
	{
		$provider = $this->makeProvider();
		$provider->boot();

		$result = UpdateDisablerPluginProvider::pathsToPublish(__NAMESPACE__ . '\UpdateDisablerPluginProvider');

		$this->assertEquals(array(
			$this->extractSrcPath('Plugins/UpdateDisabler/configs.php') => config_path('pressor.plugins.update-disabler.php'),
		), $result);
	}
	function test_register_NoParams_MergesConfigsKeyWithConfigsFile()
	{
		$provider = $this->makeProvider();

		$this->app['config']->shouldReceive('set')->once()->with('pressor.plugins.update-disabler', require $this->extractSrcPath('Plugins/UpdateDisabler/configs.php'));

		$provider->register();
	}
	function test_register_NoParams_SetsAppPluginClassnameAsSingletonOfPlugin()
	{
		$provider = $this->makeProvider();
		$provider->register();
		$plugin = $this->app[$classname = __NAMESPACE__ . '\UpdateDisabler'];
		$second = $this->app[$classname];

		$result = array($plugin instanceof UpdateDisabler, $plugin === $second);

		$this->assertEquals(array(true, true), $result);
	}
	function test_register_NoParamsWhenAppMakesPluginClassnameKey_SetsOnPluginPressorAndConfigsFromPressorPluginsUpdateDisablerKey()
	{
		$provider = $this->makeProvider();
		$provider->register();
		$this->app['config']->shouldReceive('get')->with('pressor.plugins.update-disabler')->andReturn(array('configs'));
		$plugin = $this->app[$classname = __NAMESPACE__ . '\UpdateDisabler'];

		$result = array($plugin->getPressor(), $plugin->getConfigs());

		$this->assertEquals(array($this->app['pressor'], array('configs')), $result);
	}
	function test_register_NoParamsWhenAppMakesPluginClassnameKey_CallsActionOnHooksFactoryWithPluginsLoadedEventAndArrayWithPluginAndBootAsMethod()
	{
		$provider = $this->makeProvider();
		$provider->register();

		$this->app['pressor.hooks']->shouldReceive('action')->once()->andReturnUsing(function($event, $method)
		{
			list($plugin, $name) = $method;
			$result = array($event, $plugin instanceof UpdateDisabler, $name);
			$this->assertEquals(array('plugins_loaded', true, 'boot'), $result);
		});

		$this->app[__NAMESPACE__ . '\UpdateDisabler'];
	}

// shouldLoadOnRequest
	function test_shouldLoadOnRequest_RequestContext_CallsIsAdminOnRequestContextWithNoArgs()
	{
		$provider = $this->makeProvider();
		$mockRequest = $this->fakePressorRequest();

		$mockRequest->shouldReceive('isAdmin')->once()->withNoArgs();

		$provider->shouldLoadOnRequest($mockRequest);
	}
	function test_shouldLoadOnRequest_RequestContextWhenIsAdminReturnsResult_ReturnsResult()
	{
		$provider = $this->makeProvider();
		$stubRequest = $this->fakePressorRequest();
		$stubRequest->shouldReceive('isAdmin')->andReturn('result');

		$result = $provider->shouldLoadOnRequest($stubRequest);

		$this->assertEquals('result', $result);
	}

/*
*/
}

