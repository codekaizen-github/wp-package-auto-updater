<?php
/**
 * Check Update Provider
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Provider\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Provider\PackageMeta\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Contract\Provider\PackageMeta\CheckUpdate\CheckUpdatePackageMetaValueContract;
use CodeKaizen\WPPackageAutoUpdater\Validator\MetaObject\CheckUpdate\CheckUpdateMetaObjectValidator;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator;
use stdClass;
use UnexpectedValueException;

/**
 * Undocumented class
 */
class CheckUpdatePackageMetaProvider implements CheckUpdatePackageMetaValueContract {
	/**
	 * Undocumented variable
	 *
	 * @var stdClass
	 */
	protected stdClass $data;
	/**
	 * Undocumented function
	 *
	 * @param stdClass $data Data.
	 * @throws UnexpectedValueException If validation fails.
	 */
	public function __construct( stdClass $data ) {
		try {
			Validator::create( new CheckUpdateMetaObjectValidator() )->check( $data );
		} catch ( ValidationException $e ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new UnexpectedValueException( $e->getMessage(), 0, $e );
		}
		$this->data = $data;
	}
	/**
	 * Undocumented function
	 *
	 * @return string
	 */
	public function getFullSlug(): string {
		$value = $this->data->id;
		/**
		 * Validated
		 *
		 * @var string $value
		 */
		return $value;
	}
	/**
	 * Undocumented function
	 *
	 * @return string
	 */
	public function getShortSlug(): string {
		$value = $this->data->slug;
		/**
		 * Validated
		 *
		 * @var string $value
		 */
		return $value;
	}
	/**
	 * Undocumented function
	 *
	 * @return string|null
	 */
	public function getVersion(): ?string {
		$value = $this->data->new_version;
		/**
		 * Validated
		 *
		 * @var ?string $value
		 */
		return $value;
	}
	/**
	 * Undocumented function
	 *
	 * @return string|null
	 */
	public function getViewURL(): ?string {
		$value = $this->data->url;
		/**
		 * Validated
		 *
		 * @var ?string $value
		 */
		return $value;
	}
	/**
	 * Undocumented function
	 *
	 * @return string|null
	 */
	public function getDownloadURL(): ?string {
		$value = $this->data->package;
		/**
		 * Validated
		 *
		 * @var ?string $value
		 */
		return $value;
	}
	/**
	 * Undocumented function
	 *
	 * @return array<string,string>
	 */
	public function getIcons(): array {
		$value = $this->data->icons ?? [];
		/**
		 * Validated
		 *
		 * @var array<string,string> $value
		 */
		return $value;
	}
	/**
	 * Undocumented function
	 *
	 * @return array<string,string>
	 */
	public function getBanners(): array {
		$value = $this->data->banners ?? [];
		/**
		 * Validated
		 *
		 * @var array<string,string> $value
		 */
		return $value;
	}
	/**
	 * Undocumented function
	 *
	 * @return array<string,string>
	 */
	public function getBannersRTL(): array {
		$value = $this->data->banners_rtl ?? [];
		/**
		 * Validated
		 *
		 * @var array<string,string> $value
		 */
		return $value;
	}
	/**
	 * Undocumented function
	 *
	 * @return string|null
	 */
	public function getTested(): ?string {
		$value = $this->data->tested;
		/**
		 * Validated
		 *
		 * @var ?string $value
		 */
		return $value;
	}
	/**
	 * Undocumented function
	 *
	 * @return string|null
	 */
	public function getRequiresWordPressVersion(): ?string {
		$value = $this->data->requires;
		/**
		 * Validated
		 *
		 * @var ?string $value
		 */
		return $value;
	}
	/**
	 * Undocumented function
	 *
	 * @return string|null
	 */
	public function getRequiresPHPVersion(): ?string {
		$value = $this->data->requires_php;
		/**
		 * Validated
		 *
		 * @var ?string $value
		 */
		return $value;
	}
}
