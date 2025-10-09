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
		$packageRoot
			->shouldReceive( 'getPackageRoot' )
			->with()
			->andReturn( FixturePathHelper::getPathForPlugin() . '/plugins' );
		$sut = new PluginSlugParser( $filePath, $packageRoot );
		$this->assertEquals( $shortSlugExpected, $sut->getShortSlug() );
		$this->assertEquals( $fullSlugExpected, $sut->getFullSlug() );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testNormalPackageRootFolderAndNormalPackageFolderAndMissingPackageFileIsInvalid(): void {
		$filePath    = FixturePathHelper::getPathForPlugin() . '/plugins/my-test-plugin/i-do-not-exist.php';
		$packageRoot = Mockery::mock( PackageRootContract::class );
		$packageRoot
			->shouldReceive( 'getPackageRoot' )
			->with()
			->andReturn( FixturePathHelper::getPathForPlugin() . '/plugins' );
		$sut = new PluginSlugParser( $filePath, $packageRoot );
		$this->expectException( UnexpectedValueException::class );
		$sut->getShortSlug();
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testNormalPackageRootFolderAndSymlinkPackageFolderAndNormalPackageFileIsValid(): void {
		$shortSlugExpected = 'my-test-plugin-symlink';
		$fullSlugExpected  = 'my-test-plugin-symlink/my-test-plugin.php';
		$filePath          = FixturePathHelper::getPathForPlugin() .
			'/plugins/my-test-plugin-symlink/my-test-plugin.php';
		$packageRoot       = Mockery::mock( PackageRootContract::class );
		$packageRoot
			->shouldReceive( 'getPackageRoot' )
			->with()
			->andReturn( FixturePathHelper::getPathForPlugin() . '/plugins' );
		$sut = new PluginSlugParser( $filePath, $packageRoot );
		$this->assertEquals( $shortSlugExpected, $sut->getShortSlug() );
		$this->assertEquals( $fullSlugExpected, $sut->getFullSlug() );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testSymlinkPackageRootFolderAndNormalPackageFolderAndNormalPackageFileIsValid(): void {
		$shortSlugExpected = 'my-test-plugin';
		$fullSlugExpected  = 'my-test-plugin/my-test-plugin.php';
		$filePath          = FixturePathHelper::getPathForPlugin() .
			'/plugins-symlink/my-test-plugin/my-test-plugin.php';
		$packageRoot       = Mockery::mock( PackageRootContract::class );
		$packageRoot
			->shouldReceive( 'getPackageRoot' )
			->with()
			->andReturn( FixturePathHelper::getPathForPlugin() . '/plugins' );
		$sut = new PluginSlugParser( $filePath, $packageRoot );
		$this->assertEquals( $shortSlugExpected, $sut->getShortSlug() );
		$this->assertEquals( $fullSlugExpected, $sut->getFullSlug() );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testSymlinkPackageRootFolderAndSymlinkPackageFolderAndNormalPackageFileIsValid(): void {
		$shortSlugExpected = 'my-test-plugin-symlink';
		$fullSlugExpected  = 'my-test-plugin-symlink/my-test-plugin.php';
		$filePath          = FixturePathHelper::getPathForPlugin() .
			'/plugins-symlink/my-test-plugin-symlink/my-test-plugin.php';
		$packageRoot       = Mockery::mock( PackageRootContract::class );
		$packageRoot
			->shouldReceive( 'getPackageRoot' )
			->with()
			->andReturn( FixturePathHelper::getPathForPlugin() . '/plugins' );
		$sut = new PluginSlugParser( $filePath, $packageRoot );
		$this->assertEquals( $shortSlugExpected, $sut->getShortSlug() );
		$this->assertEquals( $fullSlugExpected, $sut->getFullSlug() );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testNormalPackageRootFolderAndNormalPackageFolderAndOtherNamedPackageFileIsValid(): void {
		$shortSlugExpected = 'other-test-plugin';
		$fullSlugExpected  = 'other-test-plugin/different-name-plugin.php';
		$filePath          = FixturePathHelper::getPathForPlugin() .
			'/plugins/other-test-plugin/different-name-plugin.php';
		$packageRoot       = Mockery::mock( PackageRootContract::class );
		$packageRoot
			->shouldReceive( 'getPackageRoot' )
			->with()
			->andReturn( FixturePathHelper::getPathForPlugin() . '/plugins' );
		$sut = new PluginSlugParser( $filePath, $packageRoot );
		$this->assertEquals( $shortSlugExpected, $sut->getShortSlug() );
		$this->assertEquals( $fullSlugExpected, $sut->getFullSlug() );
	}
		/**
		 * Undocumented function
		 *
		 * @return void
		 */
	public function testNormalPackageRootFolderAndNoPackageFolderAndNormalPackageFileIsValid(): void {
		$shortSlugExpected = 'simple-plugin';
		$fullSlugExpected  = 'simple-plugin.php';
		$filePath          = FixturePathHelper::getPathForPlugin() .
			'/plugins/simple-plugin.php';
		$packageRoot       = Mockery::mock( PackageRootContract::class );
		$packageRoot
			->shouldReceive( 'getPackageRoot' )
			->with()
			->andReturn( FixturePathHelper::getPathForPlugin() . '/plugins' );
		$sut = new PluginSlugParser( $filePath, $packageRoot );
		$this->assertEquals( $shortSlugExpected, $sut->getShortSlug() );
		$this->assertEquals( $fullSlugExpected, $sut->getFullSlug() );
	}
}
