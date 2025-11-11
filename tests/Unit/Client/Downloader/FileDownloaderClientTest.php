<?php
/**
 * Test file for FileDownloaderClient.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Client\Downloader
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Client\Downloader;

use CodeKaizen\WPPackageAutoUpdater\Client\Downloader\FileDownloaderClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Test class for FileDownloaderClient.
 */
class FileDownloaderClientTest extends TestCase {

	/**
	 * Test successful file download.
	 *
	 * @return void
	 */
	public function testDownloadSuccessful(): void {
		// Set up test double.
		$mockHandler  = new MockHandler();
		$handlerStack = HandlerStack::create( $mockHandler );
		$sut          = new FileDownloaderClient(
			'http://example.com/test.zip',
			[ 'handler' => $handlerStack ]
		);

		// Set up mock response.
		$mockHandler->append( new Response( 200, [], 'test content' ) );

		// Execute.
		$sut->download();

		// Verify.
		$fileName = $sut->getFileName();
		$this->assertNotNull( $fileName );
		$this->assertFileExists( $fileName );
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$this->assertEquals( 'test content', file_get_contents( $fileName ) );

		// Cleanup.
		// phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
		unlink( $fileName );
	}

	/**
	 * Test failed download with non-200 response.
	 *
	 * @return void
	 */
	public function testDownloadFailure(): void {
		// Set up test double.
		$mockHandler  = new MockHandler();
		$handlerStack = HandlerStack::create( $mockHandler );
		$sut          = new FileDownloaderClient(
			'http://example.com/test.zip',
			[ 'handler' => $handlerStack ]
		);

		// Set up mock response.
		$mockHandler->append( new Response( 404 ) );

		// Execute and verify.
		$this->expectException( \Exception::class );
		$this->expectExceptionMessage(
			'Client error: `GET http://example.com/test.zip` resulted in a `404 Not Found` response'
		);

		$sut->download();
	}

	/**
	 * Test getFileName before download.
	 *
	 * @return void
	 */
	public function testGetFileNameBeforeDownload(): void {
		$sut = new FileDownloaderClient( 'http://example.com/test.zip' );
		$this->assertNull( $sut->getFileName() );
	}

	/**
	 * Test constructor sets URL correctly.
	 *
	 * @return void
	 */
	public function testConstructorSetsUrl(): void {
		$url    = 'http://example.com/test.zip';
		$client = new FileDownloaderClient( $url );

		$this->assertInstanceOf( FileDownloaderClient::class, $client );
		$this->assertNull( $client->getFileName() );
	}
}
