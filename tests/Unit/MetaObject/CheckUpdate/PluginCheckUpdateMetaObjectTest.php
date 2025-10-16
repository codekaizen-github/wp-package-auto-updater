<?php
/**
 * Tests
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\MetaObject\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\MetaObject\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckUpdate\PluginCheckUpdateMetaObject;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaContract;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class PluginCheckUpdateMetaObjectTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testValid(): void {
		$slugExpected            = 'test-plugin';
		$newVersionExpected      = '3.0.1';
		$packageExpected         = 'https://github.com/codekaizen-github/wp-package-meta-provider-local';
		$urlExpected             = 'https://codekaizen.net';
		$idExpected              = 'test-plugin/test-plugin.php';
		$iconsExpected           = [];
		$bannersExpected         = [];
		$bannersRtlExpected      = [];
		$requiresExpected        = '6.8.2';
		$testedExpected          = '6.8.2';
		$requiresPhpExpected     = '8.2.1';
		$requiresPluginsExpected = [
			'plugin-one/plugin-one.php',
			'plugin-two/plugin-two.php',
		];
		$provider                = Mockery::mock( PluginPackageMetaContract::class );
		$provider->shouldReceive( 'getShortSlug' )->with()->andReturn( $slugExpected );
		$provider->shouldReceive( 'getVersion' )->with()->andReturn( $newVersionExpected );
		$provider->shouldReceive( 'getDownloadURL' )->with()->andReturn( $packageExpected );
		$provider->shouldReceive( 'getViewURL' )->with()->andReturn( $urlExpected );
		$provider->shouldReceive( 'getFullSlug' )->with()->andReturn( $idExpected );
		$provider->shouldReceive( 'getRequiresWordPressVersion' )->with()->andReturn( $requiresExpected );
		$provider->shouldReceive( 'getTested' )->with()->andReturn( $testedExpected );
		$provider->shouldReceive( 'getRequiresPHPVersion' )->with()->andReturn( $requiresPhpExpected );
		$provider->shouldReceive( 'getRequiresPlugins' )->with()->andReturn(
			$requiresPluginsExpected
		);
		$sut = new PluginCheckUpdateMetaObject( $provider );
		$this->assertObjectHasProperty( 'slug', $sut );
		$this->assertEquals( $slugExpected, $sut->slug );
		$this->assertObjectHasProperty( 'new_version', $sut );
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->assertEquals( $newVersionExpected, $sut->new_version );
		$this->assertObjectHasProperty( 'package', $sut );
		$this->assertEquals( $packageExpected, $sut->package );
		$this->assertObjectHasProperty( 'url', $sut );
		$this->assertEquals( $urlExpected, $sut->url );
		$this->assertObjectHasProperty( 'id', $sut );
		$this->assertEquals( $idExpected, $sut->id );
		$this->assertObjectHasProperty( 'icons', $sut );
		$this->assertEquals( $iconsExpected, $sut->icons );
		$this->assertObjectHasProperty( 'banners', $sut );
		$this->assertEquals( $bannersExpected, $sut->banners );
		$this->assertObjectHasProperty( 'banners_rtl', $sut );
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->assertEquals( $bannersRtlExpected, $sut->banners_rtl );
		$this->assertObjectHasProperty( 'requires', $sut );
		$this->assertEquals( $requiresExpected, $sut->requires );
		$this->assertObjectHasProperty( 'tested', $sut );
		$this->assertEquals( $testedExpected, $sut->tested );
		$this->assertObjectHasProperty( 'requires_php', $sut );
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->assertEquals( $requiresPhpExpected, $sut->requires_php );
		$this->assertObjectHasProperty( 'requires_plugins', $sut );
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->assertEquals( $requiresPluginsExpected, $sut->requires_plugins );
	}
}
