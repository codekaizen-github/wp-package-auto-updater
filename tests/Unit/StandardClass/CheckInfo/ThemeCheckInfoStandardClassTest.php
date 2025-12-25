<?php
/**
 * Test file for ThemeCheckInfoStandardClass.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\StandardClass\CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\StandardClass\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckInfo\ThemeCheckInfoStandardClass;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Test class for ThemeCheckInfoStandardClass.
 */
class ThemeCheckInfoStandardClassTest extends TestCase {
	/**
	 * Test that constructor sets all properties correctly.
	 *
	 * @return void
	 */
	public function testConstructorSetsProperties(): void {
		$packageMeta = Mockery::mock( PackageMetaValueContract::class );
		$packageMeta->shouldReceive( 'getName' )->andReturn( 'Test Theme' );
		$packageMeta->shouldReceive( 'getShortSlug' )->andReturn( 'test-theme' );
		$packageMeta->shouldReceive( 'getVersion' )->andReturn( '1.0.0' );
		$packageMeta->shouldReceive( 'getAuthor' )->andReturn( 'Test Author' );
		$packageMeta->shouldReceive( 'getRequiresWordPressVersion' )->andReturn( '5.0' );
		$packageMeta->shouldReceive( 'getTested' )->andReturn( '6.0' );
		$packageMeta->shouldReceive( 'getRequiresPHPVersion' )->andReturn( '7.4' );
		$packageMeta->shouldReceive( 'getViewURL' )->andReturn( 'https://example.com' );
		$packageMeta->shouldReceive( 'getDownloadURL' )->andReturn( 'https://example.com/download' );
		$packageMeta->shouldReceive( 'getTags' )->andReturn( [] );

		$sut = new ThemeCheckInfoStandardClass( $packageMeta );

		$this->assertEquals( 'Test Theme', $sut->name );
		$this->assertEquals( 'test-theme', $sut->slug );
		$this->assertEquals( '1.0.0', $sut->version );
		$this->assertEquals( 'Test Author', $sut->author );
	}
}

