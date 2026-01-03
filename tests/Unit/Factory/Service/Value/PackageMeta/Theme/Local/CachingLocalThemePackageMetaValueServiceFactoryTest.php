<?php
/**
 * Test
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Service\Value\PackageMeta\Theme\Local
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Service\Value\PackageMeta\Theme\Local;

// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\Theme\Local\CachingLocalThemePackageMetaValueServiceFactory;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\ThemePackageMetaValueServiceFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\ThemePackageMetaValueServiceContract;
use Mockery;
use WP_Mock\Tools\TestCase;

/**
 * Undocumented class
 */
class CachingLocalThemePackageMetaValueServiceFactoryTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testCreate(): void {
		$mockProvider = Mockery::mock( ThemePackageMetaValueServiceContract::class );
		$factory      = Mockery::mock( ThemePackageMetaValueServiceFactoryContract::class );
		$factory->shouldReceive( 'create' )->once()->andReturn( $mockProvider );
		$sut    = new CachingLocalThemePackageMetaValueServiceFactory( $factory );
		$return = $sut->create();
		$this->assertInstanceOf( ThemePackageMetaValueServiceContract::class, $return );
	}

	/**
	 * Test that create() caches the provider and returns the same instance on subsequent calls.
	 *
	 * @return void
	 */
	public function testCreateCachesProvider(): void {
		$mockProvider = Mockery::mock( ThemePackageMetaValueServiceContract::class );
		$factory      = Mockery::mock( ThemePackageMetaValueServiceFactoryContract::class );
		$factory->shouldReceive( 'create' )->once()->andReturn( $mockProvider );
		$sut     = new CachingLocalThemePackageMetaValueServiceFactory( $factory );
		$return1 = $sut->create();
		$return2 = $sut->create();
		// phpcs:ignore Generic.Files.LineLength.TooLong
		$this->assertSame( $return1, $return2, 'Caching factory should return the same instance on subsequent calls.' );
		// phpcs:ignore Generic.Files.LineLength.TooLong
		$this->assertSame( $mockProvider, $return1, 'Caching factory should return the provider from the decorated factory.' );
	}
}

