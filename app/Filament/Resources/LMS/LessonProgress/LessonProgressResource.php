<?php

namespace App\Filament\Resources\LMS\LessonProgress;

use App\Filament\Resources\LMS\LessonProgress\Pages\CreateLessonProgress;
use App\Filament\Resources\LMS\LessonProgress\Pages\EditLessonProgress;
use App\Filament\Resources\LMS\LessonProgress\Pages\ListLessonProgress;
use App\Filament\Resources\LMS\LessonProgress\Schemas\LessonProgressForm;
use App\Filament\Resources\LMS\LessonProgress\Tables\LessonProgressTable;
use App\Models\LMS\LessonProgress;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LessonProgressResource extends Resource
{
    protected static ?string $model = LessonProgress::class;

    protected static $navigationIcon = Heroicon::OutlinedChartBar;

    public static function getNavigationGroup(): ?string
    {
        return 'LMS';
    }

    public static function form(Schema $schema): Schema
    {
        return LessonProgressForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LessonProgressTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLessonProgress::route('/'),
            'create' => CreateLessonProgress::route('/create'),
            'edit' => EditLessonProgress::route('/{record}/edit'),
        ];
    }
}
