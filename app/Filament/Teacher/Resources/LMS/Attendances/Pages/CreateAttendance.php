<?php

namespace App\Filament\Teacher\Resources\LMS\Attendances\Pages;

use App\Filament\Teacher\Resources\LMS\Attendances\AttendanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $studentIds = (array) ($data['student_user_id'] ?? []);
        unset($data['student_user_id']);

        $lastRecord = null;
        foreach ($studentIds as $studentId) {
            $lastRecord = static::getModel()::create([
                ...$data,
                'student_user_id' => $studentId,
                'marked_by_user_id' => auth()->id(),
                'marked_at' => now(),
            ]);
        }

        return $lastRecord;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
