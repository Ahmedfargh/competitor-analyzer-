<?php

namespace App\Livewire\Admin\Posts;

use App\Models\Post;
use Illuminate\Support\Str;
use Livewire\Component;
use Modules\Admin\DTOs\Post\PostDTO;
use Modules\Admin\Services\Contracts\PostServiceInterface;

class PostBlockEditor extends Component
{
    public ?int $postId = null;

    public string $type = 'post'; // 'post' or 'landing_page'

    public string $status = 'draft'; // 'draft' or 'published'

    public string $locale = 'en'; // Current editing locale: 'en' or 'ar'

    public array $title = [
        'en' => '',
        'ar' => '',
    ];

    public string $slug = '';

    public array $excerpt = [
        'en' => '',
        'ar' => '',
    ];

    public ?string $featuredImage = null;

    public array $blocks = [];

    public array $seoMeta = [
        'meta_title' => '',
        'meta_description' => '',
    ];

    public ?string $tenantId = null;

    public bool $previewMode = false;

    public bool $showBlockPicker = false;

    public ?int $insertAtIndex = null;

    public string $blockSearch = '';

    public string $sidebarTab = 'document'; // 'document' or 'block'

    public ?int $selectedBlockIndex = null;

    protected function rules(): array
    {
        return [
            'title.en' => 'required_without:title.ar|string|max:255',
            'slug' => 'required|string|max:255',
            'type' => 'required|in:post,landing_page',
            'status' => 'required|in:draft,published',
            'blocks' => 'array',
        ];
    }

    public function mount(?int $postId = null, string $type = 'post', ?PostServiceInterface $postService = null): void
    {
        $this->postId = $postId;
        $this->type = $type;

        if ($this->postId) {
            $post = app(PostServiceInterface::class)->getPostById($this->postId);
            if ($post) {
                $this->type = $post->type;
                $this->status = $post->status;
                $this->slug = $post->slug;
                $this->featuredImage = $post->featured_image;
                $this->blocks = is_array($post->blocks) ? $post->blocks : [];
                $this->seoMeta = is_array($post->seo_meta) ? $post->seo_meta : ['meta_title' => '', 'meta_description' => ''];
                $this->tenantId = $post->tenant_id;

                $this->title = [
                    'en' => $post->getTranslation('title', 'en', false) ?: '',
                    'ar' => $post->getTranslation('title', 'ar', false) ?: '',
                ];

                $this->excerpt = [
                    'en' => $post->getTranslation('excerpt', 'en', false) ?: '',
                    'ar' => $post->getTranslation('excerpt', 'ar', false) ?: '',
                ];
            }
        } else {
            // Seed initial blocks matching the type
            $this->seedInitialBlocks();
        }
    }

    protected function seedInitialBlocks(): void
    {
        if ($this->type === 'landing_page') {
            $this->blocks = [
                $this->createBlockSchema('hero'),
                $this->createBlockSchema('features_grid'),
                $this->createBlockSchema('cta_banner'),
                $this->createBlockSchema('faq'),
            ];
        } else {
            $this->blocks = [
                $this->createBlockSchema('heading'),
                $this->createBlockSchema('paragraph'),
                $this->createBlockSchema('image'),
                $this->createBlockSchema('paragraph'),
            ];
        }
    }

    public function updatedTitleEn(string $value): void
    {
        if (empty($this->slug) && ! empty($value)) {
            $this->slug = Str::slug($value);
        }
    }

    public function generateSlug(): void
    {
        $source = ! empty($this->title['en']) ? $this->title['en'] : ($this->title['ar'] ?? '');
        $this->slug = Str::slug($source) ?: 'page-'.uniqid();
    }

    public function openBlockPicker(?int $atIndex = null): void
    {
        $this->insertAtIndex = $atIndex;
        $this->blockSearch = '';
        $this->showBlockPicker = true;
    }

    public function closeBlockPicker(): void
    {
        $this->showBlockPicker = false;
        $this->insertAtIndex = null;
    }

    public function selectBlock(string $type): void
    {
        $newBlock = $this->createBlockSchema($type);

        if ($this->insertAtIndex !== null && isset($this->blocks[$this->insertAtIndex])) {
            array_splice($this->blocks, $this->insertAtIndex + 1, 0, [$newBlock]);
            $this->selectedBlockIndex = $this->insertAtIndex + 1;
        } else {
            $this->blocks[] = $newBlock;
            $this->selectedBlockIndex = count($this->blocks) - 1;
        }

        $this->closeBlockPicker();
    }

