<?php

namespace App\Filament\Teacher\Resources\LMS\Assessments\Pages;

use App\Filament\Teacher\Resources\LMS\Assessments\AssessmentResource;
use App\Models\LMS\AssessmentSubmission;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\HtmlString;

class ViewSubmission extends Page
{
    protected static string $resource = AssessmentResource::class;

    protected static string $view = 'filament.teacher.resources.assessments.pages.view-submission';

    public AssessmentSubmission $submission;

    public string $submissionId;

    // Grading fields
    public $status;
    public $total_score;
    public $instructor_feedback;

    public function mount(string $record, string $submission): void
    {
        $this->submissionId = $submission;
        $this->submission = AssessmentSubmission::with(['user', 'answers.question'])->findOrFail($submission);

        $this->status = $this->submission->status;
        $this->total_score = $this->submission->total_score;
        $this->instructor_feedback = $this->submission->instructor_feedback;
    }

    public function saveGrade(): void
    {
        $this->submission->update([
            'status' => $this->status,
            'total_score' => $this->total_score,
            'instructor_feedback' => $this->instructor_feedback,
            'graded_by' => auth()->id(),
            'graded_at' => now(),
        ]);

        Notification::make()
            ->title('Grade saved successfully')
            ->success()
            ->send();
    }

    public function saveAnswerGrade(string $answerId, bool $isCorrect, ?int $pointsAwarded, ?string $instructorComment): void
    {
        $answer = $this->submission->answers()->findOrFail($answerId);
        $answer->update([
            'is_correct' => $isCorrect,
            'points_awarded' => $pointsAwarded,
            'instructor_comment' => $instructorComment,
        ]);

        // Recalculate total score from individual answer points
        $totalScore = $this->submission->answers()->whereNotNull('points_awarded')->sum('points_awarded');
        $this->submission->update(['total_score' => $totalScore]);
        $this->total_score = $totalScore;

        Notification::make()
            ->title('Answer graded')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to Assessment')
                ->url(AssessmentResource::getUrl('edit', ['record' => $this->submission->assessment_id]))
                ->icon('heroicon-o-arrow-left')
                ->color('gray'),

            Action::make('save_grade')
                ->label('Save Grade')
                ->icon('heroicon-o-check')
                ->color('success')
                ->action('saveGrade'),
        ];
    }

    public function getTitle(): string
    {
        return 'Review Submission: ' . ($this->submission->user?->name ?? 'Unknown Student');
    }
}
