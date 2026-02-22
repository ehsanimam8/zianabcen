<?php

namespace App\Filament\Resources\LMS\CourseAnnouncements\Schemas;

use Filament\Schemas\Schema;

class CourseAnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Announcement Details')->schema([
                    \Filament\Forms\Components\Select::make('course_id')
                        ->relationship('course', 'name')
                        ->required(),
                    \Filament\Forms\Components\Select::make('instructor_user_id')
                        ->relationship('instructor', 'name')
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    \Filament\Forms\Components\Toggle::make('is_published')
                        ->default(true),
                    \Filament\Forms\Components\Toggle::make('send_email_notification')
                        ->default(false),
                    \Filament\Forms\Components\RichEditor::make('content')
                        ->required()
                        ->columnSpanFull(),
                ])->columns(2),
            ]);
    }
}
