<?php
/**
 * Test
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Service\Value\PackageMeta\Plugin\Remote
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Service\Value\PackageMeta\Plugin\Remote;

// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\Plugin\Remote\CachingRemotePluginPackageMetaValueServiceFactory;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\PluginPackageMetaValueServiceFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PluginPackageMetaValueServiceContract;
use Mockery;
use WP_Mock\Tools\TestCase;

/**
 * Undocumented class
 */
class CachingRemotePluginPackageMetaValueServiceFactoryTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testCreate(): void {
		$mockProvider = Mockery::mock( PluginPackageMetaValueServiceContract::class );
		$factory      = Mockery::mock( PluginPackageMetaValueServiceFactoryContract::class );
		$factory->shouldReceive( 'create' )->once()->andReturn( $mockProvider );
		$sut    = new CachingRemotePluginPackageMetaValueServiceFactory( $factory );
		$return = $sut->create();
		$this->assertInstanceOf( PluginPackageMetaValueServiceContract::class, $return );
	}

	/**
	 * Test that create() caches the provider and returns the same instance on subsequent calls.
	 *
	 * @return void
	 */
	public function testCreateCachesProvider(): void {
		$mockProvider = Mockery::mock( PluginPackageMetaValueServiceContract::class );
		$factory      = Mockery::mock( PluginPackageMetaValueServiceFactoryContract::class );
		$factory->shouldReceive( 'create' )->once()->andReturn( $mockProvider );
		$sut     = new CachingRemotePluginPackageMetaValueServiceFactory( $factory );
		$return1 = $sut->create();
		$return2 = $sut->create();
		// phpcs:ignore Generic.Files.LineLength.TooLong
		$this->assertSame( $return1, $return2, 'Caching factory should return the same instance on subsequent calls.' );
		// phpcs:ignore Generic.Files.LineLength.TooLong
		$this->assertSame( $mockProvider, $return1, 'Caching factory should return the provider from the decorated factory.' );
	}
}
