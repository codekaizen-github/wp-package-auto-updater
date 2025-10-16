<?php
/**
 * Tests
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Formatter\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Formatter\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate\PluginCheckUpdateFormatter;
use CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckUpdate\PluginCheckUpdateMetaObject;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaContract;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class PluginCheckUpdateFormatterTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testValid(): void {
		$slugExpected             = 'test-plugin';
		$newVersionExpected       = '3.0.1';
		$packageExpected          = 'https://github.com/codekaizen-github/wp-package-meta-provider-local';
		$urlExpected              = 'https://codekaizen.net';
		$fullSlugExpected         = 'test-plugin/test-plugin.php';
		$idExpected               = 'test-theme/style.css';
		$iconsExpected            = [];
		$bannersExpected          = [];
		$bannersRtlExpected       = [];
		$requiresExpected         = '6.8.2';
		$testedExpected           = '6.8.2';
		$requiresPhpExpected      = '8.2.1';
		$requiresPluginsExpected  = [
			'plugin-one/plugin-one.php',
			'plugin-two/plugin-two.php',
		];
		$localPackageMetaProvider = Mockery::mock( PluginPackageMetaContract::class );
		$localPackageMetaProvider->shouldReceive( 'getFullSlug' )->with()->andReturn( $fullSlugExpected );
		$remotePackageMetaProvider = Mockery::mock( PluginPackageMetaContract::class );
		$remotePackageMetaProvider->shouldReceive( 'getShortSlug' )->with()->andReturn( $slugExpected );
		$remotePackageMetaProvider->shouldReceive( 'getVersion' )->with()->andReturn( $newVersionExpected );
		$remotePackageMetaProvider->shouldReceive( 'getDownloadURL' )->with()->andReturn( $packageExpected );
		$remotePackageMetaProvider->shouldReceive( 'getViewURL' )->with()->andReturn( $urlExpected );
		$remotePackageMetaProvider->shouldReceive( 'getFullSlug' )->with()->andReturn( $idExpected );
		$remotePackageMetaProvider
			->shouldReceive( 'getRequiresWordPressVersion' )
			->with()
			->andReturn( $requiresExpected );
		$remotePackageMetaProvider->shouldReceive( 'getTested' )->with()->andReturn( $testedExpected );
		$remotePackageMetaProvider->shouldReceive( 'getRequiresPHPVersion' )->with()->andReturn( $requiresPhpExpected );
		$remotePackageMetaProvider->shouldReceive( 'getRequiresPlugins' )->with()->andReturn(
			$requiresPluginsExpected
		);
		$sut            = new PluginCheckUpdateFormatter( $localPackageMetaProvider, $remotePackageMetaProvider );
		$actualResponse = $sut
			->formatForCheckUpdate( [] );
		$this->assertArrayHasKey( $fullSlugExpected, $actualResponse );
		$this->assertIsObject( $actualResponse[ $fullSlugExpected ] );
		$actualMetaObject = $actualResponse[ $fullSlugExpected ];
		$this->assertInstanceOf( PluginCheckUpdateMetaObject::class, $actualMetaObject );
	}
}
