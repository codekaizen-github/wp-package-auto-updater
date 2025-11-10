<?php
/**
 * Check Update Meta Object Validator
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Validator\MetaObject
 */

namespace CodeKaizen\WPPackageAutoUpdater\Validator\MetaObject\CheckUpdate;

use CodeKaizen\WPPackageMetaProviderLocal\Validator\Rule\Version\FlexibleSemanticVersionRule;
use Respect\Validation\Rules;
use Respect\Validation\Rules\Core\Simple;
use Respect\Validation\Validator;

/**
 * Validates CheckUpdateMetaObject instances
 *
 * @since 1.0.0
 */
class CheckUpdateMetaObjectValidator extends Simple {

	/**
	 * Validates a CheckUpdateMetaObject instance
	 *
	 * @param mixed $input The object to validate.
	 * @return bool True if validation passes, false otherwise.
	 */
	public function isValid( mixed $input ): bool {
		return Validator::create(
			new Rules\AllOf(
				new Rules\ObjectType(),
				new Rules\Key( 'id', new Rules\StringType(), true ),
				new Rules\Key( 'slug', new Rules\StringType(), true ),
				new Rules\Key( 'new_version', new FlexibleSemanticVersionRule(), false ),
				new Rules\Key( 'url', new Rules\Url(), false ),
				new Rules\Key( 'package', new Rules\Url(), false ),
				new Rules\Key(
					'icons',
					new Rules\AllOf(
						new Rules\ArrayType(),
						new Rules\Each( new Rules\Url() ),
						new Rules\Call( 'array_keys', new Rules\Each( new Rules\StringType() ) ),
					),
					false
				),
				new Rules\Key(
					'banners',
					new Rules\AllOf(
						new Rules\ArrayType(),
						new Rules\Each( new Rules\Url() ),
						new Rules\Call( 'array_keys', new Rules\Each( new Rules\StringType() ) ),
					),
					false
				),
				new Rules\Key(
					'banners_rtl',
					new Rules\AllOf(
						new Rules\ArrayType(),
						new Rules\Each( new Rules\Url() ),
						new Rules\Call( 'array_keys', new Rules\Each( new Rules\StringType() ) ),
					),
					false
				),
				new Rules\Key( 'tested', new FlexibleSemanticVersionRule(), false ),
				new Rules\Key( 'requires', new FlexibleSemanticVersionRule(), false ),
				new Rules\Key( 'requires_php', new FlexibleSemanticVersionRule(), false )
			)
		)->isValid( $input );
	}
}
