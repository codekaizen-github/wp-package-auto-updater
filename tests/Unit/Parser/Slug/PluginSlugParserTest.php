<?php
/**
 * File for PluginSlugParserTest.php.
 *
 * @package WPPackageAutoUpdater
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Parser\Slug;

use CodeKaizen\WPPackageAutoUpdater\Contract\PackageRoot\PackageRootContract;
use CodeKaizen\WPPackageAutoUpdater\Parser\Slug\PluginSlugParser;
use CodeKaizen\WPPackageAutoUpdaterTests\Helper\FixturePathHelper;
use Mockery;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * Undocumented class
 */
class PluginSlugParserTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testNormalPackageRootFolderAndNormalPackageFolderAndNormalPackageFileIsValid(): void {
		$shortSlugExpected = 'my-test-plugin';
		$fullSlugExpected  = 'my-test-plugin/my-test-plugin.php';
		$filePath          = FixturePathHelper::getPathForPlugin() . '/plugins/my-test-plugin/my-test-plugin.php';
		$packageRoot       = Mockery::mock( PackageRootContract::class );
		$packageRoot->shouldReceive( 'getPackageRoot' )->with()->andReturn( FixturePathHelper::getPathForPlugin() . '/plugins' );
		$sut = new PluginSlugParser( $filePath, $packageRoot );
		$this->assertEquals( $sut->getShortSlug(), $shortSlugExpected );
		$this->assertEquals( $sut->getFullSlug(), $fullSlugExpected );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testNormalPackageRootFolderAndNormalPackageFolderAndMissingPackageFileIsInvalid(): void {
				$shortSlugExpected = 'my-test-plugin';
		$fullSlugExpected          = 'my-test-plugin/my-test-plugin.php';
		$filePath                  = FixturePathHelper::getPathForPlugin() . '/plugins/my-test-plugin/i-do-not-exist.php';
		$packageRoot               = Mockery::mock( PackageRootContract::class );
		$packageRoot->shouldReceive( 'getPackageRoot' )->with()->andReturn( FixturePathHelper::getPathForPlugin() . '/plugins' );
		$sut = new PluginSlugParser( $filePath, $packageRoot );
		$this->expectException( UnexpectedValueException::class );
		$sut->getShortSlug();
	}
}
