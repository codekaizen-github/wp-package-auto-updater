<?php
/**
 * Unit test for ThemePackageRootValue.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Tests\Unit\PackageRoot
 */

namespace CodeKaizen\WPPackageAutoUpdater\Tests\Unit\PackageRoot;

use CodeKaizen\WPPackageAutoUpdater\Value\PackageRoot\ThemePackageRootValue;
use PHPUnit\Framework\TestCase;

/**
 * Class ThemePackageRootTest
 *
 * @covers \CodeKaizen\WPPackageAutoUpdater\Value\PackageRoot\ThemePackageRootValue
 */
class ThemePackageRootTest extends TestCase {
	/**
	 * Test instantiation of ThemePackageRootValue.
	 *
	 * @return void
	 */
	public function testCanBeInstantiated(): void {
		$root = new ThemePackageRootValue();
		$this->assertInstanceOf( ThemePackageRootValue::class, $root );
	}
}
