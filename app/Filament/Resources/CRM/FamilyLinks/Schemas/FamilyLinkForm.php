<?php

namespace App\Filament\Resources\CRM\FamilyLinks\Schemas;

use Filament\Schemas\Schema;

class FamilyLinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('parent_user_id')
                    ->label('Parent / Guardian')
                    ->relationship('parent', 'name', fn (\Illuminate\Database\Eloquent\Builder $query) => $query->whereHas('roles', fn ($q) => $q->where('name', 'Guardian')))
                    ->getOptionLabelFromRecordUsing(fn (\App\Models\User $record) => "{$record->name} ({$record->email})")
                    ->searchable(['name', 'email'])
                    ->required()
                    ->createOptionForm([
                        \Filament\Forms\Components\TextInput::make('name')->required(),
                        \Filament\Forms\Components\TextInput::make('email')->email()->required()->unique('users', 'email'),
                        \Filament\Forms\Components\TextInput::make('password')->password()->required(),
                        \Filament\Forms\Components\Hidden::make('role')->default('Guardian'),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $user = \App\Models\User::create($data);
                        $user->assignRole('Guardian');
                        return $user->id;
                    }),
                \Filament\Forms\Components\Select::make('student_user_id')
                    ->label('Student')
                    ->relationship('student', 'name', fn (\Illuminate\Database\Eloquent\Builder $query) => $query->whereHas('roles', fn ($q) => $q->where('name', 'Student')))
                    ->getOptionLabelFromRecordUsing(fn (\App\Models\User $record) => "{$record->name} ({$record->email})")
                    ->searchable(['name', 'email'])
                    ->required()
                    ->createOptionForm([
                        \Filament\Forms\Components\TextInput::make('name')->required(),
                        \Filament\Forms\Components\TextInput::make('email')->email()->required()->unique('users', 'email'),
                        \Filament\Forms\Components\TextInput::make('password')->password()->required(),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $user = \App\Models\User::create($data);
                        $user->assignRole('Student');
                        return $user->id;
                    }),
                \Filament\Forms\Components\Select::make('relationship')
                    ->options([
                        'father' => 'Father',
                        'mother' => 'Mother',
                        'guardian' => 'Guardian',
                        'other' => 'Other',
                    ])
                    ->required(),
            ]);
    }
}
