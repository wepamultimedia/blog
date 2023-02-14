<?php

namespace Wepa\Blog\Commands;

use Wepa\Core\Commands\BaseSymlinkCommand;

class BlogSymLinkCommand extends BaseSymlinkCommand
{
    protected string $package = 'blog';

    protected $signature = 'blog:sl';
}
