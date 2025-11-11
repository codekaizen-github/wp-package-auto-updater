<?php
/**
 * Unit test for CheckUpdatePackageMetaProvider.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Tests\Unit\Provider\PackageMeta\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Tests\Unit\Provider\PackageMeta\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Provider\PackageMeta\CheckUpdate\CheckUpdatePackageMetaProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class CheckUpdatePackageMetaProviderTest
 *
 * @covers \CodeKaizen\WPPackageAutoUpdater\Provider\PackageMeta\CheckUpdate\CheckUpdatePackageMetaProvider
 */
class CheckUpdatePackageMetaProviderTest extends TestCase {
	/**
	 * Test instantiation and getters.
	 *
	 * @return void
	 */
	public function testCanBeInstantiatedAndGettersWork(): void {
		$data       = new stdClass();
		$data->id   = 'plugin/full-slug';
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

		// Assert icons array structure before instantiating provider.
		$this->assertIsArray( $data->icons );
		$this->assertArrayHasKey( 'default', $data->icons );
		$this->assertSame( 'https://example.com/icon.png', $data->icons['default'] );

		$provider = new CheckUpdatePackageMetaProvider( $data );

		$this->assertSame( 'plugin/full-slug', $provider->getFullSlug() );
		$this->assertSame( 'plugin', $provider->getShortSlug() );
		$this->assertSame( '1.2.3', $provider->getVersion() );
		$this->assertSame( 'https://example.com', $provider->getViewURL() );
		$this->assertSame( 'https://example.com/download.zip', $provider->getDownloadURL() );
		$this->assertSame( [ 'default' => 'https://example.com/icon.png' ], $provider->getIcons() );
		$this->assertSame( [ 'default' => 'https://example.com/banner.png' ], $provider->getBanners() );
		$this->assertSame( [ 'default' => 'https://example.com/banner-rtl.png' ], $provider->getBannersRTL() );
		$this->assertSame( '6.0', $provider->getTested() );
		$this->assertSame( '5.0', $provider->getRequiresWordPressVersion() );
		$this->assertSame( '7.4', $provider->getRequiresPHPVersion() );
	}
}
