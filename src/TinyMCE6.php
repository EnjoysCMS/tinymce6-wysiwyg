<?php


namespace EnjoysCMS\WYSIWYG\TinyMCE6;

use EnjoysCMS\Core\Components\Helpers\Assets;
use EnjoysCMS\Core\Components\WYSIWYG\WysiwygInterface;

class TinyMCE6 implements WysiwygInterface
{
    private ?string $twigTemplate = null;

    public function __construct()
    {
        $this->initialize();
    }

    public function setTwigTemplate(?string $twigTemplate): void
    {
        $this->twigTemplate = $twigTemplate;
    }

    public function getTwigTemplate(): string
    {
        return $this->twigTemplate  ?? '@wysisyg/tinymce6/template/basic.twig';
    }

    private function initialize()
    {
        $path = str_replace(realpath($_ENV['PROJECT_DIR']), '', realpath(__DIR__.'/../'));
        Assets::createSymlink(sprintf('%s/assets%s/node_modules/tinymce', $_ENV['PUBLIC_DIR'], $path), __DIR__ . '/../node_modules/tinymce');
        Assets::createSymlink(sprintf('%s/assets%s/node_modules/tinymce/langs', $_ENV['PUBLIC_DIR'], $path), __DIR__ . '/langs');
        Assets::js(__DIR__ . '/../node_modules/tinymce/tinymce.min.js');
    }


}
