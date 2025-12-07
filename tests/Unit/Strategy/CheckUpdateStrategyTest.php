<?php
/**
 * Test
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Strategy
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Strategy;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckUpdate\CheckUpdateFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\Strategy\CheckUpdateStrategy;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use stdClass;

/**
 * Tests for the CheckUpdateStrategy class.
 */
class CheckUpdateStrategyTest extends TestCase {
	/**
	 * Local package meta provider mock.
	 *
	 * @var PackageMetaValueContract|Mockery\MockInterface
	 */
	private $localPackageMetaProvider;

	/**
	 * Remote package meta provider mock.
	 *
	 * @var PackageMetaValueContract|Mockery\MockInterface
	 */
	private $remotePackageMetaProvider;

	/**
	 * Formatter mock.
	 *
	 * @var CheckUpdateFormatterContract|Mockery\MockInterface
	 */
	private $formatter;

	/**
	 * Logger mock.
	 *
	 * @var LoggerInterface|Mockery\MockInterface
	 */
	private $logger;

	/**
	 * System under test.
	 *
	 * @var CheckUpdateStrategy
	 */
	private $sut;

	/**
	 * Set up test environment before each test.
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->localPackageMetaProvider  = Mockery::mock( PackageMetaValueContract::class );
		$this->remotePackageMetaProvider = Mockery::mock( PackageMetaValueContract::class );
		$this->formatter                 = Mockery::mock( CheckUpdateFormatterContract::class );
		$this->logger                    = Mockery::mock( LoggerInterface::class );

		$this->logger->allows( 'debug' )->byDefault();
		$this->logger->allows( 'warning' )->byDefault();
		$this->logger->allows( 'error' )->byDefault();

		$this->sut = new CheckUpdateStrategy(
			$this->localPackageMetaProvider,
			$this->remotePackageMetaProvider,
			$this->formatter,
			$this->logger
		);
	}

	/**
	 * Tear down test environment after each test.
	 *
	 * @return void
	 */
	protected function tearDown(): void {
		Mockery::close();
		parent::tearDown();
	}

	/**
	 * Tests the checkUpdate method when local version is less than remote version.
	 *
	 * @return void
	 */
	public function testCheckUpdateWhenVersionLessThan(): void {
		// Set up transient object.
		$transient           = new stdClass();
		$transient->checked  = [ 'some-plugin/plugin.php' => '1.0.0' ];
		$transient->response = [];

		// Set up expected versions.
		$this->localPackageMetaProvider->shouldReceive( 'getVersion' )->once()->andReturn( '1.0.0' );
		$this->localPackageMetaProvider->shouldReceive( 'getFullSlug' )->once()->andReturn( 'some-plugin/plugin.php' );
		$this->remotePackageMetaProvider->shouldReceive( 'getVersion' )->once()->andReturn( '1.1.0' );

		// Mock formatter to update response property.
		$updatedResponse = [ 'some-plugin/plugin.php' => (object) [ 'new_version' => '1.1.0' ] ];
		$this->formatter->shouldReceive( 'formatForCheckUpdate' )
			->once()
			->with( [] )
			->andReturn( $updatedResponse );

		// Call the method under test.
		$result = $this->sut->checkUpdate( $transient );

		// Verify result.
		$this->assertSame( $updatedResponse, $result->response );
		$this->assertObjectHasProperty( 'response', $result );
	}

	/**
	 * Tests the checkUpdate method when local version is greater than remote version.
	 *
	 * @return void
	 */
	public function testCheckUpdateWhenVersionGreaterThan(): void {
		// Set up transient object.
		$transient           = new stdClass();
		$transient->checked  = [ 'some-plugin/plugin.php' => '2.0.0' ];
		$transient->noUpdate = [];

		// Set up expected versions.
		$this->localPackageMetaProvider->shouldReceive( 'getVersion' )->once()->andReturn( '2.0.0' );
		$this->localPackageMetaProvider->shouldReceive( 'getFullSlug' )->once()->andReturn( 'some-plugin/plugin.php' );
		$this->remotePackageMetaProvider->shouldReceive( 'getVersion' )->once()->andReturn( '1.9.0' );

		// Mock formatter to update noUpdate property.
		$updatedNoUpdate = [ 'some-plugin/plugin.php' => (object) [ 'new_version' => '1.9.0' ] ];
		$this->formatter->shouldReceive( 'formatForCheckUpdate' )
			->once()
			->with( [] )
			->andReturn( $updatedNoUpdate );

		// Call the method under test.
		$result = $this->sut->checkUpdate( $transient );

		// Verify result.
		$this->assertSame( $updatedNoUpdate, $result->noUpdate );
		$this->assertObjectHasProperty( 'noUpdate', $result );
	}

