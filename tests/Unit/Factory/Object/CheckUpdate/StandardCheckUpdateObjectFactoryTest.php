<?php
/**
 * Test file for StandardCheckUpdateObjectFactory.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Object\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Object\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckUpdate\StandardCheckUpdateObjectFactory;
use CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckUpdate\StandardCheckUpdateStandardClass;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Test class for StandardCheckUpdateObjectFactory.
 */
class StandardCheckUpdateObjectFactoryTest extends TestCase {
	/**
	 * Test that create() returns a StandardCheckUpdateStandardClass.
	 *
	 * @return void
	 */
	public function testCreateReturnsStandardCheckUpdateStandardClass(): void {
		$packageMeta = Mockery::mock( PackageMetaValueContract::class );
		$packageMeta->shouldReceive( 'getFullSlug' )->andReturn( 'test-plugin/test-plugin.php' );
		$packageMeta->shouldReceive( 'getShortSlug' )->andReturn( 'test-plugin' );
		$packageMeta->shouldReceive( 'getVersion' )->andReturn( '1.0.0' );
		$packageMeta->shouldReceive( 'getViewURL' )->andReturn( 'https://example.com' );
		$packageMeta->shouldReceive( 'getDownloadURL' )->andReturn( 'https://example.com/download' );
		$packageMeta->shouldReceive( 'getIcons' )->andReturn( [] );
		$packageMeta->shouldReceive( 'getBanners' )->andReturn( [] );
		$packageMeta->shouldReceive( 'getBannersRTL' )->andReturn( [] );
		$packageMeta->shouldReceive( 'getTested' )->andReturn( '6.0' );
		$packageMeta->shouldReceive( 'getRequiresWordPressVersion' )->andReturn( '5.0' );
		$packageMeta->shouldReceive( 'getRequiresPHPVersion' )->andReturn( '7.4' );

		$sut    = new StandardCheckUpdateObjectFactory( $packageMeta );
		$result = $sut->create();

		$this->assertInstanceOf( StandardCheckUpdateStandardClass::class, $result );
	}
}
