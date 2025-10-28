<?php
/**
 * Test
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Argument\Filter\Factory\Provider\PackageMeta\Plugin\Remote
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Argument\Filter\Factory\Provider\PackageMeta\Plugin\Remote;

// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Argument\Filter\Factory\Provider\PackageMeta\Plugin\Remote\CreateRemotePluginPackageMetaProviderFactoryFilterArgument;
use Mockery;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test CreateRemotePluginPackageMetaProviderFactoryFilterArgument class.
 */
class CreateRemotePluginPackageMetaProviderFactoryFilterArgumentTest extends TestCase {
	/**
	 * Test constructor and properties.
	 *
	 * @return void
	 */
	public function testConstructorAndPropertiesValid(): void {
		$baseURL     = 'https://example.com';
		$metaKey     = 'org.example.wp-package-metadata';
		$httpOptions = [
			'timeout' => 30,
		];
		$logger      = Mockery::mock( LoggerInterface::class );

		$argument = new CreateRemotePluginPackageMetaProviderFactoryFilterArgument(
			$baseURL,
			$metaKey,
			$httpOptions,
			$logger
		);
		$this->assertSame( $baseURL, $argument->getBaseURL() );
		$this->assertSame( $metaKey, $argument->getMetaKey() );
		$this->assertSame( $httpOptions, $argument->getHttpOptions() );
		$this->assertSame( $logger, $argument->getLogger() );
		$argument->setBaseURL( 'https://changed.com' );
		$this->assertSame( 'https://changed.com', $argument->getBaseURL() );

		$argument->setMetaKey( 'org.changed.wp-package-metadata' );
		$this->assertSame( 'org.changed.wp-package-metadata', $argument->getMetaKey() );
		$newHttpOptions = [ 'timeout' => 60 ];
		$argument->setHttpOptions( $newHttpOptions );
		$this->assertSame( $newHttpOptions, $argument->getHttpOptions() );
		$newLogger = Mockery::mock( LoggerInterface::class );
		$argument->setLogger( $newLogger );
		$this->assertSame( $newLogger, $argument->getLogger() );
	}
}