	/**
	 * Tests the checkUpdate method when local version equals remote version.
	 *
	 * @return void
	 */
	public function testCheckUpdateWhenVersionEqual(): void {
		// Set up transient object.
		$transient           = new stdClass();
		$transient->checked  = [ 'some-plugin/plugin.php' => '1.0.0' ];
		$transient->noUpdate = [];

		// Set up expected versions.
		$this->localPackageMetaProvider->shouldReceive( 'getVersion' )->once()->andReturn( '1.0.0' );
		$this->localPackageMetaProvider->shouldReceive( 'getFullSlug' )->once()->andReturn( 'some-plugin/plugin.php' );
		$this->remotePackageMetaProvider->shouldReceive( 'getVersion' )->once()->andReturn( '1.0.0' );

		// Mock formatter to update noUpdate property.
		$updatedNoUpdate = [ 'some-plugin/plugin.php' => (object) [ 'new_version' => '1.0.0' ] ];
		$this->formatter->shouldReceive( 'formatForCheckUpdate' )
			->once()
			->with( [] )
			->andReturn( $updatedNoUpdate );

		// Call the method under test.
		$result = $this->sut->checkUpdate( $transient );

		// Verify result.
		$this->assertSame( $updatedNoUpdate, $result->noUpdate );
		$this->assertObjectHasProperty( 'noUpdate', $result );
	}

	/**
	 * Tests the checkUpdate method when transient checked property is empty.
	 *
	 * @return void
	 */
	public function testCheckUpdateWhenTransientCheckedIsEmpty(): void {
		// Set up transient object with empty checked property.
		$transient          = new stdClass();
		$transient->checked = [];

		// Set up logger to expect debug message.
		$this->localPackageMetaProvider->shouldReceive( 'getFullSlug' )->once()->andReturn( 'some-plugin/plugin.php' );
		$this->logger->shouldReceive( 'debug' )->with( 'No checked packages in transient, skipping' )->once();

		// Call the method under test.
		$result = $this->sut->checkUpdate( $transient );

		// Verify result is unchanged.
		$this->assertSame( $transient, $result );
	}

	/**
	 * Tests the checkUpdate method when transient checked property is missing.
	 *
	 * @return void
	 */
	public function testCheckUpdateWhenTransientCheckedIsMissing(): void {
		// Set up transient object without checked property.
		$transient = new stdClass();

		// Set up logger to expect debug message.
		$this->localPackageMetaProvider->shouldReceive( 'getFullSlug' )->once()->andReturn( 'some-plugin/plugin.php' );
		$this->logger->shouldReceive( 'debug' )->with( 'No checked packages in transient, skipping' )->once();

		// Call the method under test.
		$result = $this->sut->checkUpdate( $transient );

		// Verify result is unchanged.
		$this->assertSame( $transient, $result );
	}

	/**
	 * Tests the checkUpdate method when response property doesn't exist.
	 *
	 * @return void
	 */
	public function testCheckUpdateWhenResponsePropertyDoesNotExist(): void {
		// Set up transient object without response property.
		$transient          = new stdClass();
		$transient->checked = [ 'some-plugin/plugin.php' => '1.0.0' ];
		// No response property set.

		// Set up expected versions.
		$this->localPackageMetaProvider->shouldReceive( 'getVersion' )->once()->andReturn( '1.0.0' );
		$this->localPackageMetaProvider->shouldReceive( 'getFullSlug' )->once()->andReturn( 'some-plugin/plugin.php' );
		$this->remotePackageMetaProvider->shouldReceive( 'getVersion' )->once()->andReturn( '2.0.0' );

		// Mock formatter to create response property.
		$updatedResponse = [ 'some-plugin/plugin.php' => (object) [ 'new_version' => '2.0.0' ] ];
		$this->formatter->shouldReceive( 'formatForCheckUpdate' )
			->once()
			->with( [] )
			->andReturn( $updatedResponse );

		// Call the method under test.
		$result = $this->sut->checkUpdate( $transient );

		// Verify result has response property created.
		$this->assertObjectHasProperty( 'response', $result );
		$this->assertSame( $updatedResponse, $result->response );
	}

	/**
	 * Tests the checkUpdate method when noUpdate property doesn't exist.
	 *
	 * @return void
	 */
	public function testCheckUpdateWhenNoUpdatePropertyDoesNotExist(): void {
		// Set up transient object without noUpdate property.
		$transient          = new stdClass();
		$transient->checked = [ 'some-plugin/plugin.php' => '2.0.0' ];
		// No noUpdate property set.

		// Set up expected versions.
		$this->localPackageMetaProvider->shouldReceive( 'getVersion' )->once()->andReturn( '2.0.0' );
		$this->localPackageMetaProvider->shouldReceive( 'getFullSlug' )->once()->andReturn( 'some-plugin/plugin.php' );
		$this->remotePackageMetaProvider->shouldReceive( 'getVersion' )->once()->andReturn( '1.0.0' );

		// Mock formatter to create noUpdate property.
		$updatedNoUpdate = [ 'some-plugin/plugin.php' => (object) [ 'new_version' => '1.0.0' ] ];
		$this->formatter->shouldReceive( 'formatForCheckUpdate' )
			->once()
			->with( [] )
			->andReturn( $updatedNoUpdate );

		// Call the method under test.
		$result = $this->sut->checkUpdate( $transient );

		// Verify result has noUpdate property created.
		$this->assertObjectHasProperty( 'noUpdate', $result );
		$this->assertSame( $updatedNoUpdate, $result->noUpdate );
	}

	/**
	 * Tests the checkUpdate method when response property is not an array.
	 *
	 * @return void
	 */
	public function testCheckUpdateWhenResponsePropertyIsNotArray(): void {
		// Set up transient object with non-array response property.
		$transient           = new stdClass();
		$transient->checked  = [ 'some-plugin/plugin.php' => '1.0.0' ];
		$transient->response = 'not-an-array';

		// Set up expected versions.
		$this->localPackageMetaProvider->shouldReceive( 'getVersion' )->once()->andReturn( '1.0.0' );
		$this->localPackageMetaProvider->shouldReceive( 'getFullSlug' )->once()->andReturn( 'some-plugin/plugin.php' );
		$this->remotePackageMetaProvider->shouldReceive( 'getVersion' )->once()->andReturn( '2.0.0' );

		// Mock formatter to update response property.
		$updatedResponse = [ 'some-plugin/plugin.php' => (object) [ 'new_version' => '2.0.0' ] ];
		$this->formatter->shouldReceive( 'formatForCheckUpdate' )
			->once()
			->with( [] )
			->andReturn( $updatedResponse );

		// Call the method under test.
		$result = $this->sut->checkUpdate( $transient );

		// Verify result response is now an array.
		$this->assertIsArray( $result->response );
		$this->assertSame( $updatedResponse, $result->response );
	}

	/**
	 * Tests the checkUpdate method when noUpdate property is not an array.
	 *
	 * @return void
	 */
	public function testCheckUpdateWhenNoUpdatePropertyIsNotArray(): void {
		// Set up transient object with non-array noUpdate property.
		$transient           = new stdClass();
		$transient->checked  = [ 'some-plugin/plugin.php' => '2.0.0' ];
		$transient->noUpdate = 'not-an-array';

		// Set up expected versions.
		$this->localPackageMetaProvider->shouldReceive( 'getVersion' )->once()->andReturn( '2.0.0' );
		$this->localPackageMetaProvider->shouldReceive( 'getFullSlug' )->once()->andReturn( 'some-plugin/plugin.php' );
		$this->remotePackageMetaProvider->shouldReceive( 'getVersion' )->once()->andReturn( '1.0.0' );

		// Mock formatter to update noUpdate property.
		$updatedNoUpdate = [ 'some-plugin/plugin.php' => (object) [ 'new_version' => '1.0.0' ] ];
		$this->formatter->shouldReceive( 'formatForCheckUpdate' )
			->once()
			->with( [] )
			->andReturn( $updatedNoUpdate );

		// Call the method under test.
		$result = $this->sut->checkUpdate( $transient );

		// Verify result noUpdate is now an array.
		$this->assertIsArray( $result->noUpdate );
		$this->assertSame( $updatedNoUpdate, $result->noUpdate );
	}

