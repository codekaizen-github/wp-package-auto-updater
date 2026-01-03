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
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test class for ThemeCheckInfoObjectFactory.
 */
class ThemeCheckInfoObjectFactoryTest extends TestCase {
	/**
	 * Common mocks for tests.
	 *
	 * @var MockInterface&ThemePackageMetaValueContract
	 */
	protected MockInterface $packageMeta;

	/**
	 * Undocumented variable
	 *
	 * @var MockInterface&ThemePackageMetaValueServiceContract
	 */
	protected MockInterface $service;

	/**
	 * Undocumented variable
	 *
	 * @var MockInterface&ThemePackageMetaValueServiceFactoryContract
	 */
	protected MockInterface $factory;

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->packageMeta = Mockery::mock( ThemePackageMetaValueContract::class );
		$this->packageMeta->shouldReceive( 'getName' )->andReturn( 'Test Theme' );
		$this->packageMeta->shouldReceive( 'getShortSlug' )->andReturn( 'test-theme' );
		$this->packageMeta->shouldReceive( 'getVersion' )->andReturn( '1.0.0' );
		$this->packageMeta->shouldReceive( 'getAuthor' )->andReturn( 'Test Author' );
		$this->packageMeta->shouldReceive( 'getRequiresWordPressVersion' )->andReturn( '5.0' );
		$this->packageMeta->shouldReceive( 'getTested' )->andReturn( '6.0' );
		$this->packageMeta->shouldReceive( 'getRequiresPHPVersion' )->andReturn( '7.4' );
		$this->packageMeta->shouldReceive( 'getViewURL' )->andReturn( 'https://example.com' );
		$this->packageMeta->shouldReceive( 'getDownloadURL' )->andReturn( 'https://example.com/download' );
		$this->packageMeta->shouldReceive( 'getTags' )->andReturn( [] );

		$this->service = Mockery::mock( ThemePackageMetaValueServiceContract::class );
		$this->service->shouldReceive( 'getPackageMeta' )->andReturn( $this->packageMeta );

		$this->factory = Mockery::mock( ThemePackageMetaValueServiceFactoryContract::class );
		$this->factory->shouldReceive( 'create' )->andReturn( $this->service );
	}

	/**
	 * Test that create() returns a ThemeCheckInfoStandardClass.
	 *
	 * @return void
	 */
	public function testCreateReturnsThemeCheckInfoStandardClass(): void {
		$sut    = new ThemeCheckInfoObjectFactory( $this->factory );
		$result = $sut->create();

		$this->assertInstanceOf( ThemeCheckInfoStandardClass::class, $result );
	}
}
