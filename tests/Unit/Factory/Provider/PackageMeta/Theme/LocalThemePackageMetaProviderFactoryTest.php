<?php
/**
 * Test
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Provider\PackageMeta\Theme
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Provider\PackageMeta\Theme;

use CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Theme\LocalThemePackageMetaProviderFactory;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaContract;
use Mockery;
use Psr\Log\LoggerInterface;
use WP_Mock\Tools\TestCase;

/**
 * Undocumented class
 */
class LocalThemePackageMetaProviderFactoryTest extends TestCase {
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
		Mockery::mock( 'overload:CodeKaizen\WPPackageAutoUpdater\Parser\Slug\ThemeSlugParser' );
		// phpcs:disable Generic.Files.LineLength.TooLong
		$providerFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactoryV1'
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
		$providerFactory->shouldReceive( 'create' )->andReturn( Mockery::mock( ThemePackageMetaContract::class ) );
		$sut    = new LocalThemePackageMetaProviderFactory( $filePath, $logger );
		$return = $sut->create();
		$this->assertInstanceOf( ThemePackageMetaContract::class, $return );
	}
}
