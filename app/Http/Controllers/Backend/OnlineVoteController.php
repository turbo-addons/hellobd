<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OnlineVoteController extends Controller
{
    public function questionIndex()
    {
        $questions = Question::select(['id', 'question_text', 'question_date', 'is_active', 'created_at'])
            ->withCount([
                'votes as yes_count' => function ($query) {
                    $query->where('vote_option', 'yes');
                },
                'votes as no_count' => function ($query) {
                    $query->where('vote_option', 'no');
                },
                'votes as no_comment_count' => function ($query) {
                    $query->where('vote_option', 'no_comment');
                },
                'votes as total_votes'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Cache statistics for 5 minutes
        $stats = Cache::remember('question_stats', 300, function () {
            return [
                'total' => Question::count(),
                'active' => Question::where('is_active', true)->count(),
                'total_votes' => Vote::count(),
                'total_voters' => Vote::select('user_id', 'ip_address')
                    ->whereNotNull('user_id')
                    ->orWhereNotNull('ip_address')
                    ->distinct()
                    ->count(),
            ];
        });
        
        $this->setBreadcrumbTitle(__('Online Votes'))
            ->setBreadcrumbIcon('lucide:vote');
        
        return $this->renderViewWithBreadcrumbs('backend.pages.questions.index', compact('questions', 'stats'));
    }

    public function questionCreate()
    {
        $this->setBreadcrumbTitle(__('Add Question'))
            ->setBreadcrumbIcon('lucide:plus-circle')
            ->addBreadcrumbItem(__('Questions'), route('admin.questions.index'));
        
        return $this->renderViewWithBreadcrumbs('backend.pages.questions.create');
    }

    public function questionStore(Request $request)
    {
        $validated = $request->validate([
            'question_text' => 'required|string|min:10|max:1000',
            'question_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        Question::create($validated);

        return redirect()->route('admin.questions.index')->with('success', __('Question created successfully'));
    }

    public function questionShow(Question $question)
    {
        $question->loadCount([
            'votes as yes_count' => function ($query) {
                $query->where('vote_option', 'yes');
            },
            'votes as no_count' => function ($query) {
                $query->where('vote_option', 'no');
            },
            'votes as no_comment_count' => function ($query) {
                $query->where('vote_option', 'no_comment');
            },
            'votes as total_votes'
        ]);
        
        // Recent votes (last 20)
        $recentVotes = $question->votes()
            ->with('user:id,first_name,last_name,username')
            ->latest()
            ->limit(20)
            ->get();
        
        $this->setBreadcrumbTitle(__('Question Details'))
            ->setBreadcrumbIcon('lucide:file-text')
            ->addBreadcrumbItem(__('Questions'), route('admin.questions.index'));
        
        return $this->renderViewWithBreadcrumbs('backend.pages.questions.show', compact('question', 'recentVotes'));
    }

    public function questionEdit(Question $question)
    {
        $this->setBreadcrumbTitle(__('Edit Question'))
            ->setBreadcrumbIcon('lucide:edit')
            ->addBreadcrumbItem(__('Questions'), route('admin.questions.index'));
        
        return $this->renderViewWithBreadcrumbs('backend.pages.questions.edit', compact('question'));
    }

    public function questionUpdate(Request $request, Question $question)
    {
        $validated = $request->validate([
            'question_text' => 'required|string|min:10|max:1000',
            'question_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        $question->update($validated);

        return redirect()->route('admin.questions.index')->with('success', __('Question updated successfully'));
    }

    public function questionDelete(Question $question)
    {
        $question->delete();

        return redirect()->route('admin.questions.index')->with('success', __('Question deleted successfully'));
    }

    public function toggleStatus(Question $question)
    {
        $question->update(['is_active' => !$question->is_active]);
        
        $status = $question->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()->with('success', __('Question :status successfully', ['status' => $status]));
    }
}