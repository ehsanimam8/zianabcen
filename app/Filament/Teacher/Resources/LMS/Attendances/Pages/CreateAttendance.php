<?php

namespace App\Filament\Teacher\Resources\LMS\Attendances\Pages;

use App\Filament\Teacher\Resources\LMS\Attendances\AttendanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;
}
