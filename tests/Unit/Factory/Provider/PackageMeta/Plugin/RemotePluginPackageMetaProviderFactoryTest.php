<?php
/**
 * Test
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Provider\PackageMeta\Plugin
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Provider\PackageMeta\Plugin;

use CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Plugin\RemotePluginPackageMetaProviderFactory;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Provider\PackageMeta\PluginPackageMetaProviderContract;
use Mockery;
use Psr\Log\LoggerInterface;
use WP_Mock\Tools\TestCase;
use UnexpectedValueException;
use WP_Mock;
use WP_Mock\Filter_Responder;

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
		$baseUrl     = '';
		$metaKey     = '';
		$httpOptions = [];
		$logger      = Mockery::mock( LoggerInterface::class );
		Mockery::mock( 'overload:CodeKaizen\WPPackageAutoUpdater\Parser\Slug\PluginSlugParser' );
		// phpcs:disable Generic.Files.LineLength.TooLong
		$providerFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryV1'
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
		$providerFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( PluginPackageMetaProviderContract::class )
		);
		$sut    = new RemotePluginPackageMetaProviderFactory( $baseUrl, $metaKey, $httpOptions, $logger );
		$return = $sut->create();
		$this->assertInstanceOf( PluginPackageMetaProviderContract::class, $return );
	}
	/**
	 * Undocumented function.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @link https://stackoverflow.com/a/72639610/13461208
	 * @return void
	 */
	public function testFilterInvalidReturn(): void {
		$baseUrl     = '';
		$metaKey     = '';
		$httpOptions = [];
		$logger      = Mockery::mock( LoggerInterface::class );
		Mockery::mock( 'overload:CodeKaizen\WPPackageAutoUpdater\Parser\Slug\PluginSlugParser' );
		// phpcs:disable Generic.Files.LineLength.TooLong
		$providerFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryV1'
		);
		$providerFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( PluginPackageMetaProviderContract::class )
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
		$sut    = new RemotePluginPackageMetaProviderFactory( $baseUrl, $metaKey, $httpOptions, $logger );
		$filter = WP_Mock::onFilter(
			'wp_package_auto_updater_remote_plugin_package_meta_provider_factory_instance_options'
		);
		/**
		 * Ignore phpstan error
		 *
		 * @var Filter_Responder $responder
		 * @phpstan-ignore class.notFound
		 */
		$responder = $filter->withAnyArgs();
		// @phpstan-ignore class.notFound
		$responder->reply( null );
		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessage( 'Invalid options provided' );
		$sut->create();
	}
}
