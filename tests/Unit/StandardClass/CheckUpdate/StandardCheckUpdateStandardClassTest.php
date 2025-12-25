<?php
/**
 * Test file for StandardCheckUpdateStandardClass.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\StandardClass\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\StandardClass\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckUpdate\StandardCheckUpdateStandardClass;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Test class for StandardCheckUpdateStandardClass.
 */
class StandardCheckUpdateStandardClassTest extends TestCase {
	/**
	 * Test that constructor sets all properties correctly.
	 *
	 * @return void
	 */
	public function testConstructorSetsProperties(): void {
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

		$sut = new StandardCheckUpdateStandardClass( $packageMeta );

		$this->assertEquals( 'test-plugin/test-plugin.php', $sut->id );
		$this->assertEquals( 'test-plugin', $sut->slug );
		$this->assertEquals( '1.0.0', $sut->new_version );
	}
}

