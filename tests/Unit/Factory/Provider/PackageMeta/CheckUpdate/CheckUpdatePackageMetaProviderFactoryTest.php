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
use Psr\Log\LoggerInterface;
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
			$slug               = 'akismet/akismet.php';
			$pluginData         = new \stdClass();
			$pluginData->id     = 'w.org/plugins/akismet';
			$pluginData->slug   = 'akismet';
			$pluginData->plugin = 'akismet/akismet.php';
			// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
			$pluginData->new_version = '5.5';
			$pluginData->url         = 'https://wordpress.org/plugins/akismet/';
			$pluginData->package     = 'https://downloads.wordpress.org/plugin/akismet.5.5.zip';
			$pluginData->icons       = [
				'2x' => 'https://ps.w.org/akismet/assets/icon-256x256.png?rev=2818463',
				'1x' => 'https://ps.w.org/akismet/assets/icon-128x128.png?rev=2818463',
			];
			$pluginData->banners     = [
				'2x' => 'https://ps.w.org/akismet/assets/banner-1544x500.png?rev=2900731',
				'1x' => 'https://ps.w.org/akismet/assets/banner-772x250.png?rev=2900731',
			];
			// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
			$pluginData->banners_rtl = [];
			$pluginData->requires    = '5.8';
			$pluginData->tested      = '6.8.3';
			// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
			$pluginData->requires_php = '7.2';
			// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
			$pluginData->requires_plugins = [];

			$transient = (object) [
				'last_checked' => 1762913803,
				'response'     => [
					'akismet/akismet.php' => $pluginData,
				],
				'translations' => [],
				'no_update'    => [],
				'checked'      => [
					'akismet/akismet.php' => '5.3.7',
				],
			];
			$accessor  = Mockery::mock(
				\CodeKaizen\WPPackageAutoUpdater\Contract\Accessor\MixedAccessorContract::class
			);
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
			$slug      = 'akismet/akismet.php';
			$transient = (object) [
				'last_checked' => 1762913803,
				'response'     => [],
				'translations' => [],
				'no_update'    => [],
				'checked'      => [
					'akismet/akismet.php' => '5.3.7',
				],
			];
			$accessor  = Mockery::mock(
				\CodeKaizen\WPPackageAutoUpdater\Contract\Accessor\MixedAccessorContract::class
			);
			$logger    = Mockery::mock( LoggerInterface::class );
			$logger->shouldReceive( 'info' );
			$logger->shouldReceive( 'error' );
			$accessor->shouldReceive( 'get' )->andReturn( $transient );
			$sut = new CheckUpdatePackageMetaProviderFactory( $accessor, $slug, $logger );
			$this->expectException( InvalidCheckUpdatePackageMetaException::class );
			$sut->create();
	}
}
