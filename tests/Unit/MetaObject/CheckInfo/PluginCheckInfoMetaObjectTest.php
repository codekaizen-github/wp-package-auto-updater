<?php
/**
 * Tests
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\StandardClass\CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\StandardClass\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckInfo\PluginCheckInfoStandardClass;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PluginPackageMetaValueContract;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class PluginCheckInfoStandardClassTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testValid(): void {
		$nameExpected         = 'Test Plugin';
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
		$sectionsExpected     = [
			'changelog' => 'changed',
			'about'     => 'this is a plugin about section',
		];
		$externalExpected     = true;
		$provider             = Mockery::mock( PluginPackageMetaValueContract::class );
		$provider->shouldReceive( 'getName' )->with()->andReturn( $nameExpected );
		$provider->shouldReceive( 'getShortSlug' )->with()->andReturn( $slugExpected );
		$provider->shouldReceive( 'getVersion' )->with()->andReturn( $versionExpected );
		$provider->shouldReceive( 'getAuthor' )->with()->andReturn( $authorExpected );
		$provider->shouldReceive( 'getRequiresWordPressVersion' )->with()->andReturn( $requiresExpected );
		$provider->shouldReceive( 'getTested' )->with()->andReturn( $testedExpected );
		$provider->shouldReceive( 'getRequiresPHPVersion' )->with()->andReturn( $requiresPhpExpected );
		$provider->shouldReceive( 'getViewURL' )->with()->andReturn( $homepageExpected );
		$provider->shouldReceive( 'getDownloadURL' )->with()->andReturn( $downloadLinkExpected );
		$provider->shouldReceive( 'getSections' )->with()->andReturn( $sectionsExpected );
		$provider->shouldReceive( 'getTags' )->with()->andReturn( $tagsExpected );
		$sut = new PluginCheckInfoStandardClass( $provider );
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
		$this->assertObjectHasProperty( 'sections', $sut );
		$this->assertEquals( $sectionsExpected, $sut->sections );
		$this->assertObjectHasProperty( 'tags', $sut );
		$this->assertEquals( $tagsExpected, $sut->tags );
		$this->assertObjectHasProperty( 'external', $sut );
		$this->assertEquals( $externalExpected, $sut->external );
	}
}
