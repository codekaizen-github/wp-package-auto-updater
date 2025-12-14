<?php
/**
 * Test for ThemeCheckInfoHook.
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo\ThemeCheckInfoHook;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\ThemePackageMetaValueServiceFactoryContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\ThemePackageMetaValueServiceContract;
use Exception;
use Mockery;
use Psr\Log\LoggerInterface;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Tests for the ThemeCheckInfoHook class.
 */
class ThemeCheckInfoHookTest extends TestCase {

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

		$sut = new ThemeCheckInfoHook( $localFactory, $remoteFactory, $logger );
		// Set up expectations.
		WP_Mock::expectFilterAdded(
			'themes_api',
			[ $sut , 'checkInfo' ],
			10,
			3
		);

		// Create the instance and call init.
		$sut->init();

		// This is important for WP_Mock assertions to work.
		$this->assertConditionsMet();
	}
	/**
	 * Mock a StandardCheckInfoStrategy and have it throw an exception on the checkInfo call.
	 * Test to ensure that the sut gracefully handles the exception and logs an error.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testExceptionHandlingInCheckInfo(): void {
		// Mock the dependencies.
		$localFactory = Mockery::mock( ThemePackageMetaValueServiceFactoryContract::class );
		$localFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( ThemePackageMetaValueServiceContract::class )
		);
		$remoteFactory = Mockery::mock( ThemePackageMetaValueServiceFactoryContract::class );
		$remoteFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( ThemePackageMetaValueServiceContract::class )
		);
		$logger = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		$logger->shouldReceive( 'error' );
		$sut = new ThemeCheckInfoHook( $localFactory, $remoteFactory, $logger );
		Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo\ThemeCheckInfoFormatter'
		);
		$strategy = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Strategy\CheckInfo\StandardCheckInfoStrategy'
		);
		$strategy->shouldReceive( 'checkInfo' )->andThrow( new Exception( 'Test exception' ) );
		$logger->shouldReceive( 'error' );
		// Call the method under test.
		$result = $sut->checkInfo( false, 'theme_information', (object) [] );
		$this->assertFalse( $result );
	}
}
