<?php
/**
 * Unit test for InvalidCheckUpdatePackageMetaException.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Tests\Unit\Exception
 */

namespace CodeKaizen\WPPackageAutoUpdater\Tests\Unit\Exception;

use CodeKaizen\WPPackageAutoUpdater\Exception\InvalidCheckUpdatePackageMetaException;
use PHPUnit\Framework\TestCase;

/**
 * Class InvalidCheckUpdatePackageMetaExceptionTest
 *
 * @covers \CodeKaizen\WPPackageAutoUpdater\Exception\InvalidCheckUpdatePackageMetaException
 */
class InvalidCheckUpdatePackageMetaExceptionTest extends TestCase {

	/**
	 * Test instantiation of InvalidCheckUpdatePackageMetaException.
	 *
	 * @return void
	 */
	public function testCanBeInstantiated(): void {
		$exception = new InvalidCheckUpdatePackageMetaException( 'Test message' );
		$this->assertInstanceOf( InvalidCheckUpdatePackageMetaException::class, $exception );
		$this->assertSame( 'Test message', $exception->getMessage() );
	}

	/**
	 * Test that InvalidCheckUpdatePackageMetaException is throwable.
	 *
	 * @return void
	 */
	public function testIsThrowable(): void {
		$exception = new InvalidCheckUpdatePackageMetaException();
		$this->assertInstanceOf( \Throwable::class, $exception );
	}
}
