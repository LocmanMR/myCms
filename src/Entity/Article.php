<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @Assert\EnableAutoMapping()
 */
class Article
{
    use TimestampableEntity;

    private const MIN_TITLE_LEN = 3;
    private const MAX_DESCRIPTION_LEN = 100;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("base")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Title not be empty")
     * @Groups("base")
     */
    private ?string $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\DisableAutoMapping()
     * @Groups("base")
     */
    private string $slug;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups("base")
     */
    private string $description;

    /**
     * @ORM\Column(type="text")
     * @Groups("base")
     */
    private string $body;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("base")
     */
    private ?string $keywords;

    /**
     * @var int|string|null
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("base")
     */
    private $voteCount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("base")
     */
    private ?string $imageFilename;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("base")
     */
    private ?DateTimeInterface $publishedAt;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="article", fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="articles")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @Assert\DisableAutoMapping()
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     * @Assert\DisableAutoMapping()
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getVoteCount(): ?int
    {
        return $this->voteCount;
    }

    /**
     * @param int|string $voteCount
     * @return $this
     */
    public function setVoteCount($voteCount): self
    {
        $this->voteCount = $voteCount;

        return $this;
    }

    public function setVote(): self
    {
        $this->voteCount++;
        return $this;
    }

    public function pickUpVote(): self
    {
        $this->voteCount--;
        return $this;
    }

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(?string $imageFilename): self
    {
        $this->imageFilename = $imageFilename;

        return $this;
    }

    public function getPublishedAt(): ?DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getImagePath(): string
    {
        return 'images/' . $this->getImageFilename();
    }

    public function getAuthorAvatarPath(): string
    {
        return sprintf(
            'https://robohash.org/%s.png?set=set4',
            str_replace(' ', '_', $this->getAuthor())
        );
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getNotDeletedComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        // set the owning side to null (unless already changed)
        if ($this->comments->removeElement($comment) && $comment->getArticle() === $this) {
            $comment->setArticle(null);
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function isPublished(): bool
    {
        return null !== $this->getPublishedAt();
    }

    /**
     * @Assert\Callback()
     * @param ExecutionContextInterface $context
     * @param $payload
     */
    public function validate(ExecutionContextInterface $context, $payload): void
    {
        if (!preg_match('/^\D+$/', $this->getTitle())) {
            $context->buildViolation('Cannot use numbers in the title')
                ->atPath('title')
                ->addViolation()
            ;
        }

        if (mb_stripos($this->getTitle(), 'tea') !== false) {
            $context->buildViolation('This blog just about coffee and developing!')
                ->atPath('title')
                ->addViolation()
            ;
        }

        if (strlen($this->getTitle()) <= self::MIN_TITLE_LEN) {
            $context->buildViolation('Title must be longer')
                ->atPath('title')
                ->addViolation()
            ;
        }

        if (strlen($this->getDescription()) >= self::MAX_DESCRIPTION_LEN) {
            $context->buildViolation('Description must be shorter 100 symbols')
                ->atPath('description')
                ->addViolation()
            ;
        }
    }
}
