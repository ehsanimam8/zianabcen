<?php

namespace App\Filament\Resources\CMS\Posts;

use App\Filament\Resources\CMS\Posts\Pages\CreatePost;
use App\Filament\Resources\CMS\Posts\Pages\EditPost;
use App\Filament\Resources\CMS\Posts\Pages\ListPosts;
use App\Filament\Resources\CMS\Posts\Schemas\PostForm;
use App\Filament\Resources\CMS\Posts\Tables\PostsTable;
use App\Models\CMS\Post;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationGroup(): ?string
    {
        return 'Content Management (CMS)';
    }


    

    public static function form(Schema $schema): Schema
    {
        return PostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostsTable::configure($table);
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
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
