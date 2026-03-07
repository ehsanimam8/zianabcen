<x-filament-panels::page>
    <style>
        .report-card {
            background-color: rgb(var(--default-bg, 255 255 255));
            border-radius: 0.75rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(var(--gray-950, 3 7 18), 0.05);
            padding: 1.5rem;
            text-align: center;
        }

        .dark .report-card {
            background-color: rgba(var(--gray-900, 17 24 39), 1);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .report-value {
            font-size: 1.875rem;
            font-weight: 700;
            color: rgba(var(--gray-900, 17 24 39), 1);
        }

        .dark .report-value {
            color: white;
        }

        .report-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: rgba(var(--gray-500, 107 114 128), 1);
            margin-top: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .report-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .report-table {
            width: 100%;
            text-align: left;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .report-table th {
            padding: 0.75rem 1rem;
            background-color: rgba(var(--gray-50, 249 250 251), 1);
            color: rgba(var(--gray-500, 107 114 128), 1);
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 600;
            border-bottom: 1px solid rgba(var(--gray-200, 229 231 235), 1);
        }

        .dark .report-table th {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .report-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(var(--gray-100, 243 244 246), 1);
        }

        .dark .report-table td {
            border-color: rgba(255, 255, 255, 0.05);
        }

        .fi-filter-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .fi-filter-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            flex: 1;
            min-width: 200px;
        }

        .fi-filter-item label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #6b7280;
        }

        .fi-filter-item select,
        .fi-filter-item input {
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            width: 100%;
        }

        .dark .fi-filter-item select,
        .dark .fi-filter-item input {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .badge {
            display: inline-block;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-green {
            background: #dcfce7;
            color: #166534;
        }

        .badge-red {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-yellow {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-blue {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-gray {
            background: #f3f4f6;
            color: #374151;
        }

        .bar-chart-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.75rem;
        }

        .bar-chart-label {
            width: 100px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .bar-chart-track {
            flex: 1;
            background: #f3f4f6;
            border-radius: 9999px;
            height: 1.25rem;
            overflow: hidden;
        }

        .dark .bar-chart-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .bar-chart-fill {
            height: 100%;
            border-radius: 9999px;
        }

        .bar-chart-value {
            width: 80px;
            text-align: right;
            font-size: 0.875rem;
            color: #6b7280;
        }
    </style>

    <div class="space-y-6">

        <x-filament::tabs>
            <x-filament::tabs.item :active="$activeTab === 'attendance'" wire:click="$set('activeTab', 'attendance')"
                icon="heroicon-m-clipboard-document-check">
                Attendance
            </x-filament::tabs.item>
            <x-filament::tabs.item :active="$activeTab === 'grades'" wire:click="$set('activeTab', 'grades')"
                icon="heroicon-m-academic-cap">
                Grade Distribution
            </x-filament::tabs.item>
            <x-filament::tabs.item :active="$activeTab === 'revenue'" wire:click="$set('activeTab', 'revenue')"
                icon="heroicon-m-banknotes">
                Revenue Breakdown
            </x-filament::tabs.item>
        </x-filament::tabs>

        {{-- ATTENDANCE --}}
        @if ($activeTab === 'attendance')
            @php $attendance = $this->getAttendanceStats(); @endphp

            <x-filament::section>
                <div class="fi-filter-row">
                    <div class="fi-filter-item">
                        <label>Course</label>
                        <select wire:model.live="attendanceCourseId">
                            <option value="">All Courses</option>
                            @foreach ($this->getCourseOptions() as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fi-filter-item">
                        <label>From Date</label>
                        <input type="date" wire:model.live="attendanceDateFrom">
                    </div>
                    <div class="fi-filter-item">
                        <label>To Date</label>
                        <input type="date" wire:model.live="attendanceDateTo">
                    </div>
                </div>

                <div class="report-grid">
                    <div class="report-card">
                        <div class="report-value">{{ $attendance['total'] }}</div>
                        <div class="report-label">Total Records</div>
                    </div>
                    <div class="report-card">
                        <div class="report-value" style="color: #16a34a;">{{ $attendance['present'] }}</div>
                        <div class="report-label">Present</div>
                    </div>
                    <div class="report-card">
                        <div class="report-value" style="color: #dc2626;">{{ $attendance['absent'] }}</div>
                        <div class="report-label">Absent</div>
                    </div>
                    <div class="report-card">
                        <div class="report-value" style="color: #ca8a04;">{{ $attendance['late'] }}</div>
                        <div class="report-label">Late</div>
                    </div>
                    <div class="report-card">
                        <div class="report-value" style="color: #2563eb;">{{ $attendance['excused'] }}</div>
                        <div class="report-label">Excused</div>
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section heading="Recent Attendance Records (latest 100)">
                <div style="overflow-x: auto;">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attendance['records'] as $record)
                                <tr>
                                    <td><strong>{{ $record->student?->name ?? '—' }}</strong></td>
                                    <td>{{ $record->session?->course?->name ?? '—' }}</td>
                                    <td>
                                        @php
                                            $badge = match ($record->status) {
                                                'present' => 'badge-green',
                                                'absent' => 'badge-red',
                                                'late' => 'badge-yellow',
                                                'excused' => 'badge-blue',
                                                default => 'badge-gray',
                                            };
                                        @endphp
                                        <span class="badge {{ $badge }}">{{ ucfirst($record->status) }}</span>
                                    </td>
                                    <td>{{ $record->marked_at?->format('M d, Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 2rem; color: #9ca3af;">No
                                        attendance records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-filament::section>
        @endif

        {{-- GRADES --}}
        @if ($activeTab === 'grades')
            @php $gradeData = $this->getGradeStats(); @endphp

            <x-filament::section>
                <div class="fi-filter-row">
                    <div class="fi-filter-item" style="max-width: 300px;">
                        <label>Filter by Course</label>
                        <select wire:model.live="gradeCourseId">
                            <option value="">All Courses</option>
                            @foreach ($this->getCourseOptions() as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="report-grid">
                    <div class="report-card">
                        <div class="report-value">{{ $gradeData['total'] }}</div>
                        <div class="report-label">Total Grades Recorded</div>
                    </div>
                    <div class="report-card">
                        <div class="report-value">{{ $gradeData['average'] }}%</div>
                        <div class="report-label">Class Average</div>
                    </div>
                    <div class="report-card">
                        <div class="report-value" style="color: #dc2626;">
                            {{ $gradeData['total'] > 0 ? round(($gradeData['distribution']['F'] / $gradeData['total']) * 100, 1) : 0 }}%
                        </div>
                        <div class="report-label">Fail Rate</div>
                    </div>
                </div>

                <div style="margin-top: 2rem;">
                    <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem;">Grade Distribution</h3>
                    @foreach ([
        'A' => ['color' => '#22c55e', 'label' => 'A (90–100%)'],
        'B' => ['color' => '#3b82f6', 'label' => 'B (80–89%)'],
        'C' => ['color' => '#eab308', 'label' => 'C (70–79%)'],
        'D' => ['color' => '#f97316', 'label' => 'D (60–69%)'],
        'F' => ['color' => '#ef4444', 'label' => 'F (< 60%)'],
    ] as $letter => $meta)
                        @php
                            $count = $gradeData['distribution'][$letter];
                            $pct = $gradeData['total'] > 0 ? round(($count / $gradeData['total']) * 100) : 0;
                        @endphp
                        <div class="bar-chart-row">
                            <div class="bar-chart-label">{{ $meta['label'] }}</div>
                            <div class="bar-chart-track">
                                <div class="bar-chart-fill"
                                    style="width: {{ $pct }}%; background-color: {{ $meta['color'] }};">
                                </div>
                            </div>
                            <div class="bar-chart-value">{{ $count }} ({{ $pct }}%)</div>
                        </div>
                    @endforeach
                </div>
            </x-filament::section>

            <x-filament::section heading="Recent Grade Records (latest 100)">
                <div style="overflow-x: auto;">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Grade</th>
                                <th>%</th>
                                <th>Recorded</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gradeData['records'] as $grade)
                                <tr>
                                    <td><strong>{{ $grade->enrollment?->user?->name ?? '—' }}</strong></td>
                                    <td>{{ $grade->course?->name ?? '—' }}</td>
                                    <td><strong>{{ $grade->letter_grade ?? '—' }}</strong></td>
                                    <td>{{ $grade->percentage ? $grade->percentage . '%' : '—' }}</td>
                                    <td>{{ $grade->recorded_at?->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 2rem; color: #9ca3af;">No
                                        grades recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-filament::section>
        @endif

        {{-- REVENUE --}}
        @if ($activeTab === 'revenue')
            @php $rev = $this->getRevenueStats(); @endphp

            <x-filament::section>
                <div class="fi-filter-row">
                    <div class="fi-filter-item" style="max-width: 200px;">
                        <label>Year</label>
                        <select wire:model.live="revenueYear">
                            @foreach ($this->getYearOptions() as $y => $label)
                                <option value="{{ $y }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="report-grid">
                    <div class="report-card">
                        <div class="report-value" style="color: #16a34a;">${{ number_format($rev['total'], 2) }}</div>
                        <div class="report-label">Total Revenue</div>
                    </div>
                    <div class="report-card">
                        <div class="report-value" style="color: #2563eb;">
                            ${{ number_format($rev['total_enrollments'], 2) }}</div>
                        <div class="report-label">Enrollment Revenue</div>
                    </div>
                    <div class="report-card">
                        <div class="report-value" style="color: #e11d48;">
                            ${{ number_format($rev['total_donations'], 2) }}</div>
                        <div class="report-label">Donations Revenue</div>
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section heading="Monthly Revenue — {{ $this->revenueYear }}">
                <div style="overflow-x: auto;">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th style="text-align: right;">Enrollment Revenue</th>
                                <th style="text-align: right;">Donations</th>
                                <th style="text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rev['monthly'] as $month)
                                @php $rowTotal = $month['enrollments'] + $month['donations']; @endphp
                                <tr>
                                    <td><strong>{{ $month['month'] }}</strong></td>
                                    <td style="text-align: right;">${{ number_format($month['enrollments'], 2) }}</td>
                                    <td style="text-align: right;">${{ number_format($month['donations'], 2) }}</td>
                                    <td
                                        style="text-align: right; color: {{ $rowTotal > 0 ? '#16a34a' : 'inherit' }}; font-weight: 700;">
                                        ${{ number_format($rowTotal, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-filament::section>

            @if ($rev['by_course']->count())
                <x-filament::section heading="Revenue by Course">
                    <div style="overflow-x: auto;">
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th style="text-align: right;">Enrollments</th>
                                    <th style="text-align: right;">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rev['by_course']->sortByDesc('revenue') as $row)
                                    <tr>
                                        <td><strong>{{ $row['course'] }}</strong></td>
                                        <td style="text-align: right;">{{ $row['enrollments'] }}</td>
                                        <td style="text-align: right; color: #16a34a; font-weight: 700;">
                                            ${{ number_format($row['revenue'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-filament::section>
            @endif

        @endif

    </div>
</x-filament-panels::page>
