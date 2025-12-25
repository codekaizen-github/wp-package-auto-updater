<?php
/**
 * Unit test for ThemePackageRootValue.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Value\PackageRoot
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Value\PackageRoot;

use CodeKaizen\WPPackageAutoUpdater\Value\PackageRoot\ThemePackageRootValue;
use PHPUnit\Framework\TestCase;
use WP_Mock;

/**
 * Class ThemePackageRootValueTest
 *
 * @covers \CodeKaizen\WPPackageAutoUpdater\Value\PackageRoot\ThemePackageRootValue
 */
class ThemePackageRootValueTest extends TestCase {
	/**
	 * Test instantiation of ThemePackageRootValue.
	 *
	 * @return void
	 */
	public function testCanBeInstantiated(): void {
		WP_Mock::userFunction( 'get_theme_root' )
			->andReturn( '/path/to/themes' );

		$root = new ThemePackageRootValue();
		$this->assertInstanceOf( ThemePackageRootValue::class, $root );
	}

	/**
	 * Test getPackageRoot returns get_theme_root().
	 *
	 * @return void
	 */
	public function testGetPackageRootReturnsGetThemeRoot(): void {
		WP_Mock::userFunction( 'get_theme_root' )
			->andReturn( '/path/to/themes' );

		$root = new ThemePackageRootValue();
		$this->assertEquals( '/path/to/themes', $root->getPackageRoot() );
	}
}
