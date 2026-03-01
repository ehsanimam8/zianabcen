<?php

namespace App\Filament\Resources\SIS\Courses\Schemas;

use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Course Details')->schema([
                    \Filament\Forms\Components\TextInput::make('name')->required()->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('code')->required()->unique('courses', 'code', ignoreRecord: true),
                    \Filament\Forms\Components\Select::make('programs')->relationship('programs', 'name')->multiple()->preload(),
                    \Filament\Forms\Components\Select::make('term_id')->relationship('term', 'name'),
                    \Filament\Forms\Components\TextInput::make('credits')->numeric()->required(),
                    \Filament\Forms\Components\TextInput::make('capacity')->numeric(),
                    \Filament\Forms\Components\TextInput::make('price')->numeric()->prefix('$')->default(0),
                    \Filament\Forms\Components\Select::make('billing_cycle')->options([
                        'one_time' => 'One-time',
                        'monthly' => 'Monthly',
                        'quarterly' => 'Quarterly',
                        'annually' => 'Annually',
                    ])->default('one_time'),
                    \Filament\Forms\Components\Toggle::make('is_active')->default(true)->columnSpanFull(),
                ])->columns(2),
                \Filament\Schemas\Components\Section::make('Description')->schema([
                    \Filament\Forms\Components\RichEditor::make('description'),
                ]),
            ]);
    }
}
