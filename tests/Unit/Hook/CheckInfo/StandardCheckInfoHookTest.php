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
use Mockery\MockInterface;
use Psr\Log\LoggerInterface;
use stdClass;
use WP_Mock\Tools\TestCase;

/**
 * Undocumented class
 */
class StandardCheckInfoHookTest extends TestCase {
	/**
	 * Undocumented variable
	 *
	 * @var MockInterface&LoggerInterface
	 */
	protected MockInterface $logger;

	/**
	 * Undocumented variable
	 *
	 * @var PackageMetaValueServiceFactoryContract&MockInterface
	 */
	protected PackageMetaValueServiceFactoryContract $localFactory;

	/**
	 * Undocumented variable
	 *
	 * @var ObjectFactoryContract&MockInterface
	 */
	protected ObjectFactoryContract $remoteFactory;

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->logger        = Mockery::mock( LoggerInterface::class );
		$this->localFactory  = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$this->remoteFactory = Mockery::mock( ObjectFactoryContract::class );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */


	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testInitAddsFilterPluginsAPI() {
		$hookName = 'plugins_api';
		$this->logger->shouldReceive( 'debug' );
		$sut = new StandardCheckInfoHook( $hookName, $this->remoteFactory, $this->localFactory, $this->logger );

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
		$hookName = 'plugins_api';
		$this->logger->shouldReceive( 'debug' );
		$sut = new StandardCheckInfoHook( $hookName, $this->remoteFactory, $this->localFactory, $this->logger );

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
		$hookName     = 'plugins_api';
		$localService = Mockery::mock( PackageMetaValueServiceContract::class );
		$localValue   = Mockery::mock( PackageMetaValueContract::class );
		$localValue->shouldReceive( 'getShortSlug' )->andReturn( 'test-plugin' );
		$localService->shouldReceive( 'getPackageMeta' )->andReturn( $localValue );
		$this->localFactory->shouldReceive( 'create' )->andReturn( $localService );
		$this->logger->shouldReceive( 'debug' );
		$meta = (object) [ 'foo' => 'bar' ];
		$this->remoteFactory->shouldReceive( 'create' )->andReturn( $meta );
		$sut    = new StandardCheckInfoHook( $hookName, $this->remoteFactory, $this->localFactory, $this->logger );
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
		$hookName     = 'plugins_api';
		$localService = Mockery::mock( PackageMetaValueServiceContract::class );
		$localValue   = Mockery::mock( PackageMetaValueContract::class );
		$localValue->shouldReceive( 'getShortSlug' )->andReturn( 'test-plugin' );
		$localService->shouldReceive( 'getPackageMeta' )->andReturn( $localValue );
		$this->localFactory->shouldReceive( 'create' )->andReturn( $localService );
		$this->logger->shouldReceive( 'debug' );
		$sut    = new StandardCheckInfoHook( $hookName, $this->remoteFactory, $this->localFactory, $this->logger );
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
		$hookName = 'plugins_api';
		$this->logger->shouldReceive( 'debug' );
		$this->logger->shouldReceive( 'error' );
		$this->localFactory->shouldReceive( 'create' )->andThrow( new \Exception( 'Test exception' ) );
		$sut    = new StandardCheckInfoHook( $hookName, $this->remoteFactory, $this->localFactory, $this->logger );
		$arg    = (object) [ 'slug' => 'test-plugin' ];
		$result = $sut->checkInfo( false, 'plugin_information', $arg );
		$this->assertFalse( $result );
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testSlugNotSetValid(): void {
		$hookName     = 'plugins_api';
		$localService = Mockery::mock( PackageMetaValueServiceContract::class );
		$localValue   = Mockery::mock( PackageMetaValueContract::class );
		$localValue->shouldReceive( 'getShortSlug' )->andReturn( 'test-plugin' );
		$localService->shouldReceive( 'getPackageMeta' )->andReturn( $localValue );
		$this->localFactory->shouldReceive( 'create' )->andReturn( $localService );
		$this->logger->shouldReceive( 'debug' );
		$sut       = new StandardCheckInfoHook( $hookName, $this->remoteFactory, $this->localFactory, $this->logger );
		$arg       = new stdClass();
		$arg->slug = null;
		$actual    = $sut->checkInfo( false, '', $arg );
		$this->assertFalse( $actual );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testSlugsDoNotMatchValid(): void {
		$hookName     = 'plugins_api';
		$localService = Mockery::mock( PackageMetaValueServiceContract::class );
		$localValue   = Mockery::mock( PackageMetaValueContract::class );
		$localValue->shouldReceive( 'getShortSlug' )->andReturn( 'test-plugin' );
		$localService->shouldReceive( 'getPackageMeta' )->andReturn( $localValue );
		$this->localFactory->shouldReceive( 'create' )->andReturn( $localService );
		$this->logger->shouldReceive( 'debug' );
		$this->logger->shouldReceive( 'info' );
		$this->logger->shouldReceive( 'error' );
		$sut       = new StandardCheckInfoHook( $hookName, $this->remoteFactory, $this->localFactory, $this->logger );
		$arg       = new stdClass();
		$arg->slug = null;
		$actual    = $sut->checkInfo( false, 'other-plugin', $arg );
		$this->assertFalse( $actual );
	}
}
