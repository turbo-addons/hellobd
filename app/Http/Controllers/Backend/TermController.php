<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Term\StoreTermRequest;
use App\Http\Requests\Term\UpdateTermRequest;
use App\Models\Term;
use App\Models\TermMenuOrder;
use App\Models\MainMenuOrder;
use App\Services\Content\ContentService;
use App\Services\TermService;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function __construct(
        private readonly ContentService $contentService,
        private readonly TermService $termService
    ) {
    }

    public function index(Request $request, string $taxonomy)
    {
        $this->authorize('viewAny', Term::class);

        // Get taxonomy using service
        $taxonomyModel = $this->termService->getTaxonomy($taxonomy);

        if (! $taxonomyModel) {
            return redirect()->route('admin.posts.index')->with('error', __('Taxonomy not found'));
        }

        // Get parent terms for hierarchical taxonomies.
        $parentTerms = [];
        if ($taxonomyModel->hierarchical) {
            $parentTerms = Term::where('taxonomy', $taxonomy)
                ->orderBy('name', 'asc')
                ->get();
        }

        // Get term being edited if exists.
        $term = null;
        if ($request->has('edit') && is_numeric($request->edit)) {
            $term = Term::findOrFail($request->edit);
        }

        $this->setBreadcrumbTitle($taxonomyModel->label)
            ->setBreadcrumbIcon($taxonomyModel->icon ?? 'lucide:tag');

        return $this->renderViewWithBreadcrumbs('backend.pages.terms.index', compact('taxonomy', 'taxonomyModel', 'parentTerms', 'term'));
    }

    public function store(StoreTermRequest $request, string $taxonomy)
    {
        $this->authorize('create', Term::class);

        // Get taxonomy using service
        $taxonomyModel = $this->termService->getTaxonomy($taxonomy);

        if (! $taxonomyModel) {
            return redirect()->route('admin.posts.index')->with('error', __('Taxonomy not found'));
        }

        // Create term using service
        $term = $this->termService->createTerm($request->validated(), $taxonomy);

        // Get taxonomy label for message
        $taxLabel = $this->termService->getTaxonomyLabel($taxonomy, true);

        return redirect()->route('admin.terms.index', $taxonomy)
            ->with('success', __(':taxLabel created successfully', ['taxLabel' => $taxLabel]));
    }

    public function update(UpdateTermRequest $request, string $taxonomy, string $id)
    {
        // Get taxonomy using service
        $taxonomyModel = $this->termService->getTaxonomy($taxonomy);

        if (! $taxonomyModel) {
            return redirect()->route('admin.posts.index')->with('error', __('Taxonomy not found'));
        }

        // Get term using service
        $term = $this->termService->getTermById((int) $id, $taxonomy);

        $this->authorize('update', $term);

        // Update term using service
        $this->termService->updateTerm($term, $request->validated());

        // Get taxonomy label for message
        $taxLabel = $this->termService->getTaxonomyLabel($taxonomy, true);

        return redirect()->route('admin.terms.index', $taxonomy)
            ->with('success', __(':taxLabel updated successfully', ['taxLabel' => $taxLabel]));
    }

    public function destroy(string $taxonomy, string $id)
    {
        // Get taxonomy using service
        $taxonomyModel = $this->termService->getTaxonomy($taxonomy);

        if (! $taxonomyModel) {
            return redirect()->route('admin.posts.index')->with('error', __('Taxonomy not found'));
        }

        // Get term using service
        $term = $this->termService->getTermById((int) $id, $taxonomy);

        $this->authorize('delete', $term);

        // Get taxonomy label for messages
        $taxLabel = $this->termService->getTaxonomyLabel($taxonomy, true);

        // Check if term can be deleted
        $errors = $this->termService->canDeleteTerm($term);

        if (in_array('has_posts', $errors)) {
            return redirect()->route('admin.terms.index', $taxonomy)
                ->with('error', __('Cannot delete :taxLabel as it is associated with posts', ['taxLabel' => $taxLabel]));
        }

        if (in_array('has_children', $errors)) {
            return redirect()->route('admin.terms.index', $taxonomy)
                ->with('error', __('Cannot delete :taxLabel as it has child items', ['taxLabel' => $taxLabel]));
        }

        // Delete term using service
        $this->termService->deleteTerm($term);

        return redirect()->route('admin.terms.index', $taxonomy)
            ->with('success', __(':taxLabel deleted successfully', ['taxLabel' => $taxLabel]));
    }

    public function edit(string $taxonomy, string $term)
    {
        // Get taxonomy using service.
        $taxonomyModel = $this->termService->getTaxonomy($taxonomy);

        if (! $taxonomyModel) {
            return redirect()->route('admin.posts.index')->with('error', __('Taxonomy not found'));
        }

        // Get term using service.
        $term = $this->termService->getTermById((int) $term, $taxonomy);

        $this->authorize('update', $term);

        // Get parent terms for hierarchical taxonomies.
        $parentTerms = [];
        if ($taxonomyModel->hierarchical) {
            $parentTerms = Term::where('taxonomy', $taxonomy)
                ->orderBy('name', 'asc')
                ->get();
        }

        $this->setBreadcrumbTitle(__('Edit :taxLabel', ['taxLabel' => $taxonomyModel->label_singular]))
            ->setBreadcrumbIcon($taxonomyModel->icon ?? 'lucide:tag')
            ->addBreadcrumbItem($taxonomyModel->label, route('admin.terms.index', $taxonomy));

        return $this->renderViewWithBreadcrumbs('backend.pages.terms.edit', compact('taxonomy', 'taxonomyModel', 'term', 'parentTerms'));
    }

    /**
     * Delete multiple terms at once
     */
    public function bulkDelete(Request $request, string $taxonomy)
    {
        $this->authorize('bulkDelete', Term::class);

        // Get taxonomy using service
        $taxonomyModel = $this->termService->getTaxonomy($taxonomy);

        if (! $taxonomyModel) {
            return redirect()->route('admin.posts.index')
                ->with('error', __('Taxonomy not found'));
        }

        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->route('admin.terms.index', $taxonomy)
                ->with('error', __('No terms selected for deletion'));
        }

        // Get taxonomy label for messages
        $taxLabel = $this->termService->getTaxonomyLabel($taxonomy, true);
        $deletedCount = 0;
        $errorMessages = [];

        foreach ($ids as $id) {
            // Get term using service
            $term = $this->termService->getTermById((int) $id, $taxonomy);

            if (! $term) {
                continue;
            }

            // Check if term can be deleted
            $errors = $this->termService->canDeleteTerm($term);

            if (! empty($errors)) {
                if (in_array('has_posts', $errors)) {
                    $errorMessages[] = __('":name" cannot be deleted as it is associated with posts', ['name' => $term->name]);
                }

                if (in_array('has_children', $errors)) {
                    $errorMessages[] = __('":name" cannot be deleted as it has child items', ['name' => $term->name]);
                }

                continue;
            }

            // Delete term using service
            $this->termService->deleteTerm($term);
            $deletedCount++;
        }

        if ($deletedCount > 0) {
            session()->flash('success', __(':count :taxLabel deleted successfully', [
                'count' => $deletedCount,
                'taxLabel' => strtolower($taxonomyModel->label),
            ]));
        }

        if (! empty($errorMessages)) {
            session()->flash('error', implode('<br>', $errorMessages));
        } elseif ($deletedCount === 0) {
            session()->flash('error', __('No :taxLabel were deleted', ['taxLabel' => strtolower($taxonomyModel->label)]));
        }

        return redirect()->route('admin.terms.index', $taxonomy);
    }

    /**
     * Category Sort Index For Other Category
     */
    public function categorySortIndex()
    {
        $this->authorize('viewAny', Term::class);

        $taxonomy = 'category';

        $categories = Term::leftJoin('term_menu_orders', 'terms.id', '=', 'term_menu_orders.term_id')
            ->where('terms.taxonomy', $taxonomy)
            ->where('terms.parent_id', '!=', 0) // fully qualified
            ->select(
                'terms.id',
                'terms.name',
                'terms.parent_id',
                \DB::raw('COALESCE(term_menu_orders.menu_order, 9999) as menu_order')
            )
            ->orderBy('menu_order', 'asc')
            ->orderBy('terms.id', 'asc')
            ->get();

        $this->setBreadcrumbTitle(__('Sort Other Categories'))
            ->setBreadcrumbIcon('lucide:move-vertical');

        return $this->renderViewWithBreadcrumbs(
            'backend.pages.terms.category-sort',
            compact('categories')
        );
    }

    /**
     * Save Category Sort For Other Category
     */

    public function categorySortSave(Request $request)
    {
        $this->authorize('update', Term::class);

        $request->validate([
            'items' => 'required|array',
        ]);

        foreach ($request->items as $item) {
            \DB::table('term_menu_orders')->updateOrInsert(
                ['term_id' => $item['id']],
                ['menu_order' => $item['order'], 'updated_at' => now(), 'created_at' => now()]
            );
        }

        return response()->json(['success' => true]);
    }

    /**
     * Main Category Sort Index
     */

    public function mainCategorySortIndex()
    {
        $this->authorize('viewAny', Term::class);

        // Parent categories only (no sub-categories)
        $parentCategories = Term::where('taxonomy', 'category')
            ->whereNull('parent_id') // top-level parent categories
            ->select('id', 'name')
            ->get();

        // Check if MainMenuOrder has any data
        $savedOrders = MainMenuOrder::orderBy('menu_order')->pluck('term_id')->toArray();

        if (!empty($savedOrders)) {
            // If data exists, load from MainMenuOrder
            $sortedCategories = collect($savedOrders)
                ->map(fn($id) => $parentCategories->firstWhere('id', $id))
                ->filter(); // remove null just in case

            // Remaining categories go to default box
            $defaultCategories = $parentCategories->filter(fn($cat) => !in_array($cat->id, $savedOrders));
        } else {
            // First time: everything goes to default box, sorted box empty
            $defaultCategories = $parentCategories;
            $sortedCategories = collect([]);
        }

        return $this->setBreadcrumbTitle(__('Sort Main Menu'))
                    ->setBreadcrumbIcon('lucide:folder')
                    ->renderViewWithBreadcrumbs(
                        'backend.pages.terms.maincategory-sort',
                        compact('defaultCategories', 'sortedCategories')
                    );
    }


    public function mainCategorySortSave(Request $request)
    {
        $this->authorize('update', Term::class);

        $request->validate([
            'items' => 'required|array',
        ]);

        foreach ($request->items as $item) {
            // If order is null → remove saved order
            if (is_null($item['order'])) {
                MainMenuOrder::where('term_id', $item['id'])->delete();
            } else {
                MainMenuOrder::updateOrCreate(
                    ['term_id' => $item['id']],
                    ['menu_order' => $item['order']]
                );
            }
        }

        return response()->json(['success' => true, 'message' => 'Main menu order saved successfully!']);
    }


}
