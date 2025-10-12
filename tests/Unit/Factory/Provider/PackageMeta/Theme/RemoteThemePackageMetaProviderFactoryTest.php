<?php
/**
 * Test
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Tests\Unit\Factory\Provider\PackageMeta\Theme;
 */

namespace CodeKaizen\WPPackageAutoUpdater\Tests\Unit\Factory\Provider\PackageMeta\Theme;

use CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Theme\RemoteThemePackageMetaProviderFactory;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaContract;
use Mockery;
use Psr\Log\LoggerInterface;
use WP_Mock\Tools\TestCase;

/**
 * Undocumented class
 */
class RemoteThemePackageMetaProviderFactoryTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @link https://stackoverflow.com/a/72639610/13461208
	 * @return void
	 */
	public function testCreate(): void {
		$baseUrl = '';
		$metaKey = '';
		$logger  = Mockery::mock( LoggerInterface::class );
		Mockery::mock( 'overload:CodeKaizen\WPPackageAutoUpdater\Parser\Slug\ThemeSlugParser' );
		// phpcs:disable Generic.Files.LineLength.TooLong
		$providerFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactoryV1'
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
		$providerFactory->shouldReceive( 'create' )->andReturn( Mockery::mock( ThemePackageMetaContract::class ) );
		$sut    = new RemoteThemePackageMetaProviderFactory( $baseUrl, $metaKey, $logger );
		$return = $sut->create();
		$this->assertInstanceOf( ThemePackageMetaContract::class, $return );
	}
}
