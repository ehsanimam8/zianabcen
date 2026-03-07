<?php

namespace App\Filament\Resources\SIS\Certificates;

use App\Models\SIS\Enrollment;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use App\Filament\Resources\SIS\Certificates\Pages\ListCertificates;

class CertificateResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $navigationIcon   = 'heroicon-o-document-check';
    protected static ?string $navigationGroup  = 'SIS';
    protected static ?string $navigationLabel  = 'Certificates';
    protected static ?string $modelLabel       = 'Certificate';
    protected static ?string $pluralModelLabel = 'Certificates';
    protected static ?string $slug            = 'certificates';
    protected static ?int    $navigationSort  = 40;

    /**
     * Only show enrollments that have an issued certificate number.
     * This keeps the resource completely isolated from EnrollmentResource.
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->whereNotNull('certificate_number')
            ->with(['user', 'course']);
    }

    public static function form(Schema $schema): Schema
    {
        // Certificates are read-only — no creation or editing via this resource.
        return $schema->components([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCertificates::route('/'),
        ];
    }
}
