<?php
/**
 * Test file for StandardCheckUpdatePackageMetaValue.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Value\PackageMeta\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Value\PackageMeta\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Value\PackageMeta\CheckUpdate\StandardCheckUpdatePackageMetaValue;
use PHPUnit\Framework\TestCase;
use stdClass;
use UnexpectedValueException;

/**
 * Test class for StandardCheckUpdatePackageMetaValue.
 */
class StandardCheckUpdatePackageMetaValueTest extends TestCase {
	/**
	 * Test that constructor accepts valid data.
	 *
	 * @return void
	 */
	public function testConstructorAcceptsValidData(): void {
		$data       = new stdClass();
		$data->id   = 'test-plugin/test-plugin.php';
		$data->slug = 'test-plugin';

		$sut = new StandardCheckUpdatePackageMetaValue( $data );

		$this->assertInstanceOf( StandardCheckUpdatePackageMetaValue::class, $sut );
		$this->assertEquals( 'test-plugin/test-plugin.php', $sut->getFullSlug() );
		$this->assertEquals( 'test-plugin', $sut->getShortSlug() );
	}

	/**
	 * Test that constructor throws exception for invalid data.
	 *
	 * @return void
	 */
	public function testConstructorThrowsExceptionForInvalidData(): void {
		$data = new stdClass();

		$this->expectException( UnexpectedValueException::class );

		new StandardCheckUpdatePackageMetaValue( $data );
	}
}
