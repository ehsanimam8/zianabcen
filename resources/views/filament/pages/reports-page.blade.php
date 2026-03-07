<x-filament-panels::page>
<div class="space-y-6">

    {{-- Tab Bar --}}
    <div class="border-b border-gray-200 dark:border-white/10">
        <nav class="-mb-px flex gap-6">
            @foreach ([
                'attendance' => ['label' => 'Attendance', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                'grades'     => ['label' => 'Grade Distribution', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                'revenue'    => ['label' => 'Revenue Breakdown', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ] as $tab => $meta)
            <button
                wire:click="$set('activeTab', '{{ $tab }}')"
                @class([
                    'group inline-flex items-center gap-2 border-b-2 px-1 pb-4 text-sm font-medium transition-colors',
                    'border-primary-600 text-primary-600' => $this->activeTab === $tab,
                    'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' => $this->activeTab !== $tab,
                ])>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $meta['icon'] }}" />
                </svg>
                {{ $meta['label'] }}
            </button>
            @endforeach
        </nav>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- ATTENDANCE TAB                                                 --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    @if ($this->activeTab === 'attendance')
    @php $attendance = $this->getAttendanceStats(); @endphp

    {{-- Filters --}}
    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Course</label>
                <select wire:model.live="attendanceCourseId"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-white/10 dark:bg-white/5 dark:text-white">
                    <option value="">All Courses</option>
                    @foreach ($this->getCourseOptions() as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">From</label>
                <input type="date" wire:model.live="attendanceDateFrom"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-white/10 dark:bg-white/5 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">To</label>
                <input type="date" wire:model.live="attendanceDateTo"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-white/10 dark:bg-white/5 dark:text-white">
            </div>
        </div>
    </div>

    {{-- Stats Summary --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-5">
        @foreach([
            ['label' => 'Total Records', 'value' => $attendance['total'],   'color' => 'gray'],
            ['label' => 'Present',       'value' => $attendance['present'], 'color' => 'green'],
            ['label' => 'Absent',        'value' => $attendance['absent'],  'color' => 'red'],
            ['label' => 'Late',          'value' => $attendance['late'],    'color' => 'yellow'],
            ['label' => 'Excused',       'value' => $attendance['excused'], 'color' => 'blue'],
        ] as $stat)
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4 text-center">
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stat['value'] }}</p>
            <p class="text-xs font-medium text-gray-500 mt-1">{{ $stat['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Attendance Records Table --}}
    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-white/10">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Attendance Records (latest 100)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-white/5 text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Student</th>
                        <th class="px-4 py-3">Course</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @forelse ($attendance['records'] as $record)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $record->student?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $record->session?->course?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium',
                                'bg-green-100 text-green-800' => $record->status === 'present',
                                'bg-red-100 text-red-800'     => $record->status === 'absent',
                                'bg-yellow-100 text-yellow-800' => $record->status === 'late',
                                'bg-blue-100 text-blue-800'   => $record->status === 'excused',
                                'bg-gray-100 text-gray-800'   => !in_array($record->status, ['present','absent','late','excused']),
                            ])>{{ ucfirst($record->status) }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $record->marked_at?->format('M d, Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No attendance records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- GRADE DISTRIBUTION TAB                                         --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    @if ($this->activeTab === 'grades')
    @php $gradeData = $this->getGradeStats(); @endphp

    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4">
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Filter by Course</label>
            <select wire:model.live="gradeCourseId"
                class="w-full max-w-sm rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-white/10 dark:bg-white/5 dark:text-white">
                <option value="">All Courses</option>
                @foreach ($this->getCourseOptions() as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6 text-center">
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $gradeData['total'] }}</p>
            <p class="text-xs font-medium text-gray-500 mt-1">Total Grades Recorded</p>
        </div>
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6 text-center">
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $gradeData['average'] }}%</p>
            <p class="text-xs font-medium text-gray-500 mt-1">Class Average</p>
        </div>
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6 text-center col-span-2 sm:col-span-1">
            <p class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ $gradeData['total'] > 0 ? round($gradeData['distribution']['F'] / $gradeData['total'] * 100, 1) : 0 }}%
            </p>
            <p class="text-xs font-medium text-gray-500 mt-1">Fail Rate</p>
        </div>
    </div>

    {{-- Letter Grade Distribution --}}
    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Grade Distribution</h3>
        <div class="space-y-3">
            @foreach([
                'A' => ['color' => 'bg-green-500',  'label' => 'A (90–100%)'],
                'B' => ['color' => 'bg-blue-500',   'label' => 'B (80–89%)'],
                'C' => ['color' => 'bg-yellow-500', 'label' => 'C (70–79%)'],
                'D' => ['color' => 'bg-orange-500', 'label' => 'D (60–69%)'],
                'F' => ['color' => 'bg-red-500',    'label' => 'F (< 60%)'],
            ] as $letter => $meta)
            @php
                $count = $gradeData['distribution'][$letter];
                $pct = $gradeData['total'] > 0 ? round($count / $gradeData['total'] * 100) : 0;
            @endphp
            <div class="flex items-center gap-4">
                <span class="w-20 text-sm font-medium text-gray-700 dark:text-gray-300">{{ $meta['label'] }}</span>
                <div class="flex-1 bg-gray-100 dark:bg-white/10 rounded-full h-5 overflow-hidden">
                    <div class="{{ $meta['color'] }} h-5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>
                <span class="w-16 text-right text-sm text-gray-600 dark:text-gray-400">{{ $count }} ({{ $pct }}%)</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Grade Records Table --}}
    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-white/10">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Grade Records (latest 100)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-white/5 text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Student</th>
                        <th class="px-4 py-3">Course</th>
                        <th class="px-4 py-3">Grade</th>
                        <th class="px-4 py-3">%</th>
                        <th class="px-4 py-3">Recorded</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @forelse ($gradeData['records'] as $grade)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $grade->enrollment?->user?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $grade->course?->name ?? '—' }}</td>
                        <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $grade->letter_grade ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $grade->percentage ? $grade->percentage . '%' : '—' }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $grade->recorded_at?->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No grades recorded.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- REVENUE TAB                                                    --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    @if ($this->activeTab === 'revenue')
    @php $rev = $this->getRevenueStats(); @endphp

    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4">
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Year</label>
            <select wire:model.live="revenueYear"
                class="w-40 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-white/10 dark:bg-white/5 dark:text-white">
                @foreach ($this->getYearOptions() as $y => $label)
                    <option value="{{ $y }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Revenue Summary Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        @foreach([
            ['label' => 'Total Revenue',      'value' => '$' . number_format($rev['total'], 2),             'icon' => 'banknotes',        'color' => 'text-green-600'],
            ['label' => 'Enrollment Revenue', 'value' => '$' . number_format($rev['total_enrollments'], 2), 'icon' => 'academic-cap',     'color' => 'text-blue-600'],
            ['label' => 'Donations Revenue',  'value' => '$' . number_format($rev['total_donations'], 2),   'icon' => 'heart',            'color' => 'text-rose-600'],
        ] as $card)
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ $card['label'] }}</p>
            <p class="mt-2 text-2xl font-bold {{ $card['color'] }}">{{ $card['value'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Monthly Breakdown Table --}}
    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-white/10">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Monthly Revenue — {{ $this->revenueYear }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-white/5 text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Month</th>
                        <th class="px-4 py-3 text-right">Enrollment Revenue</th>
                        <th class="px-4 py-3 text-right">Donations</th>
                        <th class="px-4 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @foreach ($rev['monthly'] as $month)
                    @php $rowTotal = $month['enrollments'] + $month['donations']; @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $month['month'] }}</td>
                        <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-300">${{ number_format($month['enrollments'], 2) }}</td>
                        <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-300">${{ number_format($month['donations'], 2) }}</td>
                        <td class="px-4 py-3 text-right font-semibold {{ $rowTotal > 0 ? 'text-green-600' : 'text-gray-400' }}">${{ number_format($rowTotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Per-Course Revenue --}}
    @if ($rev['by_course']->count())
    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-white/10">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Revenue by Course</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-white/5 text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Course</th>
                        <th class="px-4 py-3 text-right">Enrollments</th>
                        <th class="px-4 py-3 text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @foreach ($rev['by_course']->sortByDesc('revenue') as $row)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $row['course'] }}</td>
                        <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-300">{{ $row['enrollments'] }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-green-600">${{ number_format($row['revenue'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    @endif

</div>
</x-filament-panels::page>
