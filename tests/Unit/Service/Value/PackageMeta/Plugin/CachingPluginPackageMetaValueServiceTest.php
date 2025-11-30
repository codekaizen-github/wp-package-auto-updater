<?php
/**
 * CachingPluginPackageMetaValueServiceTest
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Service\Value\PackageMeta\Plugin
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Service\Value\PackageMeta\Plugin;

use CodeKaizen\WPPackageAutoUpdater\Service\Value\PackageMeta\Plugin\CachingPluginPackageMetaValueService;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PluginPackageMetaValueServiceContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PluginPackageMetaValueContract;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class CachingPluginPackageMetaValueServiceTest extends TestCase {
	/**
	 * Undocumented variable
	 *
	 * @var (PluginPackageMetaValueServiceContract&MockInterface)|null
	 */
	protected ?PluginPackageMetaValueServiceContract $service;
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	protected function setUp(): void {
		$this->service = Mockery::mock( PluginPackageMetaValueServiceContract::class );
		$this->getService()->allows(
			[
				'getPackageMeta' => Mockery::mock( PluginPackageMetaValueContract::class ),
			]
		);
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	protected function tearDown(): void {
		Mockery::close();
	}
	/**
	 * Undocumented function
	 *
	 * @return PluginPackageMetaValueServiceContract&MockInterface
	 */
	protected function getService(): PluginPackageMetaValueServiceContract&MockInterface {
		self::assertNotNull( $this->service );
		return $this->service;
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testItCachesPackageMeta(): void {
		$sut            = new CachingPluginPackageMetaValueService( $this->getService() );
		$packageMetaOne = $sut->getPackageMeta();
		$packageMetaTwo = $sut->getPackageMeta();
		$this->assertSame( $packageMetaOne, $packageMetaTwo );
	}
}
