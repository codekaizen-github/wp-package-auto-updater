<?php
/**
 * Test file for ThemeCheckInfoObjectFactory.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Object\CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Object\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckInfo\ThemeCheckInfoObjectFactory;
use CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckInfo\ThemeCheckInfoStandardClass;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\ThemePackageMetaValueServiceFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\ThemePackageMetaValueServiceContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\ThemePackageMetaValueContract;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Test class for ThemeCheckInfoObjectFactory.
 */
class ThemeCheckInfoObjectFactoryTest extends TestCase {
	/**
	 * Test that create() returns a ThemeCheckInfoStandardClass.
	 *
	 * @return void
	 */
	public function testCreateReturnsThemeCheckInfoStandardClass(): void {
		$packageMeta = Mockery::mock( ThemePackageMetaValueContract::class );
		$packageMeta->shouldReceive( 'getName' )->andReturn( 'Test Theme' );
		$packageMeta->shouldReceive( 'getShortSlug' )->andReturn( 'test-theme' );
		$packageMeta->shouldReceive( 'getVersion' )->andReturn( '1.0.0' );
		$packageMeta->shouldReceive( 'getAuthor' )->andReturn( 'Test Author' );
		$packageMeta->shouldReceive( 'getRequiresWordPressVersion' )->andReturn( '5.0' );
		$packageMeta->shouldReceive( 'getTested' )->andReturn( '6.0' );
		$packageMeta->shouldReceive( 'getRequiresPHPVersion' )->andReturn( '7.4' );
		$packageMeta->shouldReceive( 'getViewURL' )->andReturn( 'https://example.com' );
		$packageMeta->shouldReceive( 'getDownloadURL' )->andReturn( 'https://example.com/download' );
		$packageMeta->shouldReceive( 'getTags' )->andReturn( [] );

		$service = Mockery::mock( ThemePackageMetaValueServiceContract::class );
		$service->shouldReceive( 'getPackageMeta' )->andReturn( $packageMeta );

		$factory = Mockery::mock( ThemePackageMetaValueServiceFactoryContract::class );
		$factory->shouldReceive( 'create' )->andReturn( $service );

		$sut    = new ThemeCheckInfoObjectFactory( $factory );
		$result = $sut->create();

		$this->assertInstanceOf( ThemeCheckInfoStandardClass::class, $result );
	}
}
