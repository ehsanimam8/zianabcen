<?php

namespace App\Filament\Resources\LMS\Modules\Schemas;

use Filament\Schemas\Schema;

class ModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Module Info')->schema([
                    \Filament\Forms\Components\Select::make('course_id')->relationship('course', 'name')->required(),
                    \Filament\Forms\Components\TextInput::make('title')->required()->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('sequence')->numeric()->required(),
                    \Filament\Forms\Components\Toggle::make('is_published')->default(false),
                    \Filament\Forms\Components\RichEditor::make('description')->columnSpanFull(),
                    \Filament\Forms\Components\DatePicker::make('start_date'),
                    \Filament\Forms\Components\DatePicker::make('end_date'),
                ])->columns(2),
            ]);
    }
}
