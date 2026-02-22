<?php

namespace App\Filament\Resources\LMS\Lessons\Schemas;

use Filament\Schemas\Schema;

class LessonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Lesson Info')->schema([
                    \Filament\Forms\Components\Select::make('module_id')->relationship('module', 'title')->required(),
                    \Filament\Forms\Components\TextInput::make('title')->required()->maxLength(255),
                    \Filament\Forms\Components\Select::make('type')->options([
                        'text' => 'Text',
                        'file' => 'File',
                        'audio' => 'Audio',
                        'video' => 'Video',
                        'quiz' => 'Quiz',
                        'assignment' => 'Assignment',
                        'live_session' => 'Live Session',
                    ])->required(),
                    \Filament\Forms\Components\TextInput::make('sequence')->numeric()->required(),
                    \Filament\Forms\Components\TextInput::make('file_url')->url()->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('meeting_url')->url()->maxLength(255),
                    \Filament\Forms\Components\Toggle::make('is_published')->default(false),
                    \Filament\Forms\Components\Toggle::make('is_required')->default(true),
                    \Filament\Forms\Components\RichEditor::make('content')->columnSpanFull(),
                ])->columns(2),
            ]);
    }
}
