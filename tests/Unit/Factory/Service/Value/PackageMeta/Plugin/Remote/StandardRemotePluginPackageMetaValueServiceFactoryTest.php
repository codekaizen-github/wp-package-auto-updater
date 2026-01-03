<?php
/**
 * Test
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Service\Value\PackageMeta\Plugin\Remote
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Service\Value\PackageMeta\Plugin\Remote;

// phpcs:disable Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Contract\Argument\Filter\Factory\Value\PackageMeta\Remote\CreateRemotePackageMetaValueFactoryFilterArgumentContract;
use CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\Plugin\Remote\StandardRemotePluginPackageMetaValueServiceFactory;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PluginPackageMetaValueServiceContract;
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
class StandardRemotePluginPackageMetaValueServiceFactoryTest extends TestCase {

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
			'overload:CodeKaizen\WPPackageMetaProviderORASHub\Factory\Service\Value\PackageMeta\Plugin\StandardPluginPackageMetaValueServiceFactory'
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
		$this->logger = Mockery::mock( LoggerInterface::class );
		$this->getServiceFactory()->shouldReceive( 'create' )->andReturnUsing(
			function () {
				return Mockery::mock( PluginPackageMetaValueServiceContract::class );
			}
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
		$sut         = new StandardRemotePluginPackageMetaValueServiceFactory(
			$baseUrl,
			$metaKey,
			$httpOptions,
			$this->getLogger()
		);
		$return      = $sut->create();
		$this->assertInstanceOf( PluginPackageMetaValueServiceContract::class, $return );
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
		$sut         = new StandardRemotePluginPackageMetaValueServiceFactory(
			$baseUrl,
			$metaKey,
			$httpOptions,
			$this->getLogger()
		);
		$filter      = WP_Mock::onFilter(
			'wp_package_auto_updater_remote_plugin_package_meta_provider_factory_v1_instance_options'
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
		$sut    = new StandardRemotePluginPackageMetaValueServiceFactory(
			$baseUrl,
			$metaKey,
			$httpOptions,
			$this->getLogger()
		);
		$filter = WP_Mock::onFilter(
			'wp_package_auto_updater_remote_plugin_package_meta_provider_factory_v1_instance_options'
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
		$argument = Mockery::mock( CreateRemotePackageMetaValueFactoryFilterArgumentContract::class );
		$argument->shouldReceive( 'getBaseURL' )->andReturn( $baseUrlUpdated );
		$argument->shouldReceive( 'getMetaKey' )->andReturn( $metaKeyUpdated );
		$argument->shouldReceive( 'getHttpOptions' )->andReturn( $httpOptionsUpdated );
		$argument->shouldReceive( 'getLogger' )->andReturn( $loggerUpdated );
		// phpcs:enable Generic.Files.LineLength.TooLong
		$sut    = new StandardRemotePluginPackageMetaValueServiceFactory( $baseUrl, $metaKey, $httpOptions, $logger );
		$filter = WP_Mock::onFilter(
			'wp_package_auto_updater_remote_plugin_package_meta_provider_factory_v1_instance_options'
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

	/**
	 * Test that create() does not cache the provider and returns a new instance on each call.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @link https://stackoverflow.com/a/72639610/13461208
	 * @return void
	 */
	public function testCreateDoesNotCacheProvider(): void {
		$baseUrl     = '';
		$metaKey     = '';
		$httpOptions = [];
		$logger      = $this->getLogger();
		$argument    = Mockery::mock( CreateRemotePackageMetaValueFactoryFilterArgumentContract::class );
		$argument->shouldReceive( 'getBaseURL' )->andReturn( $baseUrl );
		$argument->shouldReceive( 'getMetaKey' )->andReturn( $metaKey );
		$argument->shouldReceive( 'getHttpOptions' )->andReturn( $httpOptions );
		$argument->shouldReceive( 'getLogger' )->andReturn( $logger );
		// phpcs:enable Generic.Files.LineLength.TooLong
		$filter = WP_Mock::onFilter(
			'wp_package_auto_updater_remote_plugin_package_meta_provider_factory_v1_instance_options'
		);
		/**
		 * Fix PHPStan error
		 *
		 * @var WP_Mock\Filter_Responder $responder
		 */
		$responder = $filter->withAnyArgs();
		$responder->reply( $argument );
		$sut     = new StandardRemotePluginPackageMetaValueServiceFactory( $baseUrl, $metaKey, $httpOptions, $logger );
		$return1 = $sut->create();
		$return2 = $sut->create();
		$this->assertNotSame( $return1, $return2, 'Standard factory should return different instances on each call.' );
	}
}
