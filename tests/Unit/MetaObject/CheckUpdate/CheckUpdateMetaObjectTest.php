<?php
/**
 * Tests
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\MetaObject\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\MetaObject\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckUpdate\CheckUpdateMetaObject;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaContract;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class CheckUpdateMetaObjectTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testValid(): void {
		$slugExpected       = 'test-plugin';
		$newVersionExpected = '3.0.1';
		$packageExpected    = 'https://github.com/codekaizen-github/wp-package-meta-provider-local';
		$urlExpected        = 'https://codekaizen.net';
		$provider           = Mockery::mock( PackageMetaContract::class );
		$provider->shouldReceive( 'getShortSlug' )->with()->andReturn( $slugExpected );
		$provider->shouldReceive( 'getVersion' )->with()->andReturn( $newVersionExpected );
		$provider->shouldReceive( 'getDownloadURL' )->with()->andReturn( $packageExpected );
		$provider->shouldReceive( 'getViewURL' )->with()->andReturn( $urlExpected );
		$sut = new CheckUpdateMetaObject( $provider );
		$this->assertObjectHasProperty( 'slug', $sut );
		$this->assertEquals( $slugExpected, $sut->slug );
		$this->assertObjectHasProperty( 'newVersion', $sut );
		$this->assertEquals( $newVersionExpected, $sut->newVersion );
		$this->assertObjectHasProperty( 'package', $sut );
		$this->assertEquals( $packageExpected, $sut->package );
		$this->assertObjectHasProperty( 'url', $sut );
		$this->assertEquals( $urlExpected, $sut->url );
	}
}
