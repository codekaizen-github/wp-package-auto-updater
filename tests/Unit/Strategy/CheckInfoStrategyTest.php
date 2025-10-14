<?php
/**
 * Test
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Strategy
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Strategy;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckInfo\CheckInfoFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\Strategy\CheckInfoStrategy;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaContract;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use stdClass;

/**
 * Undocumented class
 */
class CheckInfoStrategyTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testSlugNotSetValid(): void {
		$localPackageMetaProvider = Mockery::mock( PackageMetaContract::class );
		$localPackageMetaProvider->shouldReceive( 'getShortSlug' )->with()->andReturn( 'test-plugin' );
		$formatter = Mockery::mock( CheckInfoFormatterContract::class );
		$logger    = Mockery::mock( LoggerInterface::class );
		$sut       = new CheckInfoStrategy( $localPackageMetaProvider, $formatter, $logger );
		$arg       = new stdClass();
		$arg->slug = null;
		$actual    = $sut->checkInfo( false, '', $arg );
		$this->assertFalse( $actual );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testSlugsDoNotMatchValid(): void {
		$localPackageMetaProvider = Mockery::mock( PackageMetaContract::class );
		$localPackageMetaProvider->shouldReceive( 'getShortSlug' )->with()->andReturn( 'test-plugin' );
		$formatter = Mockery::mock( CheckInfoFormatterContract::class );
		$logger    = Mockery::mock( LoggerInterface::class );
		$sut       = new CheckInfoStrategy( $localPackageMetaProvider, $formatter, $logger );
		$arg       = new stdClass();
		$arg->slug = 'other-plugin';
		$actual    = $sut->checkInfo( false, '', $arg );
		$this->assertFalse( $actual );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testPackageCheckInfoValid(): void {
		$localPackageMetaProvider = Mockery::mock( PackageMetaContract::class );
		$localPackageMetaProvider->shouldReceive( 'getShortSlug' )->with()->andReturn( 'test-plugin' );
		$formatter = Mockery::mock( CheckInfoFormatterContract::class );
		$expected  = new stdClass();
		$formatter->shouldReceive( 'formatForCheckInfo' )->with()->andReturn( new $expected() );
		$logger = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$sut       = new CheckInfoStrategy( $localPackageMetaProvider, $formatter, $logger );
		$arg       = new stdClass();
		$arg->slug = 'test-plugin';
		$actual    = $sut->checkInfo( false, '', $arg );
		$this->assertEquals( $expected, $actual );
	}
}
