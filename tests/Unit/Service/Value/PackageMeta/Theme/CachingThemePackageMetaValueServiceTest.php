<?php
/**
 * CachingThemePackageMetaValueServiceTest
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Service\Value\PackageMeta\Theme
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Service\Value\PackageMeta\Theme;

use CodeKaizen\WPPackageAutoUpdater\Service\Value\PackageMeta\Theme\CachingThemePackageMetaValueService;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\ThemePackageMetaValueServiceContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\ThemePackageMetaValueContract;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class CachingThemePackageMetaValueServiceTest extends TestCase {
	/**
	 * Undocumented variable
	 *
	 * @var (ThemePackageMetaValueServiceContract&MockInterface)|null
	 */
	protected ?ThemePackageMetaValueServiceContract $service;
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	protected function setUp(): void {
		$this->service = Mockery::mock( ThemePackageMetaValueServiceContract::class );
		$this->getService()->allows(
			[
				'getPackageMeta' => Mockery::mock( ThemePackageMetaValueContract::class ),
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
	 * @return ThemePackageMetaValueServiceContract&MockInterface
	 */
	protected function getService(): ThemePackageMetaValueServiceContract&MockInterface {
		self::assertNotNull( $this->service );
		return $this->service;
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testItCachesPackageMeta(): void {
		$sut            = new CachingThemePackageMetaValueService( $this->getService() );
		$packageMetaOne = $sut->getPackageMeta();
		$packageMetaTwo = $sut->getPackageMeta();
		$this->assertSame( $packageMetaOne, $packageMetaTwo );
	}
}
