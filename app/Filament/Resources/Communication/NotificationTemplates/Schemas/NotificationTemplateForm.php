<?php

namespace App\Filament\Resources\Communication\NotificationTemplates\Schemas;

use Filament\Schemas\Schema;

class NotificationTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Template Details')->schema([
                    \Filament\Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('trigger_event')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->helperText('The system event that triggers this notification (e.g., student.enrolled).'),
                    \Filament\Forms\Components\TextInput::make('subject')
                        ->required()
                        ->maxLength(255),
                    \Filament\Forms\Components\RichEditor::make('body')
                        ->required()
                        ->columnSpanFull()
                        ->helperText('You can use dynamic tags like [FIRST_NAME], [LAST_NAME], [COURSE_TITLE].'),
                    \Filament\Forms\Components\Textarea::make('description')
                        ->maxLength(65535)
                        ->columnSpanFull(),
                    \Filament\Forms\Components\Toggle::make('is_active')
                        ->default(true),
                ])->columns(2),
            ]);
    }
}
