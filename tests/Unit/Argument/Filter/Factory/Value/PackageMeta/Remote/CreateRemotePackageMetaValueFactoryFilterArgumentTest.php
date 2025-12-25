<?php
/**
 * Test
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Argument\Filter\Factory\Value\PackageMeta\Remote
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Argument\Filter\Factory\Value\PackageMeta\Remote;

// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Argument\Filter\Factory\Value\PackageMeta\Remote\CreateRemotePackageMetaValueFactoryFilterArgument;
use Mockery;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test CreateRemotePackageMetaValueFactoryFilterArgument class.
 */
class CreateRemotePackageMetaValueFactoryFilterArgumentTest extends TestCase {
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

		$argument = new CreateRemotePackageMetaValueFactoryFilterArgument(
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

