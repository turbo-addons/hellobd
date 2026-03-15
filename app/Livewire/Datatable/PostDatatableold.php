<?php

declare(strict_types=1);

namespace App\Livewire\Datatable;

use App\Enums\Hooks\DatatableHook;
use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\Term;
use App\Services\Content\ContentService;
use App\Services\Content\PostType;
use Illuminate\Contracts\Support\Renderable;
use Spatie\QueryBuilder\QueryBuilder;

class PostDatatable extends Datatable
{
    public string $status = '';
    public string $tag = '';
    public string $category = '';
    public string $postTypeMeta = '';
    public string $postType = PostType::POST;
    public array $queryString = [
        ...parent::QUERY_STRING_DEFAULTS,
        'status' => [],
        'tag' => [],
        'category' => [],
        'postTypeMeta' => [],
    ];
    public array $categories = [];
    public string $model = Post::class;

    public function getSearchbarPlaceholder(): string
    {
        return __('Search by title or content') . '...';
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingTag()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingPostTypeMeta()
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        parent::mount();

        $postTypeModel = $this->getPostTypeModelProperty();
        if ($postTypeModel->supports_taxonomies && $postTypeModel->taxonomies && in_array('category', $postTypeModel->taxonomies)) {
            $this->categories = Term::where('taxonomy', 'category')->get()->toArray();
        }

        // Apply hooks to modify datatable initialization.
        $this->addHooks(
            $this,
            DatatableHook::POST_DATATABLE_MOUNTED
        );
    }

    public function getPostTypeModelProperty(): PostType
    {
        return app(ContentService::class)->getPostType($this->postType);
    }

    public function getFilters(): array
    {
        $postTypeModel = $this->getPostTypeModelProperty();
        $filters = [];

        $filters[] = [
            'id' => 'status',
            'label' => __('Status'),
            'filterLabel' => __('Status'),
            'icon' => 'lucide:filter',
            'allLabel' => __('All Statuses'),
            'options' => Post::getPostStatuses(),
            'selected' => $this->status,
        ];

        $filters[] = [
            'id' => 'postTypeMeta',
            'label' => __('Type'),
            'filterLabel' => __('Post Type'),
            'icon' => 'lucide:tag',
            'allLabel' => __('All Types'),
            'options' => [
                'breaking' => __('Breaking'),
                'featured' => __('Featured'),
                'slide' => __('Slide'),
                'live' => __('Live'),
            ],
            'selected' => $this->postTypeMeta,
        ];

        if ($postTypeModel->supports_taxonomies && $postTypeModel->taxonomies && in_array('tag', $postTypeModel->taxonomies)) {
            $filters[] = [
                'id' => 'tag',
                'label' => __('Tag'),
                'filterLabel' => __('Tag'),
                'icon' => 'lucide:tag',
                'allLabel' => __('All Tags'),
                'options' => Term::where('taxonomy', 'tag')->pluck('name', 'id'),
                'selected' => $this->tag,
            ];
        }

        if ($postTypeModel->supports_taxonomies && $postTypeModel->taxonomies && in_array('category', $postTypeModel->taxonomies)) {
            $filters[] = [
                'id' => 'category',
                'label' => __('Category'),
                'filterLabel' => __('Category'),
                'icon' => 'lucide:folder',
                'allLabel' => __('All Categories'),
                'options' => collect($this->categories)->pluck('name', 'id')->toArray(),
                'selected' => $this->category,
            ];
        }

        $filters = $this->addHooks(
            $filters,
            null,
            DatatableHook::DATATABLE_MOUNTED
        );

        return $filters;
    }

    protected function getRouteParameters(): array
    {
        return ['postType' => $this->postType];
    }

    protected function getItemRouteParameters($item): array
    {
        return [
            'postType' => $this->postType,
            'post' => $item->id,
        ];
    }

    protected function getHeaders(): array
    {
        $postTypeModel = $this->getPostTypeModelProperty();

        $headers = [
            [
                'id' => 'title',
                'title' => __('Title'),
                'width' => null,
                'sortable' => true,
                'sortBy' => 'title',
            ],
            [
                'id' => 'reporter',
                'title' => __('Reporter'),
                'width' => null,
                'sortable' => false,
            ],
            [
                'id' => 'author',
                'title' => __('Author'),
                'width' => null,
                'sortable' => true,
                'sortBy' => 'author',
            ],
            [
                'id' => 'edited_by',
                'title' => __('Edited By'),
                'width' => null,
                'sortable' => false,
            ],
            [
                'id' => 'post_type_meta',
                'title' => __('Type'),
                'width' => null,
                'sortable' => false,
            ],
            [
                'id' => 'status',
                'title' => __('Status'),
                'width' => null,
                'sortable' => true,
                'sortBy' => 'status',
            ],
        ];

        if ($postTypeModel->supports_taxonomies && $postTypeModel->taxonomies && in_array('category', $postTypeModel->taxonomies)) {
            $headers[] = [
                'id' => 'category',
                'title' => __('Category'),
                'width' => null,
                'sortable' => true,
                'sortBy' => 'category',
            ];
        }

        $headers[] = [
            'id' => 'created_at',
            'title' => __('Created At'),
            'width' => null,
            'sortable' => true,
            'sortBy' => 'created_at',
        ];

        $headers[] = [
            'id' => 'actions',
            'title' => __('Actions'),
            'width' => null,
            'sortable' => false,
            'is_action' => true,
        ];

        return $headers;
    }

