<?php
/**
 * Unit test for CheckUpdatePackageMetaProviderFactory.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Tests\Unit\Factory\Provider\PackageMeta\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Tests\Unit\Factory\Provider\PackageMeta\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\CheckUpdate\CheckUpdatePackageMetaProviderFactory;
use CodeKaizen\WPPackageAutoUpdater\Provider\PackageMeta\CheckUpdate\CheckUpdatePackageMetaProvider;
use CodeKaizen\WPPackageAutoUpdater\Exception\InvalidCheckUpdatePackageMetaException;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class CheckUpdatePackageMetaProviderFactoryTest
 *
 * @covers \CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\CheckUpdate\CheckUpdatePackageMetaProviderFactory
 */
class CheckUpdatePackageMetaProviderFactoryTest extends TestCase {
	/**
	 * Test create returns provider for valid data.
	 *
	 * @return void
	 */
	public function testCreateReturnsProviderForValidData(): void {
		$slug       = 'plugin/full-slug';
		$data       = new stdClass();
		$data->id   = $slug;
		$data->slug = 'plugin';
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$data->new_version = '1.2.3';
		$data->url         = 'https://example.com';
		$data->package     = 'https://example.com/download.zip';
		$data->icons       = [ 'default' => 'https://example.com/icon.png' ];
		$data->banners     = [ 'default' => 'https://example.com/banner.png' ];
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$data->banners_rtl = [ 'default' => 'https://example.com/banner-rtl.png' ];
		$data->tested      = '6.0';
		$data->requires    = '5.0';
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$data->requires_php = '7.4';
		$transient          = [ $slug => $data ];
		// phpcs:ignore Generic.Files.LineLength.TooLong
		$accessor = Mockery::mock( \CodeKaizen\WPPackageAutoUpdater\Contract\Accessor\MixedAccessorContract::class );
		$accessor->shouldReceive( 'get' )->andReturn( $transient );
		$sut = new CheckUpdatePackageMetaProviderFactory( $accessor, $slug );
		$this->assertInstanceOf( CheckUpdatePackageMetaProvider::class, $sut->create() );
	}

	/**
	 * Test create throws for missing slug.
	 *
	 * @return void
	 */
	public function testCreateThrowsForMissingSlug(): void {
		$slug      = 'plugin/full-slug';
		$transient = [];
		// phpcs:ignore Generic.Files.LineLength.TooLong
		$accessor = Mockery::mock( \CodeKaizen\WPPackageAutoUpdater\Contract\Accessor\MixedAccessorContract::class );
		$accessor->shouldReceive( 'get' )->andReturn( $transient );
		$sut = new CheckUpdatePackageMetaProviderFactory( $accessor, $slug );
		$this->expectException( InvalidCheckUpdatePackageMetaException::class );
		$sut->create();
	}
}
