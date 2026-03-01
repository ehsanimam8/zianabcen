<?php

namespace App\Filament\Resources\SIS\Programs\Schemas;

use Filament\Schemas\Schema;

class ProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Program Details')->schema([
                    \Filament\Forms\Components\TextInput::make('name')->required()->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('code')->required()->unique('programs', 'code', ignoreRecord: true),
                    \Filament\Forms\Components\Select::make('level')->options([
                        'Beginner' => 'Beginner',
                        'Intermediate' => 'Intermediate',
                        'Advanced' => 'Advanced',
                    ])->required(),
                    \Filament\Forms\Components\RichEditor::make('description')->columnSpanFull(),
                    \Filament\Forms\Components\Textarea::make('prerequisites')->columnSpanFull(),
                ])->columns(2),

                \Filament\Schemas\Components\Section::make('Duration')->schema([
                    \Filament\Forms\Components\TextInput::make('duration_months')->numeric()->required(),
                    \Filament\Forms\Components\Toggle::make('is_active')->default(true),
                ])->columns(2),
            ]);
    }
}
