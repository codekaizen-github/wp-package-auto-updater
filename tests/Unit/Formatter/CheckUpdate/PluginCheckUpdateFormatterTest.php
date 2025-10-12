<?php
/**
 * Tests
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Formatter\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Formatter\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate\PluginCheckUpdateFormatter;
use CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckUpdate\CheckUpdateMetaObject;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaContract;
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
		$localPackageMetaProvider = Mockery::mock( PackageMetaContract::class );
		$localPackageMetaProvider->shouldReceive( 'getFullSlug' )->with()->andReturn( $fullSlugExpected );
		$remotePackageMetaProvider = Mockery::mock( PackageMetaContract::class );
		$remotePackageMetaProvider->shouldReceive( 'getShortSlug' )->with()->andReturn( $slugExpected );
		$remotePackageMetaProvider->shouldReceive( 'getVersion' )->with()->andReturn( $newVersionExpected );
		$remotePackageMetaProvider->shouldReceive( 'getDownloadURL' )->with()->andReturn( $packageExpected );
		$remotePackageMetaProvider->shouldReceive( 'getViewURL' )->with()->andReturn( $urlExpected );
		$sut            = new PluginCheckUpdateFormatter();
		$actualResponse = $sut
			->formatForCheckUpdate( [], $localPackageMetaProvider, $remotePackageMetaProvider );
		$this->assertArrayHasKey( $fullSlugExpected, $actualResponse );
		$this->assertIsObject( $actualResponse[ $fullSlugExpected ] );
		$actualMetaObject = $actualResponse[ $fullSlugExpected ];
		$this->assertInstanceOf( CheckUpdateMetaObject::class, $actualMetaObject );
	}
}
