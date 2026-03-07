<?php

namespace App\Http\Controllers;

use App\Models\SIS\Course;
use App\Models\SIS\Enrollment;
use App\Models\SIS\Program;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PdfController extends Controller
{
    public function transcript(User $student)
    {
        if (Auth::id() !== $student->id && ! Auth::user()->hasRole(['Super Admin', 'Admin'])) {
            abort(403, 'Unauthorized');
        }

        $enrollments = Enrollment::where('user_id', $student->id)
            ->with(['course', 'term'])
            ->get();

        $grades = \App\Models\LMS\Grade::whereIn('enrollment_id', $enrollments->pluck('id'))
            ->with(['course', 'enrollment.term'])
            ->orderBy('recorded_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('pdf.transcript', [
            'student'     => $student,
            'enrollments' => $enrollments,
            'grades'      => $grades,
            'tenant'      => tenant(),
        ]);

        return $pdf->download('Transcript_' . $student->name . '.pdf');
    }

    public function certificate(User $student, string $type, string $id)
    {
        if (Auth::id() !== $student->id && ! Auth::user()->hasRole(['Super Admin', 'Admin'])) {
            abort(403, 'Unauthorized');
        }

        $entity = match ($type) {
            'course'  => Course::findOrFail($id),
            'program' => Program::findOrFail($id),
            default   => abort(404),
        };

        // Find the enrollment so we can persist a stable certificate number
        $enrollment = null;
        if ($type === 'course') {
            $enrollment = Enrollment::where('user_id', $student->id)
                ->where('course_id', $entity->id)
                ->first();
        } else {
            // Program level: find ANY enrollment for a course belonging to this program
            $courseIds = $entity->courses()->pluck('courses.id');
            $enrollment = Enrollment::where('user_id', $student->id)
                ->whereIn('course_id', $courseIds)
                ->first();
        }

        // Generate once and store; reuse on subsequent downloads
        if ($enrollment) {
            if (empty($enrollment->certificate_number)) {
                $enrollment->certificate_number = 'CERT-' . date('Y') . '-' . strtoupper(Str::random(8));
                $enrollment->saveQuietly();
            }
            $certificateNumber = $enrollment->certificate_number;
        } else {
            // Fallback if no enrollment found at all
            $certificateNumber = 'CERT-' . date('Y') . '-' . strtoupper(Str::random(8));
        }

        $pdf = Pdf::loadView('pdf.certificate', [
            'student'            => $student,
            'entity'             => $entity,
            'type'               => $type,
            'tenant'             => tenant(),
            'certificate_number' => $certificateNumber,
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Certificate_' . $student->name . '_' . $entity->name . '.pdf');
    }
}
