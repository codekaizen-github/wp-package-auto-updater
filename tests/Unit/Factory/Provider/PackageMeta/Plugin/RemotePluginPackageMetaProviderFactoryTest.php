<?php
/**
 * Test
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Provider\PackageMeta\Plugin
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Provider\PackageMeta\Plugin;

use CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Plugin\RemotePluginPackageMetaProviderFactory;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaContract;
use Mockery;
use Psr\Log\LoggerInterface;
use WP_Mock\Tools\TestCase;

/**
 * Undocumented class
 */
class RemotePluginPackageMetaProviderFactoryTest extends TestCase {
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
		Mockery::mock( 'overload:CodeKaizen\WPPackageAutoUpdater\Parser\Slug\PluginSlugParser' );
		// phpcs:disable Generic.Files.LineLength.TooLong
		$providerFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryV1'
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
		$providerFactory->shouldReceive( 'create' )->andReturn( Mockery::mock( PluginPackageMetaContract::class ) );
		$sut    = new RemotePluginPackageMetaProviderFactory( $baseUrl, $metaKey, $logger );
		$return = $sut->create();
		$this->assertInstanceOf( PluginPackageMetaContract::class, $return );
	}
}
