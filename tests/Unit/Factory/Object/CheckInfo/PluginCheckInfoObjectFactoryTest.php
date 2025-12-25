<?php
/**
 * Test file for PluginCheckInfoObjectFactory.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Object\CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Object\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckInfo\PluginCheckInfoObjectFactory;
use CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckInfo\PluginCheckInfoStandardClass;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\PluginPackageMetaValueServiceFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PluginPackageMetaValueServiceContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PluginPackageMetaValueContract;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PluginCheckInfoObjectFactory.
 */
class PluginCheckInfoObjectFactoryTest extends TestCase {
	/**
	 * Test that create() returns a PluginCheckInfoStandardClass.
	 *
	 * @return void
	 */
	public function testCreateReturnsPluginCheckInfoStandardClass(): void {
		$packageMeta = Mockery::mock( PluginPackageMetaValueContract::class );
		$packageMeta->shouldReceive( 'getName' )->andReturn( 'Test Plugin' );
		$packageMeta->shouldReceive( 'getShortSlug' )->andReturn( 'test-plugin' );
		$packageMeta->shouldReceive( 'getVersion' )->andReturn( '1.0.0' );
		$packageMeta->shouldReceive( 'getAuthor' )->andReturn( 'Test Author' );
		$packageMeta->shouldReceive( 'getRequiresWordPressVersion' )->andReturn( '5.0' );
		$packageMeta->shouldReceive( 'getTested' )->andReturn( '6.0' );
		$packageMeta->shouldReceive( 'getRequiresPHPVersion' )->andReturn( '7.4' );
		$packageMeta->shouldReceive( 'getViewURL' )->andReturn( 'https://example.com' );
		$packageMeta->shouldReceive( 'getDownloadURL' )->andReturn( 'https://example.com/download' );
		$packageMeta->shouldReceive( 'getSections' )->andReturn( [] );
		$packageMeta->shouldReceive( 'getTags' )->andReturn( [] );

		$service = Mockery::mock( PluginPackageMetaValueServiceContract::class );
		$service->shouldReceive( 'getPackageMeta' )->andReturn( $packageMeta );

		$factory = Mockery::mock( PluginPackageMetaValueServiceFactoryContract::class );
		$factory->shouldReceive( 'create' )->andReturn( $service );

		$sut    = new PluginCheckInfoObjectFactory( $factory );
		$result = $sut->create();

		$this->assertInstanceOf( PluginCheckInfoStandardClass::class, $result );
	}
}
