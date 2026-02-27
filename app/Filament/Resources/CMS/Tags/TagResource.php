<?php

namespace App\Filament\Resources\CMS\Tags;

use App\Filament\Resources\CMS\Tags\Pages\CreateTag;
use App\Filament\Resources\CMS\Tags\Pages\EditTag;
use App\Filament\Resources\CMS\Tags\Pages\ListTags;
use App\Filament\Resources\CMS\Tags\Schemas\TagForm;
use App\Filament\Resources\CMS\Tags\Tables\TagsTable;
use App\Models\CMS\Tag;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-tag';

    public static function getNavigationGroup(): ?string
    {
        return 'Content Management (CMS)';
    }

        public static function form(Schema $schema): Schema
    {
        return TagForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TagsTable::configure($table);
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
            'index' => ListTags::route('/'),
            'create' => CreateTag::route('/create'),
            'edit' => EditTag::route('/{record}/edit'),
        ];
    }
}