    public function removeBlock(int $index): void
    {
        if (isset($this->blocks[$index])) {
            array_splice($this->blocks, $index, 1);
            if ($this->selectedBlockIndex === $index) {
                $this->selectedBlockIndex = null;
            } elseif ($this->selectedBlockIndex > $index) {
                $this->selectedBlockIndex--;
            }
        }
    }

    public function duplicateBlock(int $index): void
    {
        if (isset($this->blocks[$index])) {
            $clone = $this->blocks[$index];
            $clone['id'] = 'blk_'.uniqid();
            array_splice($this->blocks, $index + 1, 0, [$clone]);
            $this->selectedBlockIndex = $index + 1;
        }
    }

    public function moveBlockUp(int $index): void
    {
        if ($index > 0 && isset($this->blocks[$index])) {
            $temp = $this->blocks[$index - 1];
            $this->blocks[$index - 1] = $this->blocks[$index];
            $this->blocks[$index] = $temp;
            $this->selectedBlockIndex = $index - 1;
        }
    }

    public function moveBlockDown(int $index): void
    {
        if ($index < count($this->blocks) - 1 && isset($this->blocks[$index])) {
            $temp = $this->blocks[$index + 1];
            $this->blocks[$index + 1] = $this->blocks[$index];
            $this->blocks[$index] = $temp;
            $this->selectedBlockIndex = $index + 1;
        }
    }

    public function addFeatureItem(int $blockIndex): void
    {
        if (isset($this->blocks[$blockIndex]['content']['items'])) {
            $this->blocks[$blockIndex]['content']['items'][] = [
                'icon' => 'sparkles',
                'title' => 'New Feature',
                'description' => 'Feature description highlighting competitive edge.',
            ];
        }
    }

    public function removeFeatureItem(int $blockIndex, int $itemIndex): void
    {
        if (isset($this->blocks[$blockIndex]['content']['items'][$itemIndex])) {
            array_splice($this->blocks[$blockIndex]['content']['items'], $itemIndex, 1);
        }
    }

    public function addFaqItem(int $blockIndex): void
    {
        if (isset($this->blocks[$blockIndex]['content']['items'])) {
            $this->blocks[$blockIndex]['content']['items'][] = [
                'question' => 'How does the platform work?',
                'answer' => 'It automatically scrapes, summarizes, and tracks competitor movements using Gemini AI.',
            ];
        }
    }

    public function removeFaqItem(int $blockIndex, int $itemIndex): void
    {
        if (isset($this->blocks[$blockIndex]['content']['items'][$itemIndex])) {
            array_splice($this->blocks[$blockIndex]['content']['items'], $itemIndex, 1);
        }
    }

    public function togglePreview(): void
    {
        $this->previewMode = ! $this->previewMode;
    }

    public function save(bool $publish = false, ?PostServiceInterface $postService = null): void
    {
        $this->validate();

        $postService = $postService ?? app(PostServiceInterface::class);

        if ($publish) {
            $this->status = 'published';
        }

        $dto = new PostDTO(
            title: $this->title,
            slug: $this->slug,
            type: $this->type,
            status: $this->status,
            excerpt: $this->excerpt,
            featuredImage: $this->featuredImage,
            blocks: $this->blocks,
            seoMeta: $this->seoMeta,
            publishedAt: $this->status === 'published' ? now()->toDateTimeString() : null,
            authorId: auth('admin')->id(),
            tenantId: $this->tenantId
        );

        if ($this->postId) {
            $post = $postService->getPostById($this->postId);
            if ($post) {
                $postService->updatePost($post, $dto);
                session()->flash('success', "{$this->type} updated successfully.");
            }
        } else {
            $post = $postService->createPost($dto);
            $this->postId = $post->id;
            session()->flash('success', "{$this->type} created successfully.");
        }
    }

