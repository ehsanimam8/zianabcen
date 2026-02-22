<?php

namespace App\Filament\Resources\CMS\Posts\Schemas;

use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Post Info')->schema([
                    \Filament\Forms\Components\Select::make('post_type')->options([
                        'page' => 'Page',
                        'post' => 'Post',
                        'event' => 'Event',
                    ])->required()->default('post'),
                    \Filament\Forms\Components\TextInput::make('title')->required()->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true),
                    \Filament\Forms\Components\TextInput::make('featured_image_url')->url()->maxLength(255),
                    \Filament\Forms\Components\Select::make('status')->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ])->required()->default('draft'),
                    \Filament\Forms\Components\DateTimePicker::make('published_at'),
                    \Filament\Forms\Components\DateTimePicker::make('scheduled_publish_at'),
                    \Filament\Forms\Components\Toggle::make('is_sticky')->default(false),
                    \Filament\Forms\Components\Select::make('author_user_id')->relationship('author', 'name')->required(),
                    \Filament\Forms\Components\RichEditor::make('content')->columnSpanFull(),
                    \Filament\Forms\Components\Textarea::make('excerpt')->columnSpanFull(),
                ])->columns(2),
            ]);
    }
}
