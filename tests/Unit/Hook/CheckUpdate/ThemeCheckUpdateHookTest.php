<?php
/**
 * Test for ThemeCheckUpdateHook.
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate\ThemeCheckUpdateHook;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Provider\PackageMeta\ThemePackageMetaProviderContract;
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
		$localFactory  = Mockery::mock( ThemePackageMetaProviderFactoryContract::class );
		$remoteFactory = Mockery::mock( ThemePackageMetaProviderFactoryContract::class );
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
	 * Mock a CheckUpdateStrategy and have it throw an exception on the checkUpdate call.
	 * Test to ensure that the sut gracefully handles the exception and logs an error.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testExceptionHandlingInCheckUpdate(): void {
		// Mock the dependencies.
		$localFactory = Mockery::mock( ThemePackageMetaProviderFactoryContract::class );
		$localFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( ThemePackageMetaProviderContract::class )
		);
		$remoteFactory = Mockery::mock( ThemePackageMetaProviderFactoryContract::class );
		$remoteFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( ThemePackageMetaProviderContract::class )
		);
		$logger = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		$logger->shouldReceive( 'error' );
		$sut = new ThemeCheckUpdateHook( $localFactory, $remoteFactory, $logger );
		Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate\ThemeCheckUpdateFormatter'
		);
		$strategy = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Strategy\CheckUpdateStrategy'
		);
		$strategy->shouldReceive( 'checkUpdate' )->andThrow( new Exception( 'Test exception' ) );
		$logger->shouldReceive( 'error' );
		// Call the method under test.
		$transient = new stdClass();
		$result    = $sut->checkUpdate( $transient );
		$this->assertSame( $transient, $result );
	}
}
