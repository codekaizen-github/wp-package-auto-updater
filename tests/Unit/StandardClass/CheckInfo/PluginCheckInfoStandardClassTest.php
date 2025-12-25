<?php
/**
 * Test file for PluginCheckInfoStandardClass.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\StandardClass\CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\StandardClass\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckInfo\PluginCheckInfoStandardClass;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PluginPackageMetaValueContract;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PluginCheckInfoStandardClass.
 */
class PluginCheckInfoStandardClassTest extends TestCase {
	/**
	 * Test that constructor sets all properties correctly.
	 *
	 * @return void
	 */
	public function testConstructorSetsProperties(): void {
		$packageMeta = Mockery::mock( PluginPackageMetaValueContract::class );
		$packageMeta->shouldReceive( 'getName' )->andReturn( 'Test Plugin' );
		$packageMeta->shouldReceive( 'getShortSlug' )->andReturn( 'test-plugin' );
		$packageMeta->shouldReceive( 'getVersion' )->andReturn( '1.0.0' );
		$packageMeta->shouldReceive( 'getAuthor' )->andReturn( 'Test Author' );
		$packageMeta->shouldReceive( 'getRequiresWordPressVersion' )->andReturn( '5.0' );
		$packageMeta->shouldReceive( 'getTested' )->andReturn( '6.0' );
		$packageMeta->shouldReceive( 'getRequiresPHPVersion' )->andReturn( '7.4' );
		$packageMeta->shouldReceive( 'getViewURL' )->andReturn( 'https://example.com' );
		$packageMeta->shouldReceive( 'getDownloadURL' )->andReturn( 'https://example.com/download' );
		$packageMeta->shouldReceive( 'getSections' )->andReturn( [] );
		$packageMeta->shouldReceive( 'getTags' )->andReturn( [] );

		$sut = new PluginCheckInfoStandardClass( $packageMeta );

		$this->assertEquals( 'Test Plugin', $sut->name );
		$this->assertEquals( 'test-plugin', $sut->slug );
		$this->assertEquals( '1.0.0', $sut->version );
		$this->assertEquals( 'Test Author', $sut->author );
		$this->assertTrue( $sut->external );
	}
}

