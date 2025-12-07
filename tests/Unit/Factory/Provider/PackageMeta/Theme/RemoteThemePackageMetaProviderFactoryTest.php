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
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\ThemePackageMetaValueServiceContract;
use Mockery;
use Mockery\MockInterface;
use Psr\Log\LoggerInterface;
use stdClass;
use WP_Mock\Tools\TestCase;
use UnexpectedValueException;
use WP_Mock;

// phpcs:enable Generic.Files.LineLength.TooLong

/**
 * Undocumented class
 */
class RemoteThemePackageMetaProviderFactoryTest extends TestCase {

	/**
	 * Undocumented variable
	 *
	 * @var ?MockInterface
	 */
	protected ?MockInterface $serviceFactory;


	/**
	 * Undocumented variable
	 *
	 * @var (LoggerInterface&MockInterface)|null
	 */
	protected ?LoggerInterface $logger;

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function setUp(): void {
		// phpcs:disable Generic.Files.LineLength.TooLong
		$this->serviceFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderORASHub\Factory\Service\Value\PackageMeta\Theme\StandardThemePackageMetaValueServiceFactory'
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
		$this->logger = Mockery::mock( LoggerInterface::class );
		$this->getServiceFactory()->allows(
			[
				'create' => Mockery::mock( ThemePackageMetaValueServiceContract::class ),
			]
		);
		$this->logger->allows(
			[
				'debug' => null,
				'info'  => null,
				'error' => null,
			]
		);
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function tearDown(): void {
		Mockery::close();
	}

	/**
	 * Get the service factory.
	 *
	 * @return MockInterface
	 */
	public function getServiceFactory(): MockInterface {
		self::assertNotNull( $this->serviceFactory );
		return $this->serviceFactory;
	}

	/**
	 * Undocumented function
	 *
	 * @return LoggerInterface&MockInterface
	 */
	public function getLogger(): LoggerInterface&MockInterface {
		self::assertNotNull( $this->logger );
		return $this->logger;
	}

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
		$sut         = new RemoteThemePackageMetaProviderFactory(
			$baseUrl,
			$metaKey,
			$httpOptions,
			$this->getLogger()
		);
		$return      = $sut->create();
		$this->assertInstanceOf( ThemePackageMetaValueServiceContract::class, $return );
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
		$sut         = new RemoteThemePackageMetaProviderFactory(
			$baseUrl,
			$metaKey,
			$httpOptions,
			$this->getLogger()
		);
		$filter      = WP_Mock::onFilter(
			'wp_package_auto_updater_remote_theme_package_meta_provider_factory_v1_instance_options'
		);
		/**
		 * Fix PHPStan error
		 *
		 * @var WP_Mock\Filter_Responder $responder
		 */
		$responder = $filter->withAnyArgs();
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
		// phpcs:enable Generic.Files.LineLength.TooLong
		$sut    = new RemoteThemePackageMetaProviderFactory( $baseUrl, $metaKey, $httpOptions, $this->getLogger() );
		$filter = WP_Mock::onFilter(
			'wp_package_auto_updater_remote_theme_package_meta_provider_factory_v1_instance_options'
		);
		/**
		 * Fix PHPStan error
		 *
		 * @var WP_Mock\Filter_Responder $responder
		 */
		$responder = $filter->withAnyArgs();
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
		$logger             = $this->getLogger();
		$baseUrlUpdated     = 'https://example.com/';
		$metaKeyUpdated     = 'updated_meta_key';
		$httpOptionsUpdated = [ 'timeout' => 30 ];
		$loggerUpdated      = Mockery::mock( LoggerInterface::class );
		$loggerUpdated->allows(
			[
				'debug' => null,
				'info'  => null,
				'error' => null,
			]
		);
		$this->getServiceFactory()->shouldReceive( '__construct' )->once()->with(
			$baseUrlUpdated,
			$metaKeyUpdated,
			$httpOptionsUpdated,
			$loggerUpdated
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
		 * Fix PHPStan error
		 *
		 * @var WP_Mock\Filter_Responder $responder
		 */
		$responder = $filter->withAnyArgs();
		$responder->reply(
			$argument
		);
		$sut->create();
		$this->assertConditionsMet();
	}
}
