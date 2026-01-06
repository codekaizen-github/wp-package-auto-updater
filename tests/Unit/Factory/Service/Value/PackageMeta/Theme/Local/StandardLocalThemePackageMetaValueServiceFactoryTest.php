<?php
/**
 * Test
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Service\Value\PackageMeta\Theme\Local
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Service\Value\PackageMeta\Theme\Local;

// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\Theme\Local\StandardLocalThemePackageMetaValueServiceFactory;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\ThemePackageMetaValueServiceContract;
use Mockery;
use Psr\Log\LoggerInterface;
use WP_Mock\Tools\TestCase;

/**
 * Undocumented class
 */
class StandardLocalThemePackageMetaValueServiceFactoryTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @link https://stackoverflow.com/a/72639610/13461208
	 * @return void
	 */
	public function testCreate(): void {
		$filePath = '';
		$logger   = Mockery::mock( LoggerInterface::class );
		$logger->allows(
			[
				'debug' => null,
				'info'  => null,
				'error' => null,
			]
		);
		Mockery::mock( 'overload:CodeKaizen\WPPackageAutoUpdater\Value\Slug\ThemeSlugValue' );
		// phpcs:disable Generic.Files.LineLength.TooLong
		$providerFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderLocal\Factory\Service\Value\PackageMeta\Theme\StandardThemePackageMetaValueServiceFactory'
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
		$mockProvider = Mockery::mock( ThemePackageMetaValueServiceContract::class );
		$providerFactory->shouldReceive( 'create' )->andReturn( $mockProvider );
		$sut    = new StandardLocalThemePackageMetaValueServiceFactory( $filePath, $logger );
		$return = $sut->create();
		$this->assertInstanceOf( ThemePackageMetaValueServiceContract::class, $return );
	}

	/**
	 * Test that create() does not cache the provider and returns a new instance on each call.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @link https://stackoverflow.com/a/72639610/13461208
	 * @return void
	 */
	public function testCreateDoesNotCacheProvider(): void {
		$filePath = '';
		$logger   = Mockery::mock( LoggerInterface::class );
		$logger->allows(
			[
				'debug' => null,
				'info'  => null,
				'error' => null,
			]
		);
		$slugValue = Mockery::mock( 'overload:CodeKaizen\WPPackageAutoUpdater\Value\Slug\ThemeSlugValue' );
		// phpcs:disable Generic.Files.LineLength.TooLong
		$providerFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderLocal\Factory\Service\Value\PackageMeta\Theme\StandardThemePackageMetaValueServiceFactory'
		);
		$providerFactory->shouldReceive( 'create' )->withNoArgs()->andReturnUsing(
			function () {
				return Mockery::mock( ThemePackageMetaValueServiceContract::class );
			}
		);
		$sut     = new StandardLocalThemePackageMetaValueServiceFactory( $filePath, $logger );
		$return1 = $sut->create();
		$return2 = $sut->create();
		$this->assertNotSame( $return1, $return2, 'Standard factory should return different instances on each call.' );
	}
}
