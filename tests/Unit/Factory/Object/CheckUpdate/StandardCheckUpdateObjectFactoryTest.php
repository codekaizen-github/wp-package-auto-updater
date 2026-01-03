<?php
/**
 * Test file for StandardCheckUpdateObjectFactory.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Object\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Object\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckUpdate\StandardCheckUpdateObjectFactory;
use CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckUpdate\StandardCheckUpdateStandardClass;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test class for StandardCheckUpdateObjectFactory.
 */
class StandardCheckUpdateObjectFactoryTest extends TestCase {
	/**
	 * Common mocks for tests.
	 *
	 * @var MockInterface&PackageMetaValueContract
	 */
	protected MockInterface $packageMeta;

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->packageMeta = Mockery::mock( PackageMetaValueContract::class );
		$this->packageMeta->shouldReceive( 'getFullSlug' )->andReturn( 'test-plugin/test-plugin.php' );
		$this->packageMeta->shouldReceive( 'getShortSlug' )->andReturn( 'test-plugin' );
		$this->packageMeta->shouldReceive( 'getVersion' )->andReturn( '1.0.0' );
		$this->packageMeta->shouldReceive( 'getViewURL' )->andReturn( 'https://example.com' );
		$this->packageMeta->shouldReceive( 'getDownloadURL' )->andReturn( 'https://example.com/download' );
		$this->packageMeta->shouldReceive( 'getIcons' )->andReturn( [] );
		$this->packageMeta->shouldReceive( 'getBanners' )->andReturn( [] );
		$this->packageMeta->shouldReceive( 'getBannersRTL' )->andReturn( [] );
		$this->packageMeta->shouldReceive( 'getTested' )->andReturn( '6.0' );
		$this->packageMeta->shouldReceive( 'getRequiresWordPressVersion' )->andReturn( '5.0' );
		$this->packageMeta->shouldReceive( 'getRequiresPHPVersion' )->andReturn( '7.4' );
	}

	/**
	 * Test that create() returns a StandardCheckUpdateStandardClass.
	 *
	 * @return void
	 */
	public function testCreateReturnsStandardCheckUpdateStandardClass(): void {
		$sut    = new StandardCheckUpdateObjectFactory( $this->packageMeta );
		$result = $sut->create();
		$this->assertInstanceOf( StandardCheckUpdateStandardClass::class, $result );
	}
}
