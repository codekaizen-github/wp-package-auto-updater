<?php
/**
 * Unit test for WordPressTransientProxyMixedAccessor.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Tests\Unit\Provider\WordPress\Transient
 */

namespace CodeKaizen\WPPackageAutoUpdater\Tests\Unit\Provider\WordPress\Transient;

use CodeKaizen\WPPackageAutoUpdater\Accessor\Mixed\WordPressTransientProxyMixedAccessor;
use PHPUnit\Framework\TestCase;

/**
 * Class TransientWordPressProviderTest
 *
 * @covers \CodeKaizen\WPPackageAutoUpdater\Accessor\Mixed\WordPressTransientProxyMixedAccessor
 */
class TransientWordPressProviderTest extends TestCase {

	/**
	 * Test instantiation of WordPressTransientProxyMixedAccessor.
	 *
	 * @return void
	 */
	public function testCanBeInstantiated(): void {
		$provider = new WordPressTransientProxyMixedAccessor( 'test_key' );
		$this->assertInstanceOf( WordPressTransientProxyMixedAccessor::class, $provider );
	}
}
