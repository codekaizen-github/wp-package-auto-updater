<?php
/**
 * Unit test for PluginPackageRoot.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Tests\Unit\PackageRoot
 */

namespace CodeKaizen\WPPackageAutoUpdater\Tests\Unit\PackageRoot;

use CodeKaizen\WPPackageAutoUpdater\PackageRoot\PluginPackageRoot;
use PHPUnit\Framework\TestCase;

/**
 * Class PluginPackageRootTest
 *
 * @covers \CodeKaizen\WPPackageAutoUpdater\PackageRoot\PluginPackageRoot
 */
class PluginPackageRootTest extends TestCase {
	/**
	 * Test instantiation of PluginPackageRoot.
	 *
	 * @return void
	 */
	public function testCanBeInstantiated(): void {
		$root = new PluginPackageRoot();
		$this->assertInstanceOf( PluginPackageRoot::class, $root );
	}
}
