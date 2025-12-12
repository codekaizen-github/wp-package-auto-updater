<?php
/**
 * Test
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Provider\PackageMeta\Plugin
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Provider\PackageMeta\Plugin;

use CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\Plugin\LocalPluginPackageMetaValueServiceFactory;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PluginPackageMetaValueServiceContract;
use Mockery;
use Psr\Log\LoggerInterface;
use WP_Mock\Tools\TestCase;

/**
 * Undocumented class
 */
class LocalPluginPackageMetaProviderFactoryTest extends TestCase {
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
		Mockery::mock( 'overload:CodeKaizen\WPPackageAutoUpdater\Parser\Slug\PluginSlugParser' );
		// phpcs:disable Generic.Files.LineLength.TooLong
		$providerFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderLocal\Factory\Service\Value\PackageMeta\Plugin\StandardPluginPackageMetaValueServiceFactory'
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
		$providerFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( PluginPackageMetaValueServiceContract::class )
		);
		$sut    = new LocalPluginPackageMetaValueServiceFactory( $filePath, $logger );
		$return = $sut->create();
		$this->assertInstanceOf( PluginPackageMetaValueServiceContract::class, $return );
	}
}
