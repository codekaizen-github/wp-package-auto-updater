<?php
/**
 * Test file for PluginCheckInfoObjectFactory.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Object\CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Object\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckInfo\PluginCheckInfoObjectFactory;
use CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckInfo\PluginCheckInfoStandardClass;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\PluginPackageMetaValueServiceFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PluginPackageMetaValueServiceContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PluginPackageMetaValueContract;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PluginCheckInfoObjectFactory.
 */
class PluginCheckInfoObjectFactoryTest extends TestCase {
	/**
	 * Common mocks for tests.
	 *
	 * @var MockInterface&PluginPackageMetaValueContract
	 */
	protected MockInterface $packageMeta;

	/**
	 * Undocumented variable
	 *
	 * @var MockInterface&PluginPackageMetaValueServiceContract
	 */
	protected MockInterface $service;

	/**
	 * Undocumented variable
	 *
	 * @var MockInterface&PluginPackageMetaValueServiceFactoryContract
	 */
	protected MockInterface $factory;

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->packageMeta = Mockery::mock( PluginPackageMetaValueContract::class );
		$this->packageMeta->shouldReceive( 'getName' )->andReturn( 'Test Plugin' );
		$this->packageMeta->shouldReceive( 'getShortSlug' )->andReturn( 'test-plugin' );
		$this->packageMeta->shouldReceive( 'getVersion' )->andReturn( '1.0.0' );
		$this->packageMeta->shouldReceive( 'getAuthor' )->andReturn( 'Test Author' );
		$this->packageMeta->shouldReceive( 'getRequiresWordPressVersion' )->andReturn( '5.0' );
		$this->packageMeta->shouldReceive( 'getTested' )->andReturn( '6.0' );
		$this->packageMeta->shouldReceive( 'getRequiresPHPVersion' )->andReturn( '7.4' );
		$this->packageMeta->shouldReceive( 'getViewURL' )->andReturn( 'https://example.com' );
		$this->packageMeta->shouldReceive( 'getDownloadURL' )->andReturn( 'https://example.com/download' );
		$this->packageMeta->shouldReceive( 'getSections' )->andReturn( [] );
		$this->packageMeta->shouldReceive( 'getTags' )->andReturn( [] );

		$this->service = Mockery::mock( PluginPackageMetaValueServiceContract::class );
		$this->service->shouldReceive( 'getPackageMeta' )->andReturn( $this->packageMeta );

		$this->factory = Mockery::mock( PluginPackageMetaValueServiceFactoryContract::class );
		$this->factory->shouldReceive( 'create' )->andReturn( $this->service );
	}
	/**
	 * Test that create() returns a PluginCheckInfoStandardClass.
	 *
	 * @return void
	 */
	public function testCreateReturnsPluginCheckInfoStandardClass(): void {
		$sut    = new PluginCheckInfoObjectFactory( $this->factory );
		$result = $sut->create();
		$this->assertInstanceOf( PluginCheckInfoStandardClass::class, $result );
	}
}
