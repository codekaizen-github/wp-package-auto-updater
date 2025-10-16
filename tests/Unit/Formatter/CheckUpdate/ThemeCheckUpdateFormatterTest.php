<?php
/**
 * Tests
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Formatter\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Formatter\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate\ThemeCheckUpdateFormatter;
use CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckUpdate\CheckUpdateMetaObject;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaContract;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class ThemeCheckUpdateFormatterTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testValid(): void {
		$slugExpected             = 'test-theme';
		$newVersionExpected       = '3.0.1';
		$packageExpected          = 'https://github.com/codekaizen-github/wp-package-meta-provider-local';
		$urlExpected              = 'https://codekaizen.net';
		$fullSlugExpected         = 'test-theme/style.css';
		$idExpected               = 'test-theme/style.css';
		$iconsExpected            = [];
		$bannersExpected          = [];
		$bannersRtlExpected       = [];
		$requiresExpected         = '6.8.2';
		$testedExpected           = '6.8.2';
		$requiresPhpExpected      = '8.2.1';
		$localPackageMetaProvider = Mockery::mock( ThemePackageMetaContract::class );
		$localPackageMetaProvider->shouldReceive( 'getFullSlug' )->with()->andReturn( $fullSlugExpected );
		$remotePackageMetaProvider = Mockery::mock( ThemePackageMetaContract::class );
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
		$sut            = new ThemeCheckUpdateFormatter( $localPackageMetaProvider, $remotePackageMetaProvider );
		$actualResponse = $sut
			->formatForCheckUpdate( [] );
		$this->assertArrayHasKey( $fullSlugExpected, $actualResponse );
		$this->assertIsArray( $actualResponse[ $fullSlugExpected ] );
		$actualMetaArray = $actualResponse[ $fullSlugExpected ];
		$this->assertArrayHasKey( 'slug', $actualMetaArray );
		$this->assertEquals( $actualMetaArray['slug'], $slugExpected );
		$this->assertArrayHasKey( 'new_version', $actualMetaArray );
		$this->assertEquals( $actualMetaArray['new_version'], $newVersionExpected );
		$this->assertArrayHasKey( 'package', $actualMetaArray );
		$this->assertEquals( $actualMetaArray['package'], $packageExpected );
		$this->assertArrayHasKey( 'url', $actualMetaArray );
		$this->assertEquals( $actualMetaArray['url'], $urlExpected );
	}
}
