<?php

namespace CodeKaizen\WPPackageAutoUpdater\Parser\Slug;

use CodeKaizen\WPPackageAutoUpdater\Contract\PackageRoot\PackageRootContract;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\Parser\SlugParserContract;
use UnexpectedValueException;

class PluginSlugParser implements SlugParserContract
{
    protected PackageRootContract $packageRoot;
    protected string $filePath;
    protected ?string $shortSlug;
    protected ?string $fullSlug;
    public function __construct(string $filePath, PackageRootContract $packageRoot)
    {
        $this->filePath = $filePath;
        $this->packageRoot = $packageRoot;
        $this->shortSlug = null;
        $this->fullSlug = null;
    }
    public function getShortSlug(): string
    {
        if (null === $this->shortSlug) {
            // my-plugin/my-plugin.php -> my-plugin
            // my-plugin.php -> my-plugin.php
            $split = explode('/', $this->getFullSlug());
            if (count($split) < 1) {
                throw new UnexpectedValueException("Expected split slug array to be of length 1 or more");
            }
            $this->shortSlug = array_shift($split);
        }
        return $this->shortSlug;
    }
    public function getFullSlug(): string
    {
        if (null === $this->fullSlug) {

            $plugin_root = $this->packageRoot->getPackageRoot();
            $dir_entries = scandir($plugin_root);

            $real_path_file = realpath($this->filePath);
            $real_path_file_basename = basename($real_path_file);
            // Resolve the real path of this plugin’s directory
            $real_path_dir = dirname($real_path_file);

            $slug = null;

            foreach ($dir_entries as $entry) {
                if ($entry === '.' || $entry === '..') {
                    continue;
                }

                $candidate = $plugin_root . '/' . $entry;

                // check directory plugins
                if (is_dir($candidate)) {
                    if (realpath($candidate) === $real_path_dir) {
                        $slug = $entry . '/' . $real_path_file_basename;
                        break;
                    }
                }

                // check single-file plugins
                if (is_file($candidate)) {
                    if (realpath($candidate) === $real_path_file) {
                        $slug = $real_path_file_basename;
                        break;
                    }
                }
            }
            if (!is_string($slug)) {
                throw new UnexpectedValueException("Expected slug to be string. Got $slug instead.");
            }
            $this->fullSlug = $slug;
        }
        return $this->fullSlug;
    }
}
