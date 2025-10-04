<?php

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckInfo;

interface CheckInfoFormatterContract
{
    public function formatMetaForCheckInfo(): object;
}
