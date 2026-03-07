<?php

namespace App\Filament\Resources\SIS\Certificates;

use App\Models\SIS\Enrollment;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\SIS\Certificates\Pages\ListCertificates;

class CertificateResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static ?string $modelLabel       = 'Certificate';
    protected static ?string $pluralModelLabel = 'Certificates';
    protected static ?string $slug             = 'certificates';
    protected static ?int    $navigationSort   = 40;

    public static function getNavigationGroup(): ?string
    {
        return 'Student Information (SIS)';
    }

    /**
     * Only show enrollments with an issued certificate number.
     * Completely isolated from EnrollmentResource.
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->whereNotNull('certificate_number')
            ->with(['user', 'course']);
    }

    public static function form(Schema $schema): Schema
    {
        // Read-only resource — no creation or editing.
        return $schema->components([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCertificates::route('/'),
        ];
    }
}
