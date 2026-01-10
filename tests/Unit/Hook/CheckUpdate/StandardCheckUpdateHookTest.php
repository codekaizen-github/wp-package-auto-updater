<?php
/**
 * Test for StandardCheckUpdateHook.
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate\StandardCheckUpdateHook;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\PackageMetaValueServiceFactoryContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\PluginPackageMetaValueServiceFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PackageMetaValueServiceContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PluginPackageMetaValueServiceContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;
use Exception;
use Mockery;
use Psr\Log\LoggerInterface;
use stdClass;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Tests for the StandardCheckUpdateHook class.
 */
class StandardCheckUpdateHookTest extends TestCase {

	/**
	 * Test that init() adds the filter.
	 */
	public function testInitAddsFilterPluginsAPI(): void {
		// Mock the dependencies.
		$hookName      = 'pre_set_site_transient_update_plugins';
		$localFactory  = Mockery::mock( PluginPackageMetaValueServiceFactoryContract::class );
		$remoteFactory = Mockery::mock( PluginPackageMetaValueServiceFactoryContract::class );
		$logger        = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		$logger->shouldReceive( 'error' );

		$sut = new StandardCheckUpdateHook( $hookName, $localFactory, $remoteFactory, $logger );
		// Set up expectations.
		WP_Mock::expectFilterAdded(
			$hookName,
			[ $sut , 'checkUpdate' ]
		);

		// Create the instance and call init.
		$sut->init();

		// This is important for WP_Mock assertions to work.
		$this->assertConditionsMet();
	}
	/**
	 * Test that init() adds the filter for themes.
	 */
	public function testInitAddsFilterThemesAPI(): void {
		// Mock the dependencies.
		$hookName      = 'pre_set_site_transient_update_themes';
		$localFactory  = Mockery::mock( PluginPackageMetaValueServiceFactoryContract::class );
		$remoteFactory = Mockery::mock( PluginPackageMetaValueServiceFactoryContract::class );
		$logger        = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		$logger->shouldReceive( 'error' );

		$sut = new StandardCheckUpdateHook( $hookName, $localFactory, $remoteFactory, $logger );
		// Set up expectations.
		WP_Mock::expectFilterAdded(
			$hookName,
			[ $sut , 'checkUpdate' ]
		);

		// Create the instance and call init.
		$sut->init();

		// This is important for WP_Mock assertions to work.
		$this->assertConditionsMet();
	}
	/**
	 * Mock a StandardCheckUpdateStrategy and have it throw an exception on the checkUpdate call.
	 * Test to ensure that the sut gracefully handles the exception and logs an error.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testExceptionHandlingInCheckUpdate(): void {
		// Mock the dependencies.
		$hookName     = 'pre_set_site_transient_update_plugins';
		$localFactory = Mockery::mock( PluginPackageMetaValueServiceFactoryContract::class );
		$localFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( PluginPackageMetaValueServiceContract::class )
		);
		$remoteFactory = Mockery::mock( PluginPackageMetaValueServiceFactoryContract::class );
		$remoteFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( PluginPackageMetaValueServiceContract::class )
		);
		$logger = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		$logger->shouldReceive( 'error' );
		$logger->shouldReceive( 'error' );
		$objectFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Contract\Factory\ObjectFactoryContract'
		);
		$objectFactory->shouldReceive( 'create' )->andThrow( new Exception( 'Test exception' ) );
		// Call the method under test.
		$transient = new stdClass();
		$sut       = new StandardCheckUpdateHook( $hookName, $localFactory, $remoteFactory, $logger );
		$result    = $sut->checkUpdate( $transient );
		$this->assertSame( $transient, $result );
	}

	/**
	 * Tests the checkUpdate method when local version is less than remote version.
	 *
	 * @return void
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testCheckUpdateWhenVersionLessThan(): void {
		// Mock the dependencies.
		$localVersion  = '1.0.0';
		$remoteVersion = '1.1.0';
		$hookName      = 'pre_set_site_transient_update_plugins';
		$localValue    = Mockery::mock( PackageMetaValueContract::class );
		$localValue->shouldReceive( 'getVersion' )->andReturn( $localVersion );
		$localValue->shouldReceive( 'getFullSlug' )->andReturn( 'some-plugin/plugin.php' );
		$localService = Mockery::mock( PackageMetaValueServiceContract::class );
		$localService->shouldReceive( 'getPackageMeta' )->andReturn( $localValue );
		$localFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$localFactory->shouldReceive( 'create' )->andReturn(
			$localService
		);
		$remoteValue = Mockery::mock( PackageMetaValueContract::class );
		$remoteValue->shouldReceive( 'getVersion' )->andReturn( $remoteVersion );
		$remoteService = Mockery::mock( PackageMetaValueServiceContract::class );
		$remoteService->shouldReceive( 'getPackageMeta' )->andReturn( $remoteValue );
		$remoteFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$remoteFactory->shouldReceive( 'create' )->andReturn(
			$remoteService
		);
		// phpcs:disable Generic.Files.LineLength.TooLong
		$objectFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckUpdate\StandardCheckUpdateObjectFactory'
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
		$objectData = (object) [ 'new_version' => $remoteVersion ];
		$objectFactory->shouldReceive( 'create' )->andReturn( $objectData );
		$logger = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		// Call the method under test.
		$transient           = new stdClass();
		$transient->checked  = [ 'some-plugin/plugin.php' => '1.0.0' ];
		$transient->response = [];
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$transient->no_update = [];
		$sut                  = new StandardCheckUpdateHook( $hookName, $localFactory, $remoteFactory, $logger );
		$result               = $sut->checkUpdate( $transient );
		// Call the method under test.
		$result = $sut->checkUpdate( $transient );
		$this->assertObjectHasProperty( 'no_update', $result );
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->assertIsArray( $result->no_update );
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->assertArrayNotHasKey( 'some-plugin/plugin.php', $result->no_update );
		$this->assertObjectHasProperty( 'response', $result );
		$this->assertIsArray( $result->response );
		$this->assertArrayHasKey( 'some-plugin/plugin.php', $result->response );
		$this->assertSame( $result->response['some-plugin/plugin.php'], $objectData );
	}

	/**
	 * Tests the checkUpdate method when local version is greater than remote version.
	 *
	 * @return void
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testCheckUpdateWhenVersionGreaterThan(): void {
		// Mock the dependencies.
		$localVersion  = '2.0.0';
		$remoteVersion = '1.0.0';
		$hookName      = 'pre_set_site_transient_update_plugins';
		$localValue    = Mockery::mock( PackageMetaValueContract::class );
		$localValue->shouldReceive( 'getVersion' )->andReturn( $localVersion );
		$localValue->shouldReceive( 'getFullSlug' )->andReturn( 'some-plugin/plugin.php' );
		$localService = Mockery::mock( PackageMetaValueServiceContract::class );
		$localService->shouldReceive( 'getPackageMeta' )->andReturn( $localValue );
		$localFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$localFactory->shouldReceive( 'create' )->andReturn(
			$localService
		);
		$remoteValue = Mockery::mock( PackageMetaValueContract::class );
		$remoteValue->shouldReceive( 'getVersion' )->andReturn( $remoteVersion );
		$remoteService = Mockery::mock( PackageMetaValueServiceContract::class );
		$remoteService->shouldReceive( 'getPackageMeta' )->andReturn( $remoteValue );
		$remoteFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$remoteFactory->shouldReceive( 'create' )->andReturn(
			$remoteService
		);
		$objectFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckUpdate\StandardCheckUpdateObjectFactory'
		);
		$objectData    = (object) [ 'new_version' => $remoteVersion ];
		$objectFactory->shouldReceive( 'create' )->andReturn( $objectData );
		$logger = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		// Call the method under test.
		$transient           = new stdClass();
		$transient->checked  = [ 'some-plugin/plugin.php' => $remoteVersion ];
		$transient->response = [];
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$transient->no_update = [];
		$sut                  = new StandardCheckUpdateHook( $hookName, $localFactory, $remoteFactory, $logger );
		$result               = $sut->checkUpdate( $transient );
		// Call the method under test.
		$result = $sut->checkUpdate( $transient );
		$this->assertObjectHasProperty( 'no_update', $result );
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->assertIsArray( $result->no_update );
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->assertArrayHasKey( 'some-plugin/plugin.php', $result->no_update );
		$this->assertObjectHasProperty( 'response', $result );
		$this->assertIsArray( $result->response );
		$this->assertArrayNotHasKey( 'some-plugin/plugin.php', $result->response );
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->assertSame( $result->no_update['some-plugin/plugin.php'], $objectData );
	}

	/**
	 * Tests the checkUpdate method when local version equals remote version.
	 *
	 * @return void
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testCheckUpdateWhenVersionEqual(): void {
		// Mock the dependencies.
		$localVersion  = '2.0.0';
		$remoteVersion = '2.0.0';
		$hookName      = 'pre_set_site_transient_update_plugins';
		$localValue    = Mockery::mock( PackageMetaValueContract::class );
		$localValue->shouldReceive( 'getVersion' )->andReturn( $localVersion );
		$localValue->shouldReceive( 'getFullSlug' )->andReturn( 'some-plugin/plugin.php' );
		$localService = Mockery::mock( PackageMetaValueServiceContract::class );
		$localService->shouldReceive( 'getPackageMeta' )->andReturn( $localValue );
		$localFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$localFactory->shouldReceive( 'create' )->andReturn(
			$localService
		);
		$remoteValue = Mockery::mock( PackageMetaValueContract::class );
		$remoteValue->shouldReceive( 'getVersion' )->andReturn( $remoteVersion );
		$remoteService = Mockery::mock( PackageMetaValueServiceContract::class );
		$remoteService->shouldReceive( 'getPackageMeta' )->andReturn( $remoteValue );
		$remoteFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$remoteFactory->shouldReceive( 'create' )->andReturn(
			$remoteService
		);
		$objectFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckUpdate\StandardCheckUpdateObjectFactory'
		);
		$objectData    = (object) [ 'new_version' => $remoteVersion ];
		$objectFactory->shouldReceive( 'create' )->andReturn( $objectData );
		$logger = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		// Call the method under test.
		$transient           = new stdClass();
		$transient->checked  = [ 'some-plugin/plugin.php' => $remoteVersion ];
		$transient->response = [];
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$transient->no_update = [];
		$sut                  = new StandardCheckUpdateHook( $hookName, $localFactory, $remoteFactory, $logger );
		$result               = $sut->checkUpdate( $transient );
		// Call the method under test.
		$result = $sut->checkUpdate( $transient );
		$this->assertObjectHasProperty( 'no_update', $result );
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->assertIsArray( $result->no_update );
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->assertArrayHasKey( 'some-plugin/plugin.php', $result->no_update );
		$this->assertObjectHasProperty( 'response', $result );
		$this->assertIsArray( $result->response );
		$this->assertArrayNotHasKey( 'some-plugin/plugin.php', $result->response );
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->assertSame( $result->no_update['some-plugin/plugin.php'], $objectData );
	}

	/**
	 * Tests the checkUpdate method when transient checked property is empty.
	 *
	 * @return void
	 */
	public function testCheckUpdateWhenTransientCheckedIsEmpty(): void {
		$localVersion  = '1.0.0';
		$remoteVersion = '2.0.0';
		$hookName      = 'pre_set_site_transient_update_plugins';
		$localFactory  = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$remoteFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$logger        = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		// Call the method under test.
		// Set up transient object with empty checked property.
		$transient          = new stdClass();
		$transient->checked = [];
		$sut                = new StandardCheckUpdateHook( $hookName, $localFactory, $remoteFactory, $logger );
		$result             = $sut->checkUpdate( $transient );
		// Call the method under test.
		$result = $sut->checkUpdate( $transient );
		$this->assertObjectHasProperty( 'checked', $result );
		$this->assertIsArray( $result->checked );
		$this->assertCount( 0, $result->checked );
		$this->assertObjectNotHasProperty( 'response', $result );
		$this->assertObjectNotHasProperty( 'no_update', $result );
	}

