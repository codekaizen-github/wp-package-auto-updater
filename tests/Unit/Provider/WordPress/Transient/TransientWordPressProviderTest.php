<?php
/**
 * Unit test for TransientWordPressProvider.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Tests\Unit\Provider\WordPress\Transient
 */

namespace CodeKaizen\WPPackageAutoUpdater\Tests\Unit\Provider\WordPress\Transient;

use CodeKaizen\WPPackageAutoUpdater\Provider\WordPress\Transient\TransientWordPressProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class TransientWordPressProviderTest
 *
 * @covers \CodeKaizen\WPPackageAutoUpdater\Provider\WordPress\Transient\TransientWordPressProvider
 */
class TransientWordPressProviderTest extends TestCase {

	/**
	 * Test instantiation of TransientWordPressProvider.
	 *
	 * @return void
	 */
	public function testCanBeInstantiated(): void {
		$provider = new TransientWordPressProvider( 'test_key' );
		$this->assertInstanceOf( TransientWordPressProvider::class, $provider );
	}
}
