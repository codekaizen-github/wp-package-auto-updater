<?php
/**
 * Unit tests for CachingCheckUpdatePackageMetaValueService
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Tests\Unit\Service\Value\PackageMeta\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Tests\Unit\Service\Value\PackageMeta\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Service\Value\PackageMeta\CheckUpdate\CachingCheckUpdatePackageMetaValueService;
use CodeKaizen\WPPackageAutoUpdater\Contract\Service\Value\PackageMeta\CheckUpdatePackageMetaValueServiceContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Value\PackageMeta\CheckUpdatePackageMetaValueContract;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class CachingCheckUpdatePackageMetaValueServiceTest extends TestCase {

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
	public function testReturnsCachedValueOnSecondCall(): void {
		$mockService     = Mockery::mock( CheckUpdatePackageMetaValueServiceContract::class );
		$mockPackageMeta = Mockery::mock( CheckUpdatePackageMetaValueContract::class );
		$mockService->shouldReceive( 'getPackageMeta' )->once()->andReturn( $mockPackageMeta );

		$sut = new CachingCheckUpdatePackageMetaValueService( $mockService );

		$first  = $sut->getPackageMeta();
		$second = $sut->getPackageMeta();

		$this->assertSame( $mockPackageMeta, $first );
		$this->assertSame( $mockPackageMeta, $second );
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testReturnsValueFromServiceOnFirstCall(): void {
		$mockService     = Mockery::mock( CheckUpdatePackageMetaValueServiceContract::class );
		$mockPackageMeta = Mockery::mock( CheckUpdatePackageMetaValueContract::class );
		$mockService->shouldReceive( 'getPackageMeta' )->once()->andReturn( $mockPackageMeta );

		$sut = new CachingCheckUpdatePackageMetaValueService( $mockService );

		$result = $sut->getPackageMeta();
		$this->assertSame( $mockPackageMeta, $result );
	}
}
