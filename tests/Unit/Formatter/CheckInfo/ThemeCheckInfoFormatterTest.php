<?php
/**
 * Tests
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Formatter\CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Formatter\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckInfo\ThemeCheckInfoObjectFactory;
use CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckInfo\ThemeCheckInfoStandardClass;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\ThemePackageMetaValueServiceFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\ThemePackageMetaValueServiceContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\ThemePackageMetaValueContract;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class ThemeCheckInfoFormatterTest extends TestCase {
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
		$provider             = Mockery::mock( ThemePackageMetaValueContract::class );
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
		$service = Mockery::mock( ThemePackageMetaValueServiceContract::class );
		$service->shouldReceive( 'getPackageMeta' )->with()->andReturn( $provider );
		$serviceFactory = Mockery::mock( ThemePackageMetaValueServiceFactoryContract::class );
		$serviceFactory->shouldReceive( 'create' )->with()->andReturn( $service );
		$sut                 = ( new ThemeCheckInfoObjectFactory( $serviceFactory ) );
		$actualStandardClass = $sut->create();
		$this->assertInstanceOf( ThemeCheckInfoStandardClass::class, $actualStandardClass );
	}
}
