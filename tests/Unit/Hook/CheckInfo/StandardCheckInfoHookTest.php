<?php
/**
 * Unit tests for StandardCheckInfoHook.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo\StandardCheckInfoHook;
use CodeKaizen\WPPackageAutoUpdater\Contract\Factory\ObjectFactoryContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\PackageMetaValueServiceFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PackageMetaValueServiceContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;
use Mockery;
use Psr\Log\LoggerInterface;
use WP_Mock\Tools\TestCase;

/**
 * Undocumented class
 */
class StandardCheckInfoHookTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function tearDown(): void {
		Mockery::close();
		parent::tearDown();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testInitAddsFilterPluginsAPI() {
		$hookName      = 'plugins_api';
		$remoteFactory = Mockery::mock( ObjectFactoryContract::class );
		$localFactory  = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$logger        = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$sut = new StandardCheckInfoHook( $hookName, $remoteFactory, $localFactory, $logger );

		// Use WP_Mock if available, otherwise just check no exceptions.
		if ( class_exists( 'WP_Mock' ) ) {
			\WP_Mock::expectFilterAdded( $hookName, [ $sut, 'checkInfo' ], 10, 3 );
		}
		$sut->init();
		$this->expectNotToPerformAssertions();
	}

		/**
		 * Undocumented function
		 *
		 * @return void
		 */
	public function testInitAddsFilterThemesAPI() {
		$hookName      = 'plugins_api';
		$remoteFactory = Mockery::mock( ObjectFactoryContract::class );
		$localFactory  = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$logger        = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$sut = new StandardCheckInfoHook( $hookName, $remoteFactory, $localFactory, $logger );

		// Use WP_Mock if available, otherwise just check no exceptions.
		if ( class_exists( 'WP_Mock' ) ) {
			\WP_Mock::expectFilterAdded( $hookName, [ $sut, 'checkInfo' ], 10, 3 );
		}
		$sut->init();
		$this->expectNotToPerformAssertions();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testCheckInfoReturnsResultWhenSlugMatches() {
		$hookName      = 'plugins_api';
		$remoteFactory = Mockery::mock( ObjectFactoryContract::class );
		$localFactory  = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$localService  = Mockery::mock( PackageMetaValueServiceContract::class );
		$localValue    = Mockery::mock( PackageMetaValueContract::class );
		$localValue->shouldReceive( 'getShortSlug' )->andReturn( 'test-plugin' );
		$localService->shouldReceive( 'getPackageMeta' )->andReturn( $localValue );
		$localFactory->shouldReceive( 'create' )->andReturn( $localService );
		$logger = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$meta = (object) [ 'foo' => 'bar' ];
		$remoteFactory->shouldReceive( 'create' )->andReturn( $meta );
		$sut    = new StandardCheckInfoHook( $hookName, $remoteFactory, $localFactory, $logger );
		$arg    = (object) [ 'slug' => 'test-plugin' ];
		$result = $sut->checkInfo( false, 'plugin_information', $arg );
		$this->assertSame( $meta, $result );
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testCheckInfoReturnsFalseWhenSlugDoesNotMatch() {
		$hookName      = 'plugins_api';
		$remoteFactory = Mockery::mock( ObjectFactoryContract::class );
		$localFactory  = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$localService  = Mockery::mock( PackageMetaValueServiceContract::class );
		$localValue    = Mockery::mock( PackageMetaValueContract::class );
		$localValue->shouldReceive( 'getShortSlug' )->andReturn( 'test-plugin' );
		$localService->shouldReceive( 'getPackageMeta' )->andReturn( $localValue );
		$localFactory->shouldReceive( 'create' )->andReturn( $localService );
		$logger = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$sut    = new StandardCheckInfoHook( $hookName, $remoteFactory, $localFactory, $logger );
		$arg    = (object) [ 'slug' => 'other-plugin' ];
		$result = $sut->checkInfo( false, 'plugin_information', $arg );
		$this->assertFalse( $result );
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testCheckInfoHandlesExceptionAndReturnsFalse() {
		$hookName      = 'plugins_api';
		$remoteFactory = Mockery::mock( ObjectFactoryContract::class );
		$localFactory  = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$logger        = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'error' );
		$localFactory->shouldReceive( 'create' )->andThrow( new \Exception( 'Test exception' ) );
		$sut    = new StandardCheckInfoHook( $hookName, $remoteFactory, $localFactory, $logger );
		$arg    = (object) [ 'slug' => 'test-plugin' ];
		$result = $sut->checkInfo( false, 'plugin_information', $arg );
		$this->assertFalse( $result );
	}
}
