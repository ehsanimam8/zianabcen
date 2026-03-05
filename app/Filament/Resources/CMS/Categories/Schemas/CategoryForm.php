<?php

namespace App\Filament\Resources\CMS\Categories\Schemas;

use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make()->schema([
                    \Filament\Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $operation, $state, $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                    \Filament\Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    \Filament\Forms\Components\Select::make('parent_id')
                        ->relationship('parent', 'name')
                        ->nullable()
                        ->searchable(),
                    \Filament\Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0),
                    \Filament\Forms\Components\Textarea::make('description')
                        ->columnSpanFull(),
                ])->columns(2),
            ]);
    }
}
