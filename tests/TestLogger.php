<?php

declare(strict_types=1);

namespace Tests\EnjoysCMS\WYSIWYG\TinyMCE6;

final class TestLogger extends \Psr\Log\NullLogger
{
    public function withName(?string $name)
    {
        return $this;
    }
}
