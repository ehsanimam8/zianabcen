<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Submission Header Card --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-content-ctn">
                <div class="fi-section-content p-6">
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Student</p>
                            <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                                {{ $this->submission->user?->name ?? 'Unknown' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Submitted At</p>
                            <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                                {{ $this->submission->submitted_at?->format('M d, Y H:i') ?? '—' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
                            <div class="mt-1">
                                <span @class([
                                    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                    'bg-yellow-100 text-yellow-800' => $this->submission->status === 'submitted',
                                    'bg-blue-100 text-blue-800' => $this->submission->status === 'grading',
                                    'bg-green-100 text-green-800' => $this->submission->status === 'graded',
                                ])>
                                    {{ ucfirst($this->submission->status) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Score</p>
                            <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                                {{ $this->submission->total_score ?? '—' }}
                                / {{ $this->submission->assessment?->questions->sum('points') ?? '?' }} pts
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Individual Answers --}}
        @foreach ($this->submission->answers->sortBy(fn($a) => $a->question?->sort_order ?? 0) as $index => $answer)
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-header px-6 py-4 border-b border-gray-200 dark:border-white/10 flex items-start justify-between gap-4">
                <div class="flex-1">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                        Q{{ $index + 1 }} · {{ ucwords(str_replace('_', ' ', $answer->question?->question_type ?? '')) }}
                        · {{ $answer->question?->points ?? 0 }} pts
                    </span>
                    <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                        {{ $answer->question?->question_text ?? 'Question deleted' }}
                    </p>
                </div>
                @if ($answer->is_correct === true)
                    <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                        ✓ Correct
                    </span>
                @elseif ($answer->is_correct === false)
                    <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                        ✗ Incorrect
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">
                        Not Graded
                    </span>
                @endif
            </div>

            <div class="fi-section-content p-6 space-y-4" x-data="{
                isCorrect: {{ $answer->is_correct === null ? 'null' : ($answer->is_correct ? 'true' : 'false') }},
                pointsAwarded: {{ $answer->points_awarded ?? 'null' }},
                instructorComment: '{{ addslashes($answer->instructor_comment ?? '') }}'
            }">
                {{-- Student Answer vs Reference --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                            Student's Answer
                        </p>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 dark:border-white/10 dark:bg-white/5">
                            @if ($answer->student_answer)
                                <p class="text-sm text-gray-900 dark:text-white">{{ $answer->student_answer }}</p>
                            @else
                                <p class="text-sm text-gray-400 italic">No answer provided</p>
                            @endif
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                            Reference Answer
                        </p>
                        <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 dark:border-blue-900/40 dark:bg-blue-950/20">
                            @if ($answer->question?->question_type === 'multiple_choice' && is_array($answer->question?->options))
                                @foreach ($answer->question->options as $opt)
                                    @if ($opt['is_correct'] ?? false)
                                        <p class="text-sm font-medium text-blue-900 dark:text-blue-300">{{ $opt['text'] }}</p>
                                    @endif
                                @endforeach
                            @elseif ($answer->question?->correct_answer)
                                <p class="text-sm font-medium text-blue-900 dark:text-blue-300">{{ Str::ucfirst($answer->question->correct_answer) }}</p>
                            @else
                                <p class="text-sm text-gray-400 italic">No reference (essay/manual grading)</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Instructor Grading Controls --}}
                <div class="border-t border-gray-100 dark:border-white/5 pt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="flex items-center gap-3">
                        <label class="text-xs font-semibold uppercase tracking-wider text-gray-500">Mark as Correct</label>
                        <input type="checkbox" x-model="isCorrect"
                            class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 block mb-1">Points Awarded</label>
                        <input type="number" x-model="pointsAwarded" min="0" max="{{ $answer->question?->points ?? 100 }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-900 shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-white">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 block mb-1">Instructor Comment</label>
                        <input type="text" x-model="instructorComment"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-900 shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-white">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button
                        @click="$wire.saveAnswerGrade('{{ $answer->id }}', isCorrect, pointsAwarded ? parseInt(pointsAwarded) : null, instructorComment)"
                        class="fi-btn fi-btn-color-success fi-btn-size-sm fi-btn-outlined inline-flex items-center gap-1.5 rounded-lg border border-success-600 px-3 py-1.5 text-sm font-semibold text-success-700 shadow-sm hover:bg-success-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-success-600 dark:border-success-500 dark:text-success-400 dark:hover:bg-success-950/30">
                        Save This Answer
                    </button>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Overall Grading Section --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-header px-6 py-4 border-b border-gray-200 dark:border-white/10">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Overall Grade & Feedback</h3>
            </div>
            <div class="fi-section-content p-6 space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 block mb-1">Total Score</label>
                        <input type="number" wire:model="total_score"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-white">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 block mb-1">Status</label>
                        <select wire:model="status"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-white">
                            <option value="submitted">Submitted</option>
                            <option value="grading">Grading</option>
                            <option value="graded">Graded</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 block mb-1">Overall Feedback to Student</label>
                    <textarea wire:model="instructor_feedback" rows="4"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-white"
                        placeholder="Write feedback visible to the student..."></textarea>
                </div>
                <div class="flex justify-end">
                    <button wire:click="saveGrade"
                        class="fi-btn fi-btn-color-success inline-flex items-center gap-1.5 rounded-lg bg-success-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-success-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-success-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Save Final Grade
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page>
