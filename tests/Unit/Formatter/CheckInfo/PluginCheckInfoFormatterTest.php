<?php
/**
 * Test
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Formatter\CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Formatter\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo\PluginCheckInfoFormatter;
use CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckInfo\PluginCheckInfoMetaObject;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Provider\PackageMeta\PluginPackageMetaProviderContract;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class PluginCheckInfoFormatterTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testValid(): void {
		$nameExpected         = 'Test Plugin';
		$slugExpected         = 'test-plugin';
		$homepageExpected     = 'https://codekaizen.net';
		$versionExpected      = '3.0.1';
		$authorExpected       = 'Andrew Dawes';
		$requiresExpected     = '6.8.2';
		$requiresPhpExpected  = '8.2.1';
		$downloadLinkExpected = 'https://github.com/codekaizen-github/wp-package-meta-provider-local';
		$testedExpected       = '6.8.2';
		$tagsExpected         = [ 'tag1', 'tag2', 'tag3' ];
		$sectionsExpected     = [
			'changelog' => 'changed',
			'about'     => 'this is a plugin about section',
		];
		$provider             = Mockery::mock( PluginPackageMetaProviderContract::class );
		$provider->shouldReceive( 'getName' )->with()->andReturn( $nameExpected );
		$provider->shouldReceive( 'getShortSlug' )->with()->andReturn( $slugExpected );
		$provider->shouldReceive( 'getVersion' )->with()->andReturn( $versionExpected );
		$provider->shouldReceive( 'getAuthor' )->with()->andReturn( $authorExpected );
		$provider->shouldReceive( 'getRequiresWordPressVersion' )->with()->andReturn( $requiresExpected );
		$provider->shouldReceive( 'getTested' )->with()->andReturn( $testedExpected );
		$provider->shouldReceive( 'getRequiresPHPVersion' )->with()->andReturn( $requiresPhpExpected );
		$provider->shouldReceive( 'getViewURL' )->with()->andReturn( $homepageExpected );
		$provider->shouldReceive( 'getDownloadURL' )->with()->andReturn( $downloadLinkExpected );
		$provider->shouldReceive( 'getSections' )->with()->andReturn( $sectionsExpected );
		$provider->shouldReceive( 'getTags' )->with()->andReturn( $tagsExpected );
		$sut              = ( new PluginCheckInfoFormatter( $provider ) );
		$actualMetaObject = $sut->formatForCheckInfo();
		$this->assertInstanceOf( PluginCheckInfoMetaObject::class, $actualMetaObject );
	}
}
