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

		$sut = new CreateRemotePackageMetaValueFactoryFilterArgument(
			$baseURL,
			$metaKey,
			$httpOptions,
			$logger
		);
		$this->assertSame( $baseURL, $sut->getBaseURL() );
		$this->assertSame( $metaKey, $sut->getMetaKey() );
		$this->assertSame( $httpOptions, $sut->getHttpOptions() );
		$this->assertSame( $logger, $sut->getLogger() );
		$sut->setBaseURL( 'https://changed.com' );
		$this->assertSame( 'https://changed.com', $sut->getBaseURL() );

		$sut->setMetaKey( 'org.changed.wp-package-metadata' );
		$this->assertSame( 'org.changed.wp-package-metadata', $sut->getMetaKey() );
		$newHttpOptions = [ 'timeout' => 60 ];
		$sut->setHttpOptions( $newHttpOptions );
		$this->assertSame( $newHttpOptions, $sut->getHttpOptions() );
		$newLogger = Mockery::mock( LoggerInterface::class );
		$sut->setLogger( $newLogger );
		$this->assertSame( $newLogger, $sut->getLogger() );
	}
}
