<?php
/**
 * Test
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Provider\PackageMeta\Theme
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Provider\PackageMeta\Theme;

// phpcs:disable Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Contract\Argument\Filter\Factory\Provider\PackageMeta\Remote\CreateRemotePackageMetaProviderFactoryFilterArgumentContract;
use CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Theme\RemoteThemePackageMetaProviderFactory;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Provider\PackageMeta\ThemePackageMetaProviderContract;
use Mockery;
use Psr\Log\LoggerInterface;
use stdClass;
use UnexpectedValueException;
use WP_Mock;
use WP_Mock\Tools\TestCase;
use WP_Mock\Filter_Responder;

// phpcs:enable Generic.Files.LineLength.TooLong

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
		$baseUrl     = '';
		$metaKey     = '';
		$httpOptions = [];
		$logger      = Mockery::mock( LoggerInterface::class );
		Mockery::mock( 'overload:CodeKaizen\WPPackageAutoUpdater\Parser\Slug\ThemeSlugParser' );
		// phpcs:disable Generic.Files.LineLength.TooLong
		$providerFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactoryV1'
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
		$providerFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( ThemePackageMetaProviderContract::class )
		);
		$sut    = new RemoteThemePackageMetaProviderFactory( $baseUrl, $metaKey, $httpOptions, $logger );
		$return = $sut->create();
		$this->assertInstanceOf( ThemePackageMetaProviderContract::class, $return );
	}
	/**
	 * Undocumented function.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @link https://stackoverflow.com/a/72639610/13461208
	 * @return void
	 */
	public function testFilterNullReturnInvalid(): void {
		$baseUrl     = '';
		$metaKey     = '';
		$httpOptions = [];
		$logger      = Mockery::mock( LoggerInterface::class );
		Mockery::mock( 'overload:CodeKaizen\WPPackageAutoUpdater\Parser\Slug\ThemeSlugParser' );
		// phpcs:disable Generic.Files.LineLength.TooLong
		$providerFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactoryV1'
		);
		$providerFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( ThemePackageMetaProviderContract::class )
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
		$sut    = new RemoteThemePackageMetaProviderFactory( $baseUrl, $metaKey, $httpOptions, $logger );
		$filter = WP_Mock::onFilter(
			'wp_package_auto_updater_remote_theme_package_meta_provider_factory_v1_instance_options'
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
	/**
	 * Undocumented function.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @link https://stackoverflow.com/a/72639610/13461208
	 * @return void
	 */
	public function testFilterStdClassReturnInvalid(): void {
		$baseUrl     = '';
		$metaKey     = '';
		$httpOptions = [];
		$logger      = Mockery::mock( LoggerInterface::class );
		Mockery::mock( 'overload:CodeKaizen\WPPackageAutoUpdater\Parser\Slug\ThemeSlugParser' );
		// phpcs:disable Generic.Files.LineLength.TooLong
		$providerFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactoryV1'
		);
		$providerFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( ThemePackageMetaProviderContract::class )
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
		$sut    = new RemoteThemePackageMetaProviderFactory( $baseUrl, $metaKey, $httpOptions, $logger );
		$filter = WP_Mock::onFilter(
			'wp_package_auto_updater_remote_theme_package_meta_provider_factory_v1_instance_options'
		);
		/**
		 * Ignore phpstan error
		 *
		 * @var Filter_Responder $responder
		 * @phpstan-ignore class.notFound
		 */
		$responder = $filter->withAnyArgs();
		// @phpstan-ignore class.notFound
		$responder->reply(
			new stdClass()
		);
		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessage( 'Invalid options provided' );
		$sut->create();
	}
	/**
	 * Undocumented function.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @link https://stackoverflow.com/a/72639610/13461208
	 * @return void
	 */
	public function testFilterUpdateValuesValid(): void {
		$baseUrl            = '';
		$metaKey            = '';
		$httpOptions        = [];
		$logger             = Mockery::mock( LoggerInterface::class );
		$baseUrlUpdated     = 'https://example.com/';
		$metaKeyUpdated     = 'updated_meta_key';
		$httpOptionsUpdated = [ 'timeout' => 30 ];
		$loggerUpdated      = Mockery::mock( LoggerInterface::class );
		Mockery::mock( 'overload:CodeKaizen\WPPackageAutoUpdater\Parser\Slug\ThemeSlugParser' );
		// phpcs:disable Generic.Files.LineLength.TooLong
		$providerFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactoryV1'
		);
		$providerFactory->shouldReceive( '__construct' )->once()->with(
			$baseUrlUpdated,
			$metaKeyUpdated,
			$httpOptionsUpdated,
			$loggerUpdated
		);
		$providerFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( ThemePackageMetaProviderContract::class )
		);
		$argument = Mockery::mock( CreateRemotePackageMetaProviderFactoryFilterArgumentContract::class );
		$argument->shouldReceive( 'getBaseURL' )->andReturn( $baseUrlUpdated );
		$argument->shouldReceive( 'getMetaKey' )->andReturn( $metaKeyUpdated );
		$argument->shouldReceive( 'getHttpOptions' )->andReturn( $httpOptionsUpdated );
		$argument->shouldReceive( 'getLogger' )->andReturn( $loggerUpdated );
		// phpcs:enable Generic.Files.LineLength.TooLong
		$sut    = new RemoteThemePackageMetaProviderFactory( $baseUrl, $metaKey, $httpOptions, $logger );
		$filter = WP_Mock::onFilter(
			'wp_package_auto_updater_remote_theme_package_meta_provider_factory_v1_instance_options'
		);
		/**
		 * Ignore phpstan error
		 *
		 * @var Filter_Responder $responder
		 * @phpstan-ignore class.notFound
		 */
		$responder = $filter->withAnyArgs();
		// @phpstan-ignore class.notFound
		$responder->reply(
			$argument
		);
		$sut->create();
		$this->assertConditionsMet();
	}
}
