<?php
/**
 * Unit tests for StandardCheckUpdatePackageMetaValueService
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Tests\Unit\Service\Value\PackageMeta\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Tests\Unit\Service\Value\PackageMeta\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Service\Value\PackageMeta\CheckUpdate\StandardCheckUpdatePackageMetaValueService;
use CodeKaizen\WPPackageAutoUpdater\Contract\Accessor\MixedAccessorContract;
use CodeKaizen\WPPackageAutoUpdater\Exception\InvalidCheckUpdatePackageMetaException;
use CodeKaizen\WPPackageAutoUpdater\Value\PackageMeta\CheckUpdate\StandardCheckUpdatePackageMetaValue;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use stdClass;

/**
 * Undocumented class
 */
class StandardCheckUpdatePackageMetaValueServiceTest extends TestCase {

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function tearDown(): void {
		Mockery::close();
		parent::tearDown();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testGetPackageMetaReturnsValueForValidData() {
		$slug               = 'akismet/akismet.php';
		$pluginData         = new stdClass();
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

		$accessor = Mockery::mock( MixedAccessorContract::class );
		$logger   = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		$logger->shouldReceive( 'error' );
		$accessor->shouldReceive( 'get' )->andReturn( $transient );

		$sut = new StandardCheckUpdatePackageMetaValueService( $accessor, $slug, $logger );

		$result = $sut->getPackageMeta();
		$this->assertInstanceOf( StandardCheckUpdatePackageMetaValue::class, $result );
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testGetPackageMetaThrowsForMissingSlug() {
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
		$accessor  = Mockery::mock( MixedAccessorContract::class );
		$logger    = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		$logger->shouldReceive( 'error' );
		$accessor->shouldReceive( 'get' )->andReturn( $transient );

		$sut = new StandardCheckUpdatePackageMetaValueService( $accessor, $slug, $logger );

		$this->expectException( InvalidCheckUpdatePackageMetaException::class );
		$sut->getPackageMeta();
	}
}
