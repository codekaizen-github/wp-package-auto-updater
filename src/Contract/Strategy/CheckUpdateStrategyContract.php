<?php

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Strategy;

use stdClass;

interface CheckUpdateStrategyContract {

	public function checkUpdate( stdClass $transient ): stdClass;
}
