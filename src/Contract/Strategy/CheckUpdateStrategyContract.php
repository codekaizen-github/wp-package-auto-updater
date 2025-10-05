<?php

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Strategy;

interface CheckUpdateStrategyContract {

	public function checkUpdate( object $transient ): object;
}
