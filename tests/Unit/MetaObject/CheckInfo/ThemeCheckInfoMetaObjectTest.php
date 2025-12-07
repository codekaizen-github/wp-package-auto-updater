<?php
/**
 * Tests
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\MetaObject\CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\MetaObject\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckInfo\ThemeCheckInfoMetaObject;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class ThemeCheckInfoMetaObjectTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testValid(): void {
		$nameExpected         = 'Test Theme';
		$slugExpected         = 'test-plugin';
		$homepageExpected     = 'https://codekaizen.net';
		$versionExpected      = '3.0.1';
		$authorExpected       = 'Andrew Dawes';
		$requiresExpected     = '6.8.2';
		$requiresPhpExpected  = '8.2.1';
		$downloadLinkExpected = 'https://github.com/codekaizen-github/wp-package-meta-provider-local';
		$updateUriExpected    = $downloadLinkExpected;
		$testedExpected       = '6.8.2';
		$tagsExpected         = [ 'tag1', 'tag2', 'tag3' ];
		$provider             = Mockery::mock( PackageMetaValueContract::class );
		$provider->shouldReceive( 'getName' )->with()->andReturn( $nameExpected );
		$provider->shouldReceive( 'getShortSlug' )->with()->andReturn( $slugExpected );
		$provider->shouldReceive( 'getVersion' )->with()->andReturn( $versionExpected );
		$provider->shouldReceive( 'getAuthor' )->with()->andReturn( $authorExpected );
		$provider->shouldReceive( 'getRequiresWordPressVersion' )->with()->andReturn( $requiresExpected );
		$provider->shouldReceive( 'getTested' )->with()->andReturn( $testedExpected );
		$provider->shouldReceive( 'getRequiresPHPVersion' )->with()->andReturn( $requiresPhpExpected );
		$provider->shouldReceive( 'getViewURL' )->with()->andReturn( $homepageExpected );
		$provider->shouldReceive( 'getDownloadURL' )->with()->andReturn( $downloadLinkExpected );
		$provider->shouldReceive( 'getTags' )->with()->andReturn( $tagsExpected );
		$sut = new ThemeCheckInfoMetaObject( $provider );
		$this->assertObjectHasProperty( 'name', $sut );
		$this->assertEquals( $nameExpected, $sut->name );
		$this->assertObjectHasProperty( 'slug', $sut );
		$this->assertEquals( $slugExpected, $sut->slug );
		$this->assertObjectHasProperty( 'version', $sut );
		$this->assertEquals( $versionExpected, $sut->version );
		$this->assertObjectHasProperty( 'author', $sut );
		$this->assertEquals( $authorExpected, $sut->author );
		$this->assertObjectHasProperty( 'requires', $sut );
		$this->assertEquals( $requiresExpected, $sut->requires );
		$this->assertObjectHasProperty( 'tested', $sut );
		$this->assertEquals( $testedExpected, $sut->tested );
		$this->assertObjectHasProperty( 'requiresPhp', $sut );
		$this->assertEquals( $requiresPhpExpected, $sut->requiresPhp );
		$this->assertObjectHasProperty( 'homepage', $sut );
		$this->assertEquals( $homepageExpected, $sut->homepage );
		$this->assertObjectHasProperty( 'downloadLink', $sut );
		$this->assertEquals( $downloadLinkExpected, $sut->downloadLink );
		$this->assertObjectHasProperty( 'updateUri', $sut );
		$this->assertEquals( $updateUriExpected, $sut->updateUri );
		$this->assertObjectHasProperty( 'tags', $sut );
		$this->assertEquals( $tagsExpected, $sut->tags );
	}
}
