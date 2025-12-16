<?php
/**
 * Tests
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Formatter\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Formatter\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Factory\StandardClass\CheckUpdate\StandardCheckUpdateStandardClassFactory;
use CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckUpdate\StandardCheckUpdateStandardClass;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class StandardCheckUpdateFormatterTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testValid(): void {
		$slugExpected                     = 'test-plugin';
		$newVersionExpected               = '3.0.1';
		$packageExpected                  = 'https://github.com/codekaizen-github/wp-package-meta-provider-local';
		$urlExpected                      = 'https://codekaizen.net';
		$fullSlugExpected                 = 'test-plugin/test-plugin.php';
		$idExpected                       = 'test-theme/style.css';
		$iconsExpected                    = [
			'1x'  => 'https://example.com/icon-128x128.png',
			'2x'  => 'https://example.com/icon-256x256.png',
			'svg' => 'https://example.com/icon.svg',
		];
		$bannersExpected                  = [
			'1x' => 'https://example.com/banner-772x250.png',
			'2x' => 'https://example.com/banner-1544x500.png',
		];
		$bannersRtlExpected               = [
			'1x' => 'https://example.com/banner-rtl-772x250.png',
			'2x' => 'https://example.com/banner-rtl-1544x500.png',
		];
		$requiresExpected                 = '6.8.2';
		$testedExpected                   = '6.8.2';
		$requiresPhpExpected              = '8.2.1';
		$requiresExpected                 = [
			'plugin-one/plugin-one.php',
			'plugin-two/plugin-two.php',
		];
		$requiresWordPressVersionExpected = '6.8.2';
		$localPackageMetaProvider         = Mockery::mock( PackageMetaValueContract::class );
		$localPackageMetaProvider->shouldReceive( 'getFullSlug' )->with()->andReturn( $fullSlugExpected );
		$remotePackageMetaProvider = Mockery::mock( PackageMetaValueContract::class );
		$remotePackageMetaProvider->shouldReceive( 'getShortSlug' )->with()->andReturn( $slugExpected );
		$remotePackageMetaProvider->shouldReceive( 'getVersion' )->with()->andReturn( $newVersionExpected );
		$remotePackageMetaProvider->shouldReceive( 'getDownloadURL' )->with()->andReturn( $packageExpected );
		$remotePackageMetaProvider->shouldReceive( 'getViewURL' )->with()->andReturn( $urlExpected );
		$remotePackageMetaProvider->shouldReceive( 'getFullSlug' )->with()->andReturn( $idExpected );
		$remotePackageMetaProvider->shouldReceive( 'getIcons' )->with()->andReturn( $iconsExpected );
		$remotePackageMetaProvider->shouldReceive( 'getBanners' )->with()->andReturn( $bannersExpected );
		$remotePackageMetaProvider->shouldReceive( 'getBannersRTL' )->with()->andReturn( $bannersRtlExpected );
		$remotePackageMetaProvider
			->shouldReceive( 'getRequiresWordPressVersion' )
			->with()
			->andReturn( $requiresWordPressVersionExpected );
		$remotePackageMetaProvider->shouldReceive( 'getTested' )->with()->andReturn( $testedExpected );
		$remotePackageMetaProvider->shouldReceive( 'getRequiresPHPVersion' )->with()->andReturn( $requiresPhpExpected );
		$remotePackageMetaProvider->shouldReceive( 'getRequires' )->with()->andReturn(
			$requiresExpected
		);
		$sut            = new StandardCheckUpdateStandardClassFactory(
			$localPackageMetaProvider,
			$remotePackageMetaProvider
		);
		$actualResponse = $sut
		->formatForCheckUpdate();
		$this->assertInstanceOf( StandardCheckUpdateStandardClass::class, $actualResponse );
	}
}
