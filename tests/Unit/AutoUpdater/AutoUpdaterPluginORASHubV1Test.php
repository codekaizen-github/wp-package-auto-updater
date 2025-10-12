<?php
/**
 * Test
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\AutoUpdater
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\AutoUpdater;

use CodeKaizen\WPPackageAutoUpdater\AutoUpdater\AutoUpdaterPluginORASHubV1;
use CodeKaizen\WPPackageAutoUpdaterTests\Helper\FixturePathHelper;
use Mockery;
use WP_Mock\Tools\TestCase;

/**
 * Test
 * You need to extend WP_Mock\Tools\TestCase, not PHPUnit\Framework\TestCase,
 * in order for the `overload` mock and its assertions to work.
 */
class AutoUpdaterPluginORASHubV1Test extends TestCase {
	/**
	 * Undocumented function.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @link https://stackoverflow.com/a/72639610/13461208
	 * @return void
	 */
	public function testInitHooks(): void {
		$filePath      = FixturePathHelper::getPathForPlugin() . '/plugins/my-test-plugin/my-test-plugin.php';
		$baseURL       = 'https://codekaizen.net';
		$checkInfoHook = Mockery::mock( 'overload:CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo\PluginCheckInfoHook' );
		$checkInfoHook->shouldReceive( 'init' )->once();
		$checkUpdateHook = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate\PluginCheckUpdateHook'
		);
		$checkUpdateHook->shouldReceive( 'init' )->once();
		$sut = new AutoUpdaterPluginORASHubV1( $filePath, $baseURL );
		$sut->init();
		$this->expectNotToPerformAssertions();
	}
}
