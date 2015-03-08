<?php namespace Pressor\Path;
use Pressor\Testing\TestCase;

class ProviderTest extends TestCase {

	protected $useSpy = true;

	function test_construct_NoParams_ReturnsInstanceOfProviderContract()
	{
		$result = new Provider();

		$this->assertInstanceOf('Pressor\Contracts\Path\Provider', $result);
	}
	function test_construct_NoParams_SetsContainerAsNull()
	{
		$provider = new Provider();

		$result = $provider->getContainer();

		$this->assertNull($result);
	}
	function test_construct_Container_SetsContainer()
	{
		$provider = new Provider($container = $this->fakeContainer());

		$result = $provider->getContainer();

		$this->assertEquals($container, $result);
	}
	function test_make_NoParamsContainerSet_CallsOffsetExistsOnContainerWithPathWordressKey()
	{
		$provider = new Provider($mockContainer = $this->fakeContainer());

		$mockContainer->shouldReceive('offsetExists')->once()->with('path.wordpress');

		$provider->make();
	}
	function test_make_NoParamsContainerSetOffsetExistsOnContainerReturnsTrue_CallsOffsetGetOnContainerWithPathWordpressKey()
	{
		$provider = new Provider($mockContainer = $this->fakeContainer());
		$mockContainer->shouldReceive('offsetExists')->with('path.wordpress')->andReturn(true);

		$mockContainer->shouldReceive('offsetGet')->once()->with('path.wordpress');

		$provider->make();
	}
	function test_make_NoParamsContainerSetOffsetExistsOnContainerReturnsTrueAndOffsetGetOnContainerReturnsPath_ReturnsPath()
	{
		$provider = new Provider($stubContainer = $this->fakeContainer());
		$stubContainer->shouldReceive('offsetExists')->with('path.wordpress')->andReturn(true);
		$stubContainer->shouldReceive('offsetGet')->with('path.wordpress')->andReturn('result');

		$result = $provider->make();

		$this->assertEquals('result', $result);
	}
	function test_make_ValidSubpathContainerSetOffsetExistsOnContainerReturnsTrueAndOffsetGetOnContainerReturnsPath_ReturnsPathDirectorySeparatorSubpath()
	{
		$provider = new Provider($stubContainer = $this->fakeContainer());
		$stubContainer->shouldReceive('offsetExists')->with('path.wordpress')->andReturn(true);
		$stubContainer->shouldReceive('offsetGet')->with('path.wordpress')->andReturn($folder = __DIR__ . DIRECTORY_SEPARATOR . 'stubs');

		$result = $provider->make($file = 'path-provider-test-stub.php');

		$this->assertEquals($folder . DIRECTORY_SEPARATOR . $file, $result);
	}
	function test_make_InvalidSubpathContainerSetOffsetExistsOnContainerReturnsTrueAndOffsetGetOnContainerReturnsPath_ReturnsFalse()
	{
		$provider = new Provider($stubContainer = $this->fakeContainer());
		$stubContainer->shouldReceive('offsetExists')->with('path.wordpress')->andReturn(true);
		$stubContainer->shouldReceive('offsetGet')->with('path.wordpress')->andReturn($folder = __DIR__ . '/stubs');

		$result = $provider->make('invalid');

		$this->assertFalse($result);
	}
	function test_make_NoParamsContainerSetOffsetExistsOnContainerReturnsFalse_CallsDefinedWithABSPATH()
	{
		$provider = new Provider($stubContainer = $this->fakeContainer());
		$stubContainer->shouldReceive('offsetExists')->with('path.wordpress')->andReturn(false);

		$provider->make();

		$this->assertFunctionLastCalledWith('defined', array('ABSPATH'));
	}
	function test_make_NoParamsWhenContainerNotSet_CallsDefinedWithABSPATH()
	{
		$provider = new Provider();

		$provider->make();

		$this->assertFunctionLastCalledWith('defined', array('ABSPATH'));
	}
	function test_make_NoParamsWhenContainerNotSetAndDefinedReturnsFalse_ReturnsNull()
	{
		$provider = new Provider();
		$this->spy['defined'] = false;

		$result = $provider->make();

		$this->assertNull($result);
	}
	function test_make_NoParamsWhenContainerNotSetAndDefinedReturnsTrue_CallsConstantWithABSPATH()
	{
		$provider = new Provider();
		$this->spy['defined'] = false;

		$provider->make();

		$this->assertFunctionLastCalledWith('constant', array('ABSPATH'));
	}
	function test_make_NoParamsWhenContainerNotSetAndDefinedReturnsTrueAndConstantReturnsResult_ReturnsResult()
	{
		$provider = new Provider();
		$this->spy['defined'] = false;
		$this->spy['constant'] = 'result';

		$result = $provider->make();

		$this->assertEquals('result', $result);
	}
	function test_make_ValidPathWhenContainerNotSetAndDefinedReturnsTrueAndConstantReturnsPathWithTrailingSlash_PathSlashSubpath()
	{
		$provider = new Provider();
		$this->spy['defined'] = false;
		$this->spy['constant'] = $folder = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR;

		$result = $provider->make($file = 'path-provider-test-stub.php');

		$this->assertEquals($folder . $file, $result);
	}

/*
*/
}

function defined()
{
	return \UnitTesting\FunctionSpy\Spy::defined();
}
function constant()
{
	return \UnitTesting\FunctionSpy\Spy::constant();
}
