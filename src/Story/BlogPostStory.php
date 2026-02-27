<?php

namespace App\Story;

use App\Factory\BlogPostFactory;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

#[AsFixture(name: "blogPost")]
final class BlogPostStory extends Story
{
    public function build(): void
    {
        BlogPostFactory::createMany(5);
    }
}
