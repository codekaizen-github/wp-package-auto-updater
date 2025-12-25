<?php
/**
 * Test file for CheckUpdateStandardClassValidator.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Validator\StandardClass\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Validator\StandardClass\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Validator\StandardClass\CheckUpdate\CheckUpdateStandardClassValidator;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Test class for CheckUpdateStandardClassValidator.
 */
class CheckUpdateStandardClassValidatorTest extends TestCase {
	/**
	 * Test that isValid() returns true for valid object.
	 *
	 * @return void
	 */
	public function testIsValidReturnsTrueForValidObject(): void {
		$validator = new CheckUpdateStandardClassValidator();
		$object    = new stdClass();
		$object->id = 'test-plugin/test-plugin.php';
		$object->slug = 'test-plugin';

		$result = $validator->isValid( $object );

		$this->assertIsBool( $result );
	}

	/**
	 * Test that isValid() returns false for invalid object.
	 *
	 * @return void
	 */
	public function testIsValidReturnsFalseForInvalidObject(): void {
		$validator = new CheckUpdateStandardClassValidator();
		$object    = new stdClass();

		$result = $validator->isValid( $object );

		$this->assertFalse( $result );
	}
}

