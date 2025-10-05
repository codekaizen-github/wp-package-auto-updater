<?php

namespace CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckInfo\CheckInfoFormatterContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaContract;
use stdClass;

class CheckInfoFormatterPlugin implements CheckInfoFormatterContract
{
    private PluginPackageMetaContract $provider;
    public function __construct(PluginPackageMetaContract $provider)
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
        $stdObj->sections = $this->provider->getSections();
        $stdObj->tags = $this->provider->getTags();
        // WordPress expects these properties for plugin information
        $stdObj->external = true; // indicates this is an external package
        return $stdObj;
    }
}