	/**
	 * Tests the checkUpdate method when transient checked property is missing.
	 *
	 * @return void
	 */
	public function testCheckUpdateWhenTransientCheckedIsMissing(): void {
		// Set up transient object without checked property.
		$hookName      = 'pre_set_site_transient_update_plugins';
		$localFactory  = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$remoteFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$logger        = Mockery::mock( LoggerInterface::class );
		$transient     = new stdClass();
		// Set up logger to expect debug messages.
		$logger->shouldReceive( 'debug' )->once()->with(
			'Entering StandardCheckUpdateHook::checkUpdate',
			[ 'transient' => $transient ]
		)->andReturnNull();
		$logger->shouldReceive( 'debug' )->once()->with(
			'Checking for updates',
			[
				'transient' => $transient,
			]
		)->andReturnNull();
		$logger->shouldReceive( 'debug' )->once()->with(
			'No checked packages in transient, skipping'
		)->andReturnNull();
		// Call the method under test.
		$sut    = new StandardCheckUpdateHook( $hookName, $localFactory, $remoteFactory, $logger );
		$result = $sut->checkUpdate( $transient );
		// Verify result is unchanged.
		$this->assertSame( $transient, $result );
	}

	/**
	 * Tests the checkUpdate method when response property doesn't exist.
	 *
	 * @return void
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testCheckUpdateWhenResponsePropertyDoesNotExist(): void {
		// No response property set.
		// Mock the dependencies.
		$localVersion  = '1.0.0';
		$remoteVersion = '1.1.0';
		$hookName      = 'pre_set_site_transient_update_plugins';
		$localValue    = Mockery::mock( PackageMetaValueContract::class );
		$localValue->shouldReceive( 'getVersion' )->andReturn( $localVersion );
		$localValue->shouldReceive( 'getFullSlug' )->andReturn( 'some-plugin/plugin.php' );
		$localService = Mockery::mock( PackageMetaValueServiceContract::class );
		$localService->shouldReceive( 'getPackageMeta' )->andReturn( $localValue );
		$localFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$localFactory->shouldReceive( 'create' )->andReturn(
			$localService
		);
		$remoteValue = Mockery::mock( PackageMetaValueContract::class );
		$remoteValue->shouldReceive( 'getVersion' )->andReturn( $remoteVersion );
		$remoteService = Mockery::mock( PackageMetaValueServiceContract::class );
		$remoteService->shouldReceive( 'getPackageMeta' )->andReturn( $remoteValue );
		$remoteFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$remoteFactory->shouldReceive( 'create' )->andReturn(
			$remoteService
		);
		$objectFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckUpdate\StandardCheckUpdateObjectFactory'
		);
		$objectData    = (object) [ 'new_version' => $remoteVersion ];
		$objectFactory->shouldReceive( 'create' )->andReturn( $objectData );
		$logger = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		// Call the method under test.
		$transient          = new stdClass();
		$transient->checked = [ 'some-plugin/plugin.php' => '1.0.0' ];
		$sut                = new StandardCheckUpdateHook( $hookName, $localFactory, $remoteFactory, $logger );
		$result             = $sut->checkUpdate( $transient );
		// Call the method under test.
		$result = $sut->checkUpdate( $transient );
		$this->assertObjectHasProperty( 'response', $result );
		$this->assertIsArray( $result->response );
		$this->assertArrayHasKey( 'some-plugin/plugin.php', $result->response );
		$this->assertSame( $result->response['some-plugin/plugin.php'], $objectData );
	}

	/**
	 * Tests the checkUpdate method when no_update property doesn't exist .
	 *
	 * @return void
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testCheckUpdateWhenNoUpdatePropertyDoesNotExist(): void {
		// Mock the dependencies.
		$localVersion  = '2.0.0';
		$remoteVersion = '1.0.0';
		$hookName      = 'pre_set_site_transient_update_plugins';
		$localValue    = Mockery::mock( PackageMetaValueContract::class );
		$localValue->shouldReceive( 'getVersion' )->andReturn( $localVersion );
		$localValue->shouldReceive( 'getFullSlug' )->andReturn( 'some-plugin/plugin.php' );
		$localService = Mockery::mock( PackageMetaValueServiceContract::class );
		$localService->shouldReceive( 'getPackageMeta' )->andReturn( $localValue );
		$localFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$localFactory->shouldReceive( 'create' )->andReturn(
			$localService
		);
		$remoteValue = Mockery::mock( PackageMetaValueContract::class );
		$remoteValue->shouldReceive( 'getVersion' )->andReturn( $remoteVersion );
		$remoteService = Mockery::mock( PackageMetaValueServiceContract::class );
		$remoteService->shouldReceive( 'getPackageMeta' )->andReturn( $remoteValue );
		$remoteFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$remoteFactory->shouldReceive( 'create' )->andReturn(
			$remoteService
		);
		$objectFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckUpdate\StandardCheckUpdateObjectFactory'
		);
		$objectData    = (object) [ 'new_version' => $remoteVersion ];
		$objectFactory->shouldReceive( 'create' )->andReturn( $objectData );
		$logger = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		// Set up transient object without no_update property.
		$transient          = new stdClass();
		$transient->checked = [ 'some-plugin/plugin.php' => '2.0.0' ];
		$sut                = new StandardCheckUpdateHook( $hookName, $localFactory, $remoteFactory, $logger );
		$result             = $sut->checkUpdate( $transient );
		// Call the method under test.
		$result = $sut->checkUpdate( $transient );
		$this->assertObjectHasProperty( 'no_update', $result );
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->assertIsArray( $result->no_update );
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->assertArrayHasKey( 'some-plugin/plugin.php', $result->no_update );
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->assertSame( $result->no_update['some-plugin/plugin.php'], $objectData );
	}

	/**
	 * Tests the checkUpdate method when response property is not an array.
	 *
	 * @return void
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testCheckUpdateWhenResponsePropertyIsNotArray(): void {

		// No response property set.
		// Mock the dependencies.
		$localVersion  = '1.0.0';
		$remoteVersion = '1.1.0';
		$hookName      = 'pre_set_site_transient_update_plugins';
		$localValue    = Mockery::mock( PackageMetaValueContract::class );
		$localValue->shouldReceive( 'getVersion' )->andReturn( $localVersion );
		$localValue->shouldReceive( 'getFullSlug' )->andReturn( 'some-plugin/plugin.php' );
		$localService = Mockery::mock( PackageMetaValueServiceContract::class );
		$localService->shouldReceive( 'getPackageMeta' )->andReturn( $localValue );
		$localFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$localFactory->shouldReceive( 'create' )->andReturn(
			$localService
		);
		$remoteValue = Mockery::mock( PackageMetaValueContract::class );
		$remoteValue->shouldReceive( 'getVersion' )->andReturn( $remoteVersion );
		$remoteService = Mockery::mock( PackageMetaValueServiceContract::class );
		$remoteService->shouldReceive( 'getPackageMeta' )->andReturn( $remoteValue );
		$remoteFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$remoteFactory->shouldReceive( 'create' )->andReturn(
			$remoteService
		);
		$objectFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckUpdate\StandardCheckUpdateObjectFactory'
		);
		$objectData    = (object) [ 'new_version' => $remoteVersion ];
		$objectFactory->shouldReceive( 'create' )->andReturn( $objectData );
		$logger = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		// Call the method under test.
		$transient           = new stdClass();
		$transient->checked  = [ 'some-plugin/plugin.php' => '1.0.0' ];
		$transient->response = 'not-an-array';
		$sut                 = new StandardCheckUpdateHook( $hookName, $localFactory, $remoteFactory, $logger );
		$result              = $sut->checkUpdate( $transient );
		// Call the method under test.
		$result = $sut->checkUpdate( $transient );
		$this->assertObjectHasProperty( 'response', $result );
		$this->assertIsArray( $result->response );
		$this->assertArrayHasKey( 'some-plugin/plugin.php', $result->response );
		$this->assertSame( $result->response['some-plugin/plugin.php'], $objectData );
	}

	/**
	 * Tests the checkUpdate method when no_update property is not an array.
	 *
	 * @return void
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testCheckUpdateWhenNoUpdatePropertyIsNotArray(): void {
		// Set up transient object with non-array no_update property.

		// Mock the dependencies.
		$localVersion  = '2.0.0';
		$remoteVersion = '1.0.0';
		$hookName      = 'pre_set_site_transient_update_plugins';
		$localValue    = Mockery::mock( PackageMetaValueContract::class );
		$localValue->shouldReceive( 'getVersion' )->andReturn( $localVersion );
		$localValue->shouldReceive( 'getFullSlug' )->andReturn( 'some-plugin/plugin.php' );
		$localService = Mockery::mock( PackageMetaValueServiceContract::class );
		$localService->shouldReceive( 'getPackageMeta' )->andReturn( $localValue );
		$localFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$localFactory->shouldReceive( 'create' )->andReturn(
			$localService
		);
		$remoteValue = Mockery::mock( PackageMetaValueContract::class );
		$remoteValue->shouldReceive( 'getVersion' )->andReturn( $remoteVersion );
		$remoteService = Mockery::mock( PackageMetaValueServiceContract::class );
		$remoteService->shouldReceive( 'getPackageMeta' )->andReturn( $remoteValue );
		$remoteFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$remoteFactory->shouldReceive( 'create' )->andReturn(
			$remoteService
		);
		$objectFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckUpdate\StandardCheckUpdateObjectFactory'
		);
		$objectData    = (object) [ 'new_version' => $remoteVersion ];
		$objectFactory->shouldReceive( 'create' )->andReturn( $objectData );
		$logger = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		// Set up transient object without no_update property.
		$transient          = new stdClass();
		$transient->checked = [ 'some-plugin/plugin.php' => '2.0.0' ];
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$transient->no_update = 'not-an-array';
		$sut                  = new StandardCheckUpdateHook( $hookName, $localFactory, $remoteFactory, $logger );
		$result               = $sut->checkUpdate( $transient );
		// Call the method under test.
		$result = $sut->checkUpdate( $transient );
		$this->assertObjectHasProperty( 'no_update', $result );
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->assertIsArray( $result->no_update );
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->assertArrayHasKey( 'some-plugin/plugin.php', $result->no_update );
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->assertSame( $result->no_update['some-plugin/plugin.php'], $objectData );
	}

	/**
	 * Tests the checkUpdate method when local version is null.
	 *
	 * @return void
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testCheckUpdateWhenLocalVersionIsNull(): void {
		// Mock the dependencies.
		$localVersion  = null;
		$remoteVersion = '1.1.0';
		$hookName      = 'pre_set_site_transient_update_plugins';
		$localValue    = Mockery::mock( PackageMetaValueContract::class );
		$localValue->shouldReceive( 'getVersion' )->andReturn( $localVersion );
		$localValue->shouldReceive( 'getFullSlug' )->andReturn( 'some-plugin/plugin.php' );
		$localService = Mockery::mock( PackageMetaValueServiceContract::class );
		$localService->shouldReceive( 'getPackageMeta' )->andReturn( $localValue );
		$localFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$localFactory->shouldReceive( 'create' )->andReturn(
			$localService
		);
		$remoteValue = Mockery::mock( PackageMetaValueContract::class );
		$remoteValue->shouldReceive( 'getVersion' )->andReturn( $remoteVersion );
		$remoteService = Mockery::mock( PackageMetaValueServiceContract::class );
		$remoteService->shouldReceive( 'getPackageMeta' )->andReturn( $remoteValue );
		$remoteFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$remoteFactory->shouldReceive( 'create' )->andReturn(
			$remoteService
		);
		$objectFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckUpdate\StandardCheckUpdateObjectFactory'
		);
		$objectData    = (object) [ 'new_version' => $remoteVersion ];
		$objectFactory->shouldReceive( 'create' )->andReturn( $objectData );
		$logger = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'warning' );
		$logger->shouldReceive( 'info' );
		// Call the method under test.
		$transient           = new stdClass();
		$transient->checked  = [ 'some-plugin/plugin.php' => '1.0.0' ];
		$transient->response = [];
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$transient->no_update = [];
		$sut                  = new StandardCheckUpdateHook( $hookName, $localFactory, $remoteFactory, $logger );
		$result               = $sut->checkUpdate( $transient );
		// Call the method under test.
		$result = $sut->checkUpdate( $transient );
		$this->assertSame( $transient, $result );
	}

	/**
	 * Tests the checkUpdate method when remote version is null.
	 *
	 * @return void
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testCheckUpdateWhenRemoteVersionIsNull(): void {
		// Mock the dependencies.
		$localVersion  = '1.0.0';
		$remoteVersion = null;
		$hookName      = 'pre_set_site_transient_update_plugins';
		$localValue    = Mockery::mock( PackageMetaValueContract::class );
		$localValue->shouldReceive( 'getVersion' )->andReturn( $localVersion );
		$localValue->shouldReceive( 'getFullSlug' )->andReturn( 'some-plugin/plugin.php' );
		$localService = Mockery::mock( PackageMetaValueServiceContract::class );
		$localService->shouldReceive( 'getPackageMeta' )->andReturn( $localValue );
		$localFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$localFactory->shouldReceive( 'create' )->andReturn(
			$localService
		);
		$remoteValue = Mockery::mock( PackageMetaValueContract::class );
		$remoteValue->shouldReceive( 'getVersion' )->andReturn( $remoteVersion );
		$remoteService = Mockery::mock( PackageMetaValueServiceContract::class );
		$remoteService->shouldReceive( 'getPackageMeta' )->andReturn( $remoteValue );
		$remoteFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$remoteFactory->shouldReceive( 'create' )->andReturn(
			$remoteService
		);
		$objectFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckUpdate\StandardCheckUpdateObjectFactory'
		);
		$objectData    = (object) [ 'new_version' => $remoteVersion ];
		$objectFactory->shouldReceive( 'create' )->andReturn( $objectData );
		$logger = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'warning' );
		$logger->shouldReceive( 'info' );
		// Call the method under test.
		$transient           = new stdClass();
		$transient->checked  = [ 'some-plugin/plugin.php' => '1.0.0' ];
		$transient->response = [];
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$transient->no_update = [];
		$sut                  = new StandardCheckUpdateHook( $hookName, $localFactory, $remoteFactory, $logger );
		$result               = $sut->checkUpdate( $transient );
		// Call the method under test.
		$result = $sut->checkUpdate( $transient );
		$this->assertSame( $transient, $result );
	}
}
