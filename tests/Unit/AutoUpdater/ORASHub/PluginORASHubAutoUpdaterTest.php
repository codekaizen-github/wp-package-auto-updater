<?php
/**
 * Test
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\AutoUpdater\ORASHub
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\AutoUpdater\ORASHub;

use CodeKaizen\WPPackageAutoUpdater\AutoUpdater\ORASHub\PluginORASHubAutoUpdater;
use CodeKaizen\WPPackageAutoUpdaterTests\Helper\FixturePathHelper;
use CodeKaizen\WPPackageAutoUpdater\Contract\Factory\ObjectFactoryContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\PackageMetaValueServiceFactoryContract;
use Mockery;
use Mockery\MockInterface;
use Psr\Log\LoggerInterface;
use WP_Mock\Tools\TestCase;

/**
 * Test
 * You need to extend WP_Mock\Tools\TestCase, not PHPUnit\Framework\TestCase,
 * in order for the `overload` mock and its assertions to work.
 */
class PluginORASHubAutoUpdaterTest extends TestCase {
	/**
	 * Undocumented variable
	 *
	 * @var MockInterface&LoggerInterface
	 */
	protected MockInterface $logger;

	/**
	 * Undocumented variable
	 *
	 * @var PackageMetaValueServiceFactoryContract&MockInterface
	 */
	protected PackageMetaValueServiceFactoryContract $localFactory;

	/**
	 * Undocumented variable
	 *
	 * @var ObjectFactoryContract&MockInterface
	 */
	protected ObjectFactoryContract $remoteFactory;

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
		$metaKey       = 'org.codekaizen-github.wp-package-deploy.wp-package-metadata';
		$httpOptions   = [];
		$this->logger  = Mockery::mock( LoggerInterface::class );
		$checkInfoHook = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo\StandardCheckInfoHook'
		);
		$checkInfoHook->shouldReceive( 'init' )->once();
		$checkUpdateHook = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate\StandardCheckUpdateHook'
		);
		$checkUpdateHook->shouldReceive( 'init' )->once();
		$sut = new PluginORASHubAutoUpdater( $filePath, $baseURL, $metaKey, $httpOptions, $this->logger );
		$sut->init();
		$this->expectNotToPerformAssertions();
	}
}
