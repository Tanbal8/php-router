<?php

namespace Tanbal\PhpRouter;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use FilesystemIterator;

class InstallerPlugin implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        $source = dirname(__DIR__) . '/stubs/backend';
        $destination = getcwd() . '/backend';

        if (!is_dir($source)) {
            $io->writeError('Backend stubs directory not found.');
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $source,
                FilesystemIterator::SKIP_DOTS
            ),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->getFilename() === '.gitkeep') {
                continue;
            }

            $relativePath = $iterator->getSubPathName();
            $target = $destination . '/' . $relativePath;

            if ($item->isDir()) {
                if (!is_dir($target)) {
                    mkdir($target, 0755, true);
                }
            } else {
                if (!file_exists($target)) {
                    if (!is_dir(dirname($target))) {
                        mkdir(dirname($target), 0755, true);
                    }

                    copy($item->getPathname(), $target);
                    $io->write("Created: backend/" . $relativePath);
                } else {
                    if ($io->askConfirmation(
                        "File backend/" . $relativePath . " already exists. Overwrite? [y/N] ",
                        false
                    )) {
                        copy($item->getPathname(), $target);
                        $io->write("Updated: backend/" . $relativePath);
                    } else {
                        $io->write("Skipped: backend/" . $relativePath);
                    }
                }
            }
        }

        $io->write('Tanbal PHP Router installed.');
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
    }
}
