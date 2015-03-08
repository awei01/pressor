<?php
use UnitTesting\FunctionSpy\SpyTrait;
use UnitTesting\FunctionSpy\Spy;
use UnitTesting\MockeryHelper\MockeryTrait;
use Illuminate\Container\Container;

class start_Test extends \PHPUnit_Framework_TestCase {
	use SpyTrait;
	use MockeryTrait;

	protected $app;

	protected function setUp()
	{
		$this->initSpy();
	}
	protected function tearDown()
	{
		$this->flushSpy();
		$this->closeMocks();
	}

	protected function requireStart()
	{
		require($GLOBALS['paths']['base'] . '/start.php');
	}

	function test_requireStart_AppMethodNotDefined_ThrowsRuntimeException()
	{
		$this->setExpectedException('RuntimeException', 'Laravel framework is not installed or loaded');

		$this->requireStart();
	}

	protected function defineApp()
	{
		if (empty($GLOBALS['app']))
		{
			$GLOBALS['app'] = new Container;
		}
		$this->app = $GLOBALS['app'];
		$this->app['pressor'] = $pressor = $this->mock('Pressor\Contracts\Framework\Pressor');
		$pressor->shouldReceive('boot')->byDefault();

		if (!function_exists('app'))
		{
			function app()
			{
				Spy::app();
				return $GLOBALS['app'];
			}
		}
	}

	function test_requireStart_WhenAppMethodDefined_CallsAppWithNoArgs()
	{
		$this->defineApp();

		$this->requireStart();

		$this->assertFunctionLastCalledWith('app', array());
	}

	function test_requireStart_WhenAppPressorKeyNotDefined_ThrowsRuntimeException()
	{
		$this->defineApp();
		unset($this->app['pressor']);

		$this->setExpectedException('RuntimeException', 'Pressor framework is not installed or its service provider is not registered');

		$this->requireStart();
	}

	function test_requireStart_WhenAppPressorKeySet_CallsBootOnPressorWithNoArgs()
	{
		$this->defineApp();

		$this->app['pressor']->shouldReceive('boot')->once()->withNoArgs();

		$this->requireStart();
	}

}
