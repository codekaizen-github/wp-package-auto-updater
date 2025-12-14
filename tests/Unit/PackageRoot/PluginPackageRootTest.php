<?php
/**
 * Unit test for PluginPackageRootValue.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Tests\Unit\PackageRoot
 */

namespace CodeKaizen\WPPackageAutoUpdater\Tests\Unit\PackageRoot;

use CodeKaizen\WPPackageAutoUpdater\Value\PackageRoot\PluginPackageRootValue;
use PHPUnit\Framework\TestCase;

/**
 * Class PluginPackageRootTest
 *
 * @covers \CodeKaizen\WPPackageAutoUpdater\Value\PackageRoot\PluginPackageRootValue
 */
class PluginPackageRootTest extends TestCase {
	/**
	 * Test instantiation of PluginPackageRootValue.
	 *
	 * @return void
	 */
	public function testCanBeInstantiated(): void {
		$root = new PluginPackageRootValue();
		$this->assertInstanceOf( PluginPackageRootValue::class, $root );
	}
}
