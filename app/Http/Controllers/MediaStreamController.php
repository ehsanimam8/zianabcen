<?php

namespace App\Http\Controllers;

use App\Models\LMS\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MediaStreamController extends Controller
{
    /**
     * Securely streams files based on enrollment and roles
     */
    public function stream(Request $request, string $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $user = Auth::user();

        // Admin and instructors can view anything
        if ($user->hasRole(['Admin', 'Super Admin', 'Instructor'])) {
            return $this->generateStreamResponse($lesson->file_url);
        }

        // Students must be actively enrolled
        // We find the courses that contain this lesson
        $hasAccess = \App\Models\SIS\CourseAccess::join('enrollments', 'course_access.enrollment_id', '=', 'enrollments.id')
            ->join('courses', 'course_access.course_id', '=', 'courses.id')
            ->join('modules', 'courses.id', '=', 'modules.course_id')
            ->where('enrollments.user_id', $user->id)
            ->where('course_access.is_active', true)
            ->where('modules.id', $lesson->module_id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have an active enrollment allowing access to this media.');
        }

        return $this->generateStreamResponse($lesson->file_url);
    }

    protected function generateStreamResponse($path)
    {
        if (empty($path)) {
            abort(404, 'Media file not found.');
        }

        // Check if the path is a full URL (external) instead of internal storage
        if (filter_var($path, FILTER_VALIDATE_URL)) {
             return redirect()->away($path); 
             // Note: external URL gating is weak, but allowed if users paste Youtube links instead of uploading to S3
        }

        // If using actual AWS S3 integration, we generate a signed URL valid for 30 minutes
        if (config('filesystems.default') === 's3' || config('filesystems.default') === 'cloudfront') {
            $url = Storage::disk(config('filesystems.default'))->temporaryUrl(
                $path, now()->addMinutes(30)
            );
            return redirect()->away($url);
        }
        
        // Local path fallback (testing/local environments)
        if (!Storage::disk('local')->exists($path) && !Storage::disk('public')->exists($path)) {
            abort(404, 'File missing from storage disk.');
        }
        
        return Storage::response($path);
    }
}
