<?php
/**
 * Test
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\AutoUpdater\ORASHub
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\AutoUpdater\ORASHub;

use CodeKaizen\WPPackageAutoUpdater\AutoUpdater\ORASHub\ThemeORASHubAutoUpdater;
use CodeKaizen\WPPackageAutoUpdaterTests\Helper\FixturePathHelper;
use Mockery;
use Psr\Log\LoggerInterface;
use WP_Mock\Tools\TestCase;

/**
 * Test
 * You need to extend WP_Mock\Tools\TestCase, not PHPUnit\Framework\TestCase,
 * in order for the `overload` mock and its assertions to work.
 */
class ThemeORASHubAutoUpdaterTest extends TestCase {
	/**
	 * Undocumented function.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @link https://stackoverflow.com/a/72639610/13461208
	 * @return void
	 */
	public function testInitHooks(): void {
		$filePath      = FixturePathHelper::getPathForTheme() . '/themes/my-test-theme/my-test-theme.php';
		$baseURL       = 'https://codekaizen.net';
		$metaKey       = 'org.codekaizen-github.wp-package-deploy.wp-package-metadata';
		$httpOptions   = [];
		$logger        = Mockery::mock( LoggerInterface::class );
		$checkInfoHook = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo\StandardCheckInfoHook'
		);
		$checkInfoHook->shouldReceive( 'init' )->once();
		$checkUpdateHook = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate\StandardCheckUpdateHook'
		);
		$checkUpdateHook->shouldReceive( 'init' )->once();
		$sut = new ThemeORASHubAutoUpdater( $filePath, $baseURL, $metaKey, $httpOptions, $logger );
		$sut->init();
		$this->expectNotToPerformAssertions();
	}
}
