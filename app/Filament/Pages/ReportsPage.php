<?php

namespace App\Filament\Pages;

use App\Models\LMS\Attendance;
use App\Models\LMS\Grade;
use App\Models\SIS\Course;
use App\Models\SIS\Enrollment;
use App\Models\CRM\Donation;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class ReportsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Reports';
    protected static ?string $title = 'Advanced Reports';
    protected static ?string $slug = 'reports';
    protected static ?int $navigationSort = 99;

    protected static string $view = 'filament.pages.reports-page';

    public string $activeTab = 'attendance';

    // ── Attendance filters ─────────────────────────────────────────────────────
    public ?string $attendanceCourseId = null;
    public ?string $attendanceDateFrom = null;
    public ?string $attendanceDateTo   = null;

    // ── Grade filters ──────────────────────────────────────────────────────────
    public ?string $gradeCourseId = null;

    // ── Revenue filters ────────────────────────────────────────────────────────
    public ?string $revenueYear = null;

    public function mount(): void
    {
        $this->attendanceDateFrom = now()->startOfMonth()->toDateString();
        $this->attendanceDateTo   = now()->toDateString();
        $this->revenueYear        = now()->year;
    }

    // ── Attendance Data ────────────────────────────────────────────────────────

    public function getAttendanceStats(): array
    {
        $query = Attendance::query()
            ->with(['session.course', 'student'])
            ->whereBetween('marked_at', [
                $this->attendanceDateFrom ?? now()->startOfMonth(),
                Carbon::parse($this->attendanceDateTo ?? now())->endOfDay(),
            ]);

        if ($this->attendanceCourseId) {
            $query->whereHas('session', fn ($q) => $q->where('course_id', $this->attendanceCourseId));
        }

        $records = $query->get();

        return [
            'total'   => $records->count(),
            'present' => $records->where('status', 'present')->count(),
            'absent'  => $records->where('status', 'absent')->count(),
            'late'    => $records->where('status', 'late')->count(),
            'excused' => $records->where('status', 'excused')->count(),
            'records' => $records->take(100),
        ];
    }

    // ── Grade Distribution Data ────────────────────────────────────────────────

    public function getGradeStats(): array
    {
        $query = Grade::query()->with(['enrollment.user', 'course']);

        if ($this->gradeCourseId) {
            $query->where('course_id', $this->gradeCourseId);
        }

        $grades = $query->get();

        $distribution = [
            'A' => $grades->filter(fn ($g) => $g->percentage >= 90)->count(),
            'B' => $grades->filter(fn ($g) => $g->percentage >= 80 && $g->percentage < 90)->count(),
            'C' => $grades->filter(fn ($g) => $g->percentage >= 70 && $g->percentage < 80)->count(),
            'D' => $grades->filter(fn ($g) => $g->percentage >= 60 && $g->percentage < 70)->count(),
            'F' => $grades->filter(fn ($g) => $g->percentage < 60)->count(),
        ];

        return [
            'total'        => $grades->count(),
            'average'      => $grades->avg('percentage') ? round($grades->avg('percentage'), 1) : 0,
            'distribution' => $distribution,
            'records'      => $grades->sortByDesc('recorded_at')->take(100),
        ];
    }

    // ── Revenue Data ───────────────────────────────────────────────────────────

    public function getRevenueStats(): array
    {
        $year = (int) ($this->revenueYear ?? now()->year);

        $monthlyRevenue = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyRevenue[] = [
                'month'       => Carbon::createFromDate($year, $m, 1)->format('M'),
                'enrollments' => Enrollment::whereYear('enrolled_at', $year)
                    ->whereMonth('enrolled_at', $m)
                    ->sum('amount_paid') ?? 0,
                'donations'   => Donation::whereYear('donated_at', $year)
                    ->whereMonth('donated_at', $m)
                    ->where('status', 'completed')
                    ->sum('amount') ?? 0,
            ];
        }

        $totalEnrollments = Enrollment::whereYear('enrolled_at', $year)->sum('amount_paid') ?? 0;
        $totalDonations   = Donation::whereYear('donated_at', $year)->where('status', 'completed')->sum('amount') ?? 0;

        // Per-course breakdown
        $byCourse = Enrollment::whereYear('enrolled_at', $year)
            ->with('course')
            ->selectRaw('course_id, SUM(amount_paid) as total, COUNT(*) as enrollments_count')
            ->groupBy('course_id')
            ->get()
            ->map(fn ($row) => [
                'course'       => $row->course?->name ?? 'Unknown',
                'enrollments'  => $row->enrollments_count,
                'revenue'      => $row->total,
            ]);

        return [
            'monthly'           => $monthlyRevenue,
            'total_enrollments' => $totalEnrollments,
            'total_donations'   => $totalDonations,
            'total'             => $totalEnrollments + $totalDonations,
            'by_course'         => $byCourse,
        ];
    }

    public function getCourseOptions(): array
    {
        return Course::orderBy('name')->pluck('name', 'id')->toArray();
    }

    public function getYearOptions(): array
    {
        $years = [];
        for ($y = now()->year; $y >= 2022; $y--) {
            $years[(string) $y] = (string) $y;
        }
        return $years;
    }
}
