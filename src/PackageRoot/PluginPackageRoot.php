<?php

namespace CodeKaizen\WPPackageAutoUpdater\PackageRoot;

use CodeKaizen\WPPackageAutoUpdater\Contract\PackageRoot\PackageRootContract;

class PluginPackageRoot implements PackageRootContract {

	public function getPackageRoot(): string {
		return WP_PLUGIN_DIR;
	}
}
