<?php

namespace CodeKaizen\WPPackageAutoUpdater\PackageRoot;

use CodeKaizen\WPPackageAutoUpdater\Contract\PackageRoot\PackageRootContract;

class ThemePackageRoot implements PackageRootContract {

	public function getPackageRoot(): string {
		return get_theme_root();
	}
}
