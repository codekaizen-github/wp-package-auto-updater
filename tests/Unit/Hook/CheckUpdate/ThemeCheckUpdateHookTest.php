<?php
/**
 * Test for ThemeCheckUpdateHook.
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate\ThemeCheckUpdateHook;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\ThemePackageMetaValueServiceFactoryContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\ThemePackageMetaValueServiceContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\ThemePackageMetaValueContract;
use Exception;
use Mockery;
use Psr\Log\LoggerInterface;
use stdClass;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Tests for the ThemeCheckUpdateHook class.
 */
class ThemeCheckUpdateHookTest extends TestCase {

	/**
	 * Test that init() adds the filter.
	 */
	public function testInitAddsFilter(): void {
		// Mock the dependencies.
		$localFactory  = Mockery::mock( ThemePackageMetaValueServiceFactoryContract::class );
		$remoteFactory = Mockery::mock( ThemePackageMetaValueServiceFactoryContract::class );
		$logger        = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		$logger->shouldReceive( 'error' );

		$sut = new ThemeCheckUpdateHook( $localFactory, $remoteFactory, $logger );
		// Set up expectations.
		WP_Mock::expectFilterAdded(
			'pre_set_site_transient_update_themes',
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
		$localFactory = Mockery::mock( ThemePackageMetaValueServiceFactoryContract::class );
		$localFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( ThemePackageMetaValueServiceContract::class )
		);
		$remoteFactory = Mockery::mock( ThemePackageMetaValueServiceFactoryContract::class );
		$remoteFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( ThemePackageMetaValueContract::class )
		);
		$logger = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		$logger->shouldReceive( 'error' );
		$sut = new ThemeCheckUpdateHook( $localFactory, $remoteFactory, $logger );
		Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckUpdate\ThemeCheckUpdateFormatter'
		);
		$strategy = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Strategy\CheckUpdate\StandardCheckUpdateStrategy'
		);
		$strategy->shouldReceive( 'checkUpdate' )->andThrow( new Exception( 'Test exception' ) );
		$logger->shouldReceive( 'error' );
		// Call the method under test.
		$transient = new stdClass();
		$result    = $sut->checkUpdate( $transient );
		$this->assertSame( $transient, $result );
	}
}
