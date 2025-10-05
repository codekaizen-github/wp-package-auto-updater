<?php

namespace CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckInfo\CheckInfoFormatterContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaContract;
use stdClass;

class CheckInfoFormatterTheme implements CheckInfoFormatterContract
{
    private ThemePackageMetaContract $provider;
    public function __construct(ThemePackageMetaContract $provider)
    {
        $this->provider = $provider;
    }
    public function formatForCheckInfo(): object
    {
        $stdObj = new stdClass();
        $stdObj->name = $this->provider->getName();
        $stdObj->slug = $this->provider->getShortSlug();
        $stdObj->version = $this->provider->getVersion();
        $stdObj->author = $this->provider->getAuthor();
        // $stdObj->author_profile
        $stdObj->requires = $this->provider->getRequiresWordPressVersion();
        $stdObj->tested = $this->provider->getTested();
        $stdObj->requires_php = $this->provider->getRequiresPHPVersion();
        $stdObj->homepage = $this->provider->getViewURL();
        $stdObj->download_link = $this->provider->getDownloadURL();
        $stdObj->update_uri = $this->provider->getDownloadURL();
        // $stdObj->last_updated
        $stdObj->tags = $this->provider->getTags();
        return $stdObj;
    }
}
