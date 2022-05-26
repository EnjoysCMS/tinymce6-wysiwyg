<?php

declare(strict_types=1);

namespace Tests\EnjoysCMS\WYSIWYG\TinyMCE6;

use DI\Container;
use DI\Definition\Source\DefinitionArray;
use Enjoys\AssetsCollector\Assets;
use Enjoys\AssetsCollector\Environment;
use EnjoysCMS\Core\Components\Helpers;
use EnjoysCMS\WYSIWYG\TinyMCE6\TinyMCE6;
use Exception;
use FilesystemIterator;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class WysiwygTest extends TestCase
{

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $_ENV['PROJECT_DIR'] = __DIR__.'/_compile';
        $_ENV['PUBLIC_DIR'] = __DIR__.'/_compile';

        $DI = new Container(
            new DefinitionArray([
                Assets::class => function () {
                    $env = new Environment('_compile');
                    $env->setBaseUrl('_compile');
                    return new Assets($env);
                },
                LoggerInterface::class => function(){
                    return new TestLogger();
                }
            ])
        );

        Helpers\Assets::setContainer($DI);
        Helpers\Assets::setContainer($DI);
    }

    protected function tearDown(): void
    {
        $this->removeDirectoryRecursive(__DIR__ . '/_compile', true);
    }

    public function testSetTwigTemplate()
    {
        $tinymce = new TinyMCE6();
        $tinymce->setTwigTemplate('test');
        $this->assertSame('test', $tinymce->getTwigTemplate());
        $tinymce->setTwigTemplate(null);
        $this->assertSame('@wysisyg/tinymce6/template/basic.twig', $tinymce->getTwigTemplate());
    }


    public function testCheckAssets()
    {
        $assets = Helpers\Assets::getContainer()->get(Assets::class);
        new TinyMCE6();
        $this->assertStringContainsString('node_modules/tinymce/tinymce.min.js', $assets->get('js'));
    }

    private function removeDirectoryRecursive($path, $removeParent = false)
    {
        if (!file_exists($path)) {
            return;
        }
        $di = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

        /** @var SplFileInfo $file */
        foreach ($ri as $file) {
            if ($file->isLink()) {
                $symlink = realpath($file->getPath()) . DIRECTORY_SEPARATOR . $file->getFilename();
                if (PHP_OS_FAMILY == 'Windows') {
                    (is_dir($symlink)) ? rmdir($symlink) : unlink($symlink);
                } else {
                    unlink($symlink);
                }
                continue;
            }
            $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
        }
        if ($removeParent) {
            rmdir($path);
        }
    }
}
