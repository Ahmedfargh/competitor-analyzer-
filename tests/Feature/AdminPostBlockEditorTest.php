<?php

namespace Tests\Feature;

use App\Livewire\Admin\Posts\PostBlockEditor;
use App\Livewire\Admin\Posts\PostManager;
use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Modules\Admin\DTOs\Post\PostDTO;
use Modules\Admin\Models\Admin;
use Modules\Admin\Services\Contracts\PostServiceInterface;
use Tests\TestCase;

class AdminPostBlockEditorTest extends TestCase
{
    use DatabaseTransactions;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            LaravelLocalizationRedirectFilter::class,
            LocaleSessionRedirect::class,
        ]);

        $this->admin = Admin::firstOrCreate(
            ['email' => 'post-editor-admin@compitator.com'],
            [
                'name' => 'Editor Admin',
                'password' => bcrypt('password'),
            ]
        );
    }

    public function test_guest_cannot_access_admin_posts(): void
    {
        $this->get('/admin/posts')->assertRedirect(route('admin.login'));
        $this->get('/admin/posts/create')->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_posts_index_and_editor(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.posts.index'))
            ->assertStatus(200)
            ->assertSee('Landing');

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.posts.create', ['type' => 'landing_page']))
            ->assertStatus(200)
            ->assertSee('Gutenberg');
    }

    public function test_post_manager_livewire_lists_and_filters_posts(): void
    {
        $postService = app(PostServiceInterface::class);
        $slug = 'test-lp-'.uniqid();

        $dto = new PostDTO(
            title: ['en' => 'AI Intelligence Platform', 'ar' => 'منصة الذكاء التنافسي'],
            slug: $slug,
            type: 'landing_page',
            status: 'published',
            blocks: [
                [
                    'id' => 'blk_1',
                    'type' => 'hero',
                    'content' => ['headline' => 'AI Intelligence Platform'],
                ],
            ],
            authorId: $this->admin->id
        );

        $postService->createPost($dto);

        Livewire::actingAs($this->admin, 'admin')
            ->test(PostManager::class)
            ->assertSee('AI Intelligence Platform')
            ->assertSee($slug)
            ->set('typeFilter', 'landing_page')
            ->assertSee($slug)
            ->set('typeFilter', 'post')
            ->assertDontSee($slug);
    }

    public function test_post_manager_livewire_can_duplicate_toggle_and_delete_post(): void
    {
        $postService = app(PostServiceInterface::class);
        $slug = 'test-dup-'.uniqid();

        $dto = new PostDTO(
            title: ['en' => 'Original Post', 'ar' => 'المقال الأصلي'],
            slug: $slug,
            type: 'post',
            status: 'draft',
            blocks: [],
            authorId: $this->admin->id
        );

        $post = $postService->createPost($dto);

        // Toggle publish
        Livewire::actingAs($this->admin, 'admin')
            ->test(PostManager::class)
            ->call('togglePublish', $post->id);

        $this->assertSame('published', $post->fresh()->status);

        // Duplicate
        Livewire::actingAs($this->admin, 'admin')
            ->test(PostManager::class)
            ->call('duplicate', $post->id);

        $this->assertDatabaseHas('posts', [
            'type' => 'post',
            'status' => 'draft',
        ]);

        // Delete
        Livewire::actingAs($this->admin, 'admin')
            ->test(PostManager::class)
            ->call('delete', $post->id);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }

    public function test_post_block_editor_livewire_creates_landing_page_with_blocks(): void
    {
        $slug = 'smart-landing-'.uniqid();

        Livewire::actingAs($this->admin, 'admin')
            ->test(PostBlockEditor::class, ['type' => 'landing_page'])
            ->set('title.en', 'Smart Landing Demo')
            ->set('slug', $slug)
            ->set('type', 'landing_page')
            ->call('selectBlock', 'hero')
            ->call('selectBlock', 'features_grid')
            ->call('selectBlock', 'cta_banner')
            ->call('save', true);

        $this->assertDatabaseHas('posts', [
            'slug' => $slug,
            'type' => 'landing_page',
            'status' => 'published',
        ]);

        $created = Post::where('slug', $slug)->first();
        $this->assertNotNull($created);
        $this->assertNotEmpty($created->blocks);
    }

    public function test_post_block_editor_manipulates_blocks(): void
    {
        $component = Livewire::actingAs($this->admin, 'admin')
            ->test(PostBlockEditor::class, ['type' => 'post'])
            ->set('blocks', [
                ['id' => 'blk_1', 'type' => 'heading', 'content' => ['text' => 'First Heading']],
                ['id' => 'blk_2', 'type' => 'paragraph', 'content' => ['text' => 'Second Paragraph']],
            ]);

        // Move down
        $component->call('moveBlockDown', 0);
        $blocks = $component->get('blocks');
        $this->assertSame('blk_2', $blocks[0]['id']);
        $this->assertSame('blk_1', $blocks[1]['id']);

        // Move up
        $component->call('moveBlockUp', 1);
        $blocks = $component->get('blocks');
        $this->assertSame('blk_1', $blocks[0]['id']);

        // Duplicate
        $component->call('duplicateBlock', 0);
        $blocks = $component->get('blocks');
        $this->assertCount(3, $blocks);

        // Remove
        $component->call('removeBlock', 0);
        $blocks = $component->get('blocks');
        $this->assertCount(2, $blocks);
    }

    public function test_public_route_renders_published_blocks_and_hides_draft(): void
    {
        $postService = app(PostServiceInterface::class);
        $publishedSlug = 'public-live-'.uniqid();
        $draftSlug = 'private-draft-'.uniqid();

        $postService->createPost(new PostDTO(
            title: ['en' => 'Public Live Page', 'ar' => 'صفحة عامة'],
            slug: $publishedSlug,
            type: 'landing_page',
            status: 'published',
            blocks: [
                [
                    'id' => 'b1',
                    'type' => 'hero',
                    'content' => [
                        'badge' => 'LIVE DEMO',
                        'headline' => 'Next-Gen Competitive Insights',
                        'subtitle' => 'Revolutionary AI tracking suite.',
                    ],
                ],
                [
                    'id' => 'b2',
                    'type' => 'faq',
                    'content' => [
                        'headline' => 'Common Questions',
                        'items' => [
                            ['question' => 'Is there a trial?', 'answer' => 'Yes, 14 days full access.'],
                        ],
                    ],
                ],
            ],
            authorId: $this->admin->id
        ));

        $postService->createPost(new PostDTO(
            title: ['en' => 'Private Draft', 'ar' => 'مسودة'],
            slug: $draftSlug,
            type: 'post',
            status: 'draft',
            blocks: [],
            authorId: $this->admin->id
        ));

        // Published page returns 200 and renders blocks
        $response = $this->get("/p/{$publishedSlug}");
        $response->assertStatus(200);
        $response->assertSee('Next-Gen Competitive Insights');
        $response->assertSee('LIVE DEMO');
        $response->assertSee('Common Questions');

        // Draft page returns 404 for guests
        $this->get("/p/{$draftSlug}")->assertStatus(404);
    }

    public function test_post_observer_logs_activity(): void
    {
        $postService = app(PostServiceInterface::class);
        $slug = 'logged-post-'.uniqid();

        $post = $postService->createPost(new PostDTO(
            title: ['en' => 'Logged Post', 'ar' => 'مقال مسجل'],
            slug: $slug,
            type: 'post',
            status: 'draft',
            blocks: [],
            authorId: $this->admin->id
        ));

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'post.created',
            'subject_type' => Post::class,
            'subject_id' => (string) $post->id,
        ]);

        $postService->togglePublishStatus($post);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'post.published',
            'subject_type' => Post::class,
            'subject_id' => (string) $post->id,
        ]);

        $postService->deletePost($post);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'post.deleted',
            'subject_type' => Post::class,
            'subject_id' => (string) $post->id,
        ]);
    }
}
