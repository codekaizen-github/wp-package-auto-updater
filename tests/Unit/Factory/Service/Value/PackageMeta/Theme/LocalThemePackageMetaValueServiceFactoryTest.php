<?php
/**
 * Test
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Service\Value\PackageMeta\Theme
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Service\Value\PackageMeta\Theme;

use CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\Theme\LocalThemePackageMetaValueServiceFactory;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\ThemePackageMetaValueServiceContract;
use Mockery;
use Psr\Log\LoggerInterface;
use WP_Mock\Tools\TestCase;

/**
 * Undocumented class
 */
class LocalThemePackageMetaValueServiceFactoryTest extends TestCase {
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
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		$logger->shouldReceive( 'error' );
		Mockery::mock( 'overload:CodeKaizen\WPPackageAutoUpdater\Value\Slug\ThemeSlugValue' );
		// phpcs:disable Generic.Files.LineLength.TooLong
		$providerFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderLocal\Factory\Service\Value\PackageMeta\Theme\StandardThemePackageMetaValueServiceFactory'
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
		$providerFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( ThemePackageMetaValueServiceContract::class )
		);
		$sut    = new LocalThemePackageMetaValueServiceFactory( $filePath, $logger );
		$return = $sut->create();
		$this->assertInstanceOf( ThemePackageMetaValueServiceContract::class, $return );
	}
}

