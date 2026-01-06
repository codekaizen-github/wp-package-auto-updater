<?php
/**
 * Test file for WordPressTransientProxyMixedAccessor.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Accessor\Mixed
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Accessor\Mixed;

use CodeKaizen\WPPackageAutoUpdater\Accessor\Mixed\WordPressTransientProxyMixedAccessor;
use PHPUnit\Framework\TestCase;
use WP_Mock;

/**
 * Test class for WordPressTransientProxyMixedAccessor.
 */
class WordPressTransientProxyMixedAccessorTest extends TestCase {
	/**
	 * Test that get() calls get_site_transient with the correct key.
	 *
	 * @return void
	 */
	public function testGetCallsGetSiteTransient(): void {
		WP_Mock::userFunction( 'get_site_transient' )
			->once()
			->with( 'test_key' )
			->andReturn( 'test_value' );

		$accessor = new WordPressTransientProxyMixedAccessor( 'test_key' );
		$result   = $accessor->get();

		$this->assertEquals( 'test_value', $result );
	}
}