    protected function buildQuery(): QueryBuilder
    {
        $query = QueryBuilder::for($this->model)
            ->where('post_type', $this->postType)
            ->select(['id', 'title', 'slug', 'status', 'user_id', 'reporter_id', 'edited_by', 'post_type_meta', 'is_sponsored', 'created_at', 'published_at'])
            ->with([
                'author:id,first_name,last_name,username',
                'editor:id,first_name,last_name,username',
                'reporter:id,type,desk_name,user_id',
                'reporter.user:id,first_name,last_name,username'
            ])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', "%{$this->search}%")
                        ->orWhere('excerpt', 'like', "%{$this->search}%");
                });
            })
            ->when($this->status, function ($q) {
                $q->where('status', $this->status);
            })
            ->when($this->postTypeMeta, function ($q) {
                $q->where(function ($query) {
                    $query->whereJsonContains('post_type_meta->is_' . $this->postTypeMeta, true)
                        ->orWhereJsonContains('post_type_meta->is_' . $this->postTypeMeta, 1);
                });
            })
            ->when($this->tag, function ($q) {
                $q->whereHas('terms', function ($q) {
                    $q->where('taxonomy', 'tag')
                        ->where('terms.id', $this->tag);
                });
            })
            ->when($this->category, function ($q) {
                $q->whereHas('terms', function ($q) {
                    $q->where('taxonomy', 'category')
                        ->where('terms.id', $this->category);
                });
            });

        return $this->sortQuery($query);
    }

    public function renderTitleColumn(Post $post): string|Renderable
    {
        ob_start();
        ?>
        <div class="flex gap-0.5 items-center">
            <?php if ($post->hasFeaturedImage()): ?>
                <img src="<?php echo $post->getFeaturedImageOriginalUrl() ?>" alt="<?php echo $post->title ?>"
                    class="w-12 object-cover rounded mr-3 min-w-10">
            <?php else: ?>
                <div class="bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center mr-2 h-10 w-10 min-w-10">
                    <iconify-icon icon="lucide:image" class=" text-center text-gray-400"></iconify-icon>
                </div>
            <?php endif; ?>
            <div class="flex flex-col">
                <a href="<?php echo route('admin.posts.edit', [$this->postType, $post->id]) ?>"
                    class="text-gray-700 dark:text-white font-medium hover:text-primary dark:hover:text-primary">
                    <?php echo $post->title; ?>
                </a>
                <?php if ($post->is_sponsored): ?>
                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 mt-1 w-fit">Sponsored</span>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function renderStatusColumn(Post $post): string|Renderable
    {
        $html = "<span class='" . get_post_status_class($post->status) . "'>" . ucfirst($post->status) . "</span>";

        if ($post->status === PostStatus::SCHEDULED->value && ! empty($post->published_at)) {
            $html .= " <br><small class='text-muted'>" . __('(Scheduled for :date)', ['date' => $post->published_at->format('M d, Y h:i A')]) . "</small>";
        }

        return $html;
    }

    public function renderAuthorColumn(Post $post): string|Renderable
    {
        return ucfirst($post->author->full_name ?? '');
    }
    
    public function renderEditedByColumn(Post $post): string|Renderable
    {
        if (!$post->edited_by || !$post->editor) {
            return '<span class="text-gray-400 text-sm">-</span>';
        }
        return ucfirst($post->editor->full_name ?? '');
    }

    public function renderCategoryColumn(Post $post): string|Renderable
    {
        return $post->categories->pluck('name')->map(fn ($name) => "<span class='badge'>" . ucfirst($name) . "</span>")->join(' ');
    }

    public function renderReporterColumn(Post $post): string|Renderable
    {
        if (!$post->reporter_id || !$post->reporter) {
            return '<span class="text-gray-400 text-sm">-</span>';
        }
        
        $reporter = $post->reporter;
        $name = $reporter->type === 'desk' 
            ? $reporter->desk_name 
            : ($reporter->user ? ($reporter->user->full_name ?? $reporter->user->first_name ?? $reporter->user->name ?? '-') : '-');
        
        if (!$name || $name === '-') {
            return '<span class="text-gray-400 text-sm">-</span>';
        }
        
        $initial = mb_substr($name, 0, 1);
        
        return '<div class="flex items-center gap-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300 text-xs font-semibold">
                ' . htmlspecialchars($initial) . '
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">' . htmlspecialchars($name) . '</span>
        </div>';
    }

    public function renderPostTypeMetaColumn(Post $post): string|Renderable
    {
        if (!$post->post_type_meta) {
            return '<span class="text-gray-400 text-sm">-</span>';
        }
        $badges = [];
        if (!empty($post->post_type_meta['is_breaking'])) {
            $badges[] = '<span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">Breaking</span>';
        }
        if (!empty($post->post_type_meta['is_featured'])) {
            $badges[] = '<span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">Featured</span>';
        }
        if (!empty($post->post_type_meta['is_slide'])) {
            $badges[] = '<span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800 dark:bg-purple-900 dark:text-purple-300">Slide</span>';
        }
        if (!empty($post->post_type_meta['is_live'])) {
            $badges[] = '<span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">Live</span>';
        }
        if ($post->is_sponsored) {
            $badges[] = '<span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">Sponsored</span>';
        }
        return $badges ? '<div class="flex flex-wrap gap-1">' . implode(' ', $badges) . '</div>' : '<span class="text-gray-400 text-sm">-</span>';
    }

}
