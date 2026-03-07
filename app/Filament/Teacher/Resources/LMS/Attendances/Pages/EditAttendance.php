<?php

namespace App\Filament\Teacher\Resources\LMS\Attendances\Pages;

use App\Filament\Teacher\Resources\LMS\Attendances\AttendanceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAttendance extends EditRecord
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['student_user_id'] = (array) ($data['student_user_id'] ?? []);
        return $data;
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $studentIds = (array) ($data['student_user_id'] ?? []);
        $data['student_user_id'] = $studentIds[0] ?? null;

        $record->update($data);

        return $record;
    }
}
