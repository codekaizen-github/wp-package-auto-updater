<?php
/**
 * Unit test for ThemePackageRoot.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Tests\Unit\PackageRoot
 */

namespace CodeKaizen\WPPackageAutoUpdater\Tests\Unit\PackageRoot;

use CodeKaizen\WPPackageAutoUpdater\PackageRoot\ThemePackageRoot;
use PHPUnit\Framework\TestCase;

/**
 * Class ThemePackageRootTest
 *
 * @covers \CodeKaizen\WPPackageAutoUpdater\PackageRoot\ThemePackageRoot
 */
class ThemePackageRootTest extends TestCase {
	/**
	 * Test instantiation of ThemePackageRoot.
	 *
	 * @return void
	 */
	public function testCanBeInstantiated(): void {
		$root = new ThemePackageRoot();
		$this->assertInstanceOf( ThemePackageRoot::class, $root );
	}
}
