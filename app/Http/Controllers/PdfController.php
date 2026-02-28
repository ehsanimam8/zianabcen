<?php

namespace App\Http\Controllers;

use App\Models\SIS\Course;
use App\Models\SIS\Program;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PdfController extends Controller
{
    public function transcript(User $student)
    {
        // Must be self or admin
        if (Auth::id() !== $student->id && !Auth::user()->hasRole(['Super Admin', 'Admin', 'admin', 'super_admin'])) {
            abort(403, 'Unauthorized');
        }

        $enrollments = \App\Models\SIS\Enrollment::where('user_id', $student->id)
            ->with(['program', 'term'])
            ->get();

        $grades = \App\Models\LMS\Grade::whereIn('enrollment_id', $enrollments->pluck('id'))
            ->with(['course', 'enrollment.term'])
            ->orderBy('recorded_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('pdf.transcript', [
            'student' => $student,
            'enrollments' => $enrollments,
            'grades' => $grades,
            'tenant' => tenant()
        ]);

        return $pdf->download('Transcript_' . $student->name . '.pdf');
    }

    public function certificate(User $student, $type, $id)
    {
        // Must be self or admin
        if (Auth::id() !== $student->id && !Auth::user()->hasRole(['Super Admin', 'Admin', 'admin', 'super_admin'])) {
            abort(403, 'Unauthorized');
        }

        $entity = null;
        if ($type === 'course') {
            $entity = Course::findOrFail($id);
        } elseif ($type === 'program') {
            $entity = Program::findOrFail($id);
        } else {
            abort(404);
        }

        $pdf = Pdf::loadView('pdf.certificate', [
            'student' => $student,
            'entity' => $entity,
            'type' => $type,
            'tenant' => tenant()
        ]);
        
        // Use landscape for certificate
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Certificate_' . $student->name . '_' . $entity->name . '.pdf');
    }
}
