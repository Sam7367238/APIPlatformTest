<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\CreateProvider;
use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ApiResource(
    uriTemplate: "/blog_posts/{blogPostId}/comments/{id}",
    operations: [
        new Patch(),
        new Delete(),
    ],
    uriVariables: [
        "blogPostId" => new Link(toProperty: "blogPost", fromClass: BlogPost::class),
        "id" => new Link(fromClass: Comment::class),
    ],
    denormalizationContext: ["groups" => "no-blog-post"],
    security: "is_granted('ROLE_USER')"
)]
#[ApiResource(
    uriTemplate: "/blog_posts/{blogPostId}/comments",
    operations: [
        new GetCollection(),
        new Post(denormalizationContext: ["groups" => "no-blog-post"], provider: CreateProvider::class)
    ],
    uriVariables: [
        "blogPostId" => new Link(toProperty: "blogPost", fromClass: BlogPost::class),
    ],
    security: "is_granted('ROLE_USER')"
)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Groups("no-blog-post")]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?BlogPost $blogPost = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getBlogPost(): ?BlogPost
    {
        return $this->blogPost;
    }

    public function setBlogPost(?BlogPost $blogPost): static
    {
        $this->blogPost = $blogPost;

        return $this;
    }
}