	/**
	 * Tests the checkUpdate method when local version is null.
	 *
	 * @return void
	 */
	public function testCheckUpdateWhenLocalVersionIsNull(): void {
		// Set up transient object.
		$transient          = new stdClass();
		$transient->checked = [ 'some-plugin/plugin.php' => '1.0.0' ];

		// Set up versions with null local version.
		$this->localPackageMetaProvider->shouldReceive( 'getVersion' )->once()->andReturn( null );
		$this->localPackageMetaProvider->shouldReceive( 'getFullSlug' )->once()->andReturn( 'some-plugin/plugin.php' );
		$this->remotePackageMetaProvider->shouldReceive( 'getVersion' )->once()->andReturn( '1.0.0' );

		// Set up warning log before SUT construction.
			$this->logger->shouldReceive( 'warning' )->withAnyArgs()->once();
		$this->sut = new CheckUpdateStrategy(
			$this->localPackageMetaProvider,
			$this->remotePackageMetaProvider,
			$this->formatter,
			$this->logger
		);
		// Set up warning log.
		$this->logger->shouldReceive( 'warning' );

		// Call the method under test.
		$result = $this->sut->checkUpdate( $transient );

		// Verify result is unchanged.
		$this->assertSame( $transient, $result );
	}

	/**
	 * Tests the checkUpdate method when remote version is null.
	 *
	 * @return void
	 */
	public function testCheckUpdateWhenRemoteVersionIsNull(): void {
		// Set up transient object.
		$transient          = new stdClass();
		$transient->checked = [ 'some-plugin/plugin.php' => '1.0.0' ];

		// Set up versions with null remote version.
		$this->localPackageMetaProvider->shouldReceive( 'getVersion' )->once()->andReturn( '1.0.0' );
		$this->localPackageMetaProvider->shouldReceive( 'getFullSlug' )->once()->andReturn( 'some-plugin/plugin.php' );
		$this->remotePackageMetaProvider->shouldReceive( 'getVersion' )->once()->andReturn( null );

		// Set up warning log before SUT construction.
			$this->logger->shouldReceive( 'warning' )->withAnyArgs()->once();
		$this->sut = new CheckUpdateStrategy(
			$this->localPackageMetaProvider,
			$this->remotePackageMetaProvider,
			$this->formatter,
			$this->logger
		);
		// Set up warning log.
		$this->logger->shouldReceive( 'warning' );

		// Call the method under test.
		$result = $this->sut->checkUpdate( $transient );

		// Verify result is unchanged.
		$this->assertSame( $transient, $result );
	}

	/**
	 * Tests the checkUpdate method when exception is thrown during version check.
	 *
	 * @return void
	 */
	public function testCheckUpdateWhenExceptionIsThrown(): void {
		// Set up transient object.
		$transient          = new stdClass();
		$transient->checked = [ 'some-plugin/plugin.php' => '1.0.0' ];

		// Set up exception scenario.
		$exceptionMessage = 'Failed to get remote version';
		$this->localPackageMetaProvider->shouldReceive( 'getFullSlug' )->once()->andReturn( 'some-plugin/plugin.php' );
		$this->localPackageMetaProvider->shouldReceive( 'getVersion' )->once()->andReturn( '1.0.0' );
		$this->remotePackageMetaProvider->shouldReceive( 'getVersion' )->once()
			->andThrow( new Exception( $exceptionMessage ) );

		// Set up error log before SUT construction.
			$this->logger->shouldReceive( 'error' )->withAnyArgs()->once();
		$this->sut = new CheckUpdateStrategy(
			$this->localPackageMetaProvider,
			$this->remotePackageMetaProvider,
			$this->formatter,
			$this->logger
		);
		// Set up error log.
		$errorMessage = 'Unable to get remote package version: ' . $exceptionMessage;
		$this->logger->shouldReceive( 'error' );

		// Call the method under test.
		$result = $this->sut->checkUpdate( $transient );

		// Verify result is unchanged.
		$this->assertSame( $transient, $result );
	}
}
