<?php
/**
 * File for ThemeSlugParserTest.php.
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Parser\Slug
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Parser\Slug;

use CodeKaizen\WPPackageAutoUpdater\Contract\PackageRoot\PackageRootContract;
use CodeKaizen\WPPackageAutoUpdater\Parser\Slug\ThemeSlugParser;
use CodeKaizen\WPPackageAutoUpdaterTests\Helper\FixturePathHelper;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use UnexpectedValueException;

/**
 * Undocumented class
 */
class ThemeSlugParserTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testNormalPackageRootFolderAndNormalPackageFolderAndNormalPackageFileIsValid(): void {
		$shortSlugExpected = 'my-test-theme';
		$fullSlugExpected  = 'my-test-theme/style.css';
		$filePath          = FixturePathHelper::getPathForTheme() . '/themes/my-test-theme/functions.php';
		$packageRoot       = Mockery::mock( PackageRootContract::class );
		$packageRoot
			->shouldReceive( 'getPackageRoot' )
			->with()
			->andReturn( FixturePathHelper::getPathForTheme() . '/themes' );
		$sut = new ThemeSlugParser( $filePath, $packageRoot );
		$this->assertEquals( $shortSlugExpected, $sut->getShortSlug() );
		$this->assertEquals( $fullSlugExpected, $sut->getFullSlug() );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testNormalPackageRootFolderAndNormalPackageFolderAndMissingPackageFileIsInvalid(): void {
		$filePath    = FixturePathHelper::getPathForTheme() . '/themes/fake-theme/functions.php';
		$packageRoot = Mockery::mock( PackageRootContract::class );
		$packageRoot
			->shouldReceive( 'getPackageRoot' )
			->with()
			->andReturn( FixturePathHelper::getPathForTheme() . '/themes' );
		$logger = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'error' );
		$sut = new ThemeSlugParser( $filePath, $packageRoot, $logger );
		$this->expectException( UnexpectedValueException::class );
		$sut->getShortSlug();
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testNormalPackageRootFolderAndSymlinkPackageFolderAndNormalPackageFileIsValid(): void {
		$shortSlugExpected = 'my-test-theme-symlink';
		$fullSlugExpected  = 'my-test-theme-symlink/style.css';
		$filePath          = FixturePathHelper::getPathForTheme() .
			'/themes/my-test-theme-symlink/functions.php';
		$packageRoot       = Mockery::mock( PackageRootContract::class );
		$packageRoot
			->shouldReceive( 'getPackageRoot' )
			->with()
			->andReturn( FixturePathHelper::getPathForTheme() . '/themes' );
		$sut = new ThemeSlugParser( $filePath, $packageRoot );
		$this->assertEquals( $shortSlugExpected, $sut->getShortSlug() );
		$this->assertEquals( $fullSlugExpected, $sut->getFullSlug() );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testSymlinkPackageRootFolderAndNormalPackageFolderAndNormalPackageFileIsValid(): void {
		$shortSlugExpected = 'my-test-theme';
		$fullSlugExpected  = 'my-test-theme/style.css';
		$filePath          = FixturePathHelper::getPathForTheme() .
			'/themes-symlink/my-test-theme/functions.php';
		$packageRoot       = Mockery::mock( PackageRootContract::class );
		$packageRoot
			->shouldReceive( 'getPackageRoot' )
			->with()
			->andReturn( FixturePathHelper::getPathForTheme() . '/themes' );
		$sut = new ThemeSlugParser( $filePath, $packageRoot );
		$this->assertEquals( $shortSlugExpected, $sut->getShortSlug() );
		$this->assertEquals( $fullSlugExpected, $sut->getFullSlug() );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testSymlinkPackageRootFolderAndSymlinkPackageFolderAndNormalPackageFileIsValid(): void {
		$shortSlugExpected = 'my-test-theme-symlink';
		$fullSlugExpected  = 'my-test-theme-symlink/style.css';
		$filePath          = FixturePathHelper::getPathForTheme() .
			'/themes-symlink/my-test-theme-symlink/functions.php';
		$packageRoot       = Mockery::mock( PackageRootContract::class );
		$packageRoot
			->shouldReceive( 'getPackageRoot' )
			->with()
			->andReturn( FixturePathHelper::getPathForTheme() . '/themes' );
		$sut = new ThemeSlugParser( $filePath, $packageRoot );
		$this->assertEquals( $shortSlugExpected, $sut->getShortSlug() );
		$this->assertEquals( $fullSlugExpected, $sut->getFullSlug() );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testNormalPackageRootFolderAndNormalPackageFolderAndOtherNamedPackageFileIsValid(): void {
		$shortSlugExpected = 'other-test-theme';
		$fullSlugExpected  = 'other-test-theme/style.css';
		$filePath          = FixturePathHelper::getPathForTheme() .
			'/themes/other-test-theme/module.php';
		$packageRoot       = Mockery::mock( PackageRootContract::class );
		$packageRoot
			->shouldReceive( 'getPackageRoot' )
			->with()
			->andReturn( FixturePathHelper::getPathForTheme() . '/themes' );
		$sut = new ThemeSlugParser( $filePath, $packageRoot );
		$this->assertEquals( $shortSlugExpected, $sut->getShortSlug() );
		$this->assertEquals( $fullSlugExpected, $sut->getFullSlug() );
	}
}