    protected function createBlockSchema(string $type): array
    {
        $id = 'blk_'.uniqid();

        return match ($type) {
            'hero' => [
                'id' => $id,
                'type' => 'hero',
                'content' => [
                    'badge' => 'AI-POWERED INTELLIGENCE',
                    'headline' => 'Monitor Competitors at the Speed of AI',
                    'subtitle' => 'Automate competitor scraping, price tracking, and market intelligence reports in real-time.',
                    'cta_primary_text' => 'Get Started Free',
                    'cta_primary_url' => '/register',
                    'cta_secondary_text' => 'Watch Interactive Demo',
                    'cta_secondary_url' => '#demo',
                    'theme' => 'orange-glow',
                    'align' => 'center',
                ],
            ],
            'heading' => [
                'id' => $id,
                'type' => 'heading',
                'content' => [
                    'level' => 'h2',
                    'text' => 'Empowering Strategy with Real-Time Data',
                    'align' => 'left',
                ],
            ],
            'paragraph' => [
                'id' => $id,
                'type' => 'paragraph',
                'content' => [
                    'text' => 'In today fast-paced market, staying ahead of competition requires immediate insights rather than retrospective reports. Our engine scans web presence, price fluctuations, and marketing shifts seamlessly.',
                    'align' => 'left',
                ],
            ],
            'image' => [
                'id' => $id,
                'type' => 'image',
                'content' => [
                    'url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1200&q=80',
                    'caption' => 'Real-time competitive dashboard analytics feed',
                    'alt' => 'Analytics visual graph',
                    'aspect_ratio' => '16:9',
                ],
            ],
            'features_grid' => [
                'id' => $id,
                'type' => 'features_grid',
                'content' => [
                    'headline' => 'Engineered for Market Leadership',
                    'columns' => 3,
                    'items' => [
                        [
                            'icon' => 'radar',
                            'title' => 'Automated Web Scrapes',
                            'description' => 'Daily automated tracking of pricing, landing pages, and marketing campaigns.',
                        ],
                        [
                            'icon' => 'sparkles',
                            'title' => 'Gemini AI Summaries',
                            'description' => 'Synthesize vast competitive information into executive bullet-point takeaways.',
                        ],
                        [
                            'icon' => 'shield',
                            'title' => 'Tenant Isolated Vault',
                            'description' => 'Database-per-tenant isolation ensuring proprietary market strategies remain private.',
                        ],
                    ],
                ],
            ],
            'cta_banner' => [
                'id' => $id,
                'type' => 'cta_banner',
                'content' => [
                    'headline' => 'Ready to Outmaneuver the Competition?',
                    'description' => 'Join forward-thinking growth teams tracking market movements with autonomous AI agents.',
                    'button_text' => 'Launch Your Free Workspace',
                    'button_url' => '/register',
                    'style' => 'gradient',
                ],
            ],
            'faq' => [
                'id' => $id,
                'type' => 'faq',
                'content' => [
                    'headline' => 'Frequently Asked Questions',
                    'items' => [
                        [
                            'question' => 'How frequently are competitor sites analyzed?',
                            'answer' => 'Scrapes run on daily, hourly, or on-demand schedules depending on your subscription plan.',
                        ],
                        [
                            'question' => 'Can I customize reports for my executive team?',
                            'answer' => 'Yes, export tailored PDF/Excel digests or automate Webhook alerts directly into Slack.',
                        ],
                    ],
                ],
            ],
            'quote' => [
                'id' => $id,
                'type' => 'quote',
                'content' => [
                    'quote' => 'This intelligence engine shifted our marketing agility from reactive scramble to proactive supremacy.',
                    'author' => 'Sarah Lin',
                    'citation' => 'Head of Strategy, Apex Retail',
                ],
            ],
            'code' => [
                'id' => $id,
                'type' => 'code',
                'content' => [
                    'language' => 'bash',
                    'code' => 'curl -X POST https://api.compitator.com/v1/analyze \\'."\n".'  -H "Authorization: Bearer $KEY" \\'."\n".'  -d \'{"target": "rival.com"}\'',
                ],
            ],
            default => [
                'id' => $id,
                'type' => 'paragraph',
                'content' => [
                    'text' => 'Content goes here...',
                ],
            ],
        };
    }

    public function render()
    {
        return view('livewire.admin.posts.post-block-editor')
            ->layout('admin::components.layouts.master', [
                'title' => ($this->postId ? 'Edit ' : 'Create ').ucwords(str_replace('_', ' ', $this->type)),
            ]);
    }
}
