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
				new Rules\Attribute( 'id', new Rules\StringType(), true ),
				new Rules\Attribute( 'slug', new Rules\StringType(), true ),
				new Rules\Attribute( 'new_version', new FlexibleSemanticVersionRule(), false ),
				new Rules\Attribute( 'url', new Rules\Url(), false ),
				new Rules\Attribute( 'package', new Rules\Url(), false ),
				new Rules\Attribute(
					'icons',
					new Rules\AllOf(
						new Rules\ArrayType(),
						new Rules\Each( new Rules\Url() ),
						new Rules\Call( 'array_keys', new Rules\Each( new Rules\StringType() ) ),
					),
					false
				),
				new Rules\Attribute(
					'banners',
					new Rules\AllOf(
						new Rules\ArrayType(),
						new Rules\Each( new Rules\Url() ),
						new Rules\Call( 'array_keys', new Rules\Each( new Rules\StringType() ) ),
					),
					false
				),
				new Rules\Attribute(
					'banners_rtl',
					new Rules\AllOf(
						new Rules\ArrayType(),
						new Rules\Each( new Rules\Url() ),
						new Rules\Call( 'array_keys', new Rules\Each( new Rules\StringType() ) ),
					),
					false
				),
				new Rules\Attribute( 'tested', new FlexibleSemanticVersionRule(), false ),
				new Rules\Attribute( 'requires', new FlexibleSemanticVersionRule(), false ),
				new Rules\Attribute( 'requires_php', new FlexibleSemanticVersionRule(), false )
			)
		)->isValid( $input );
	}
}
