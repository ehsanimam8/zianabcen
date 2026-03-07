<?php

namespace App\Filament\Resources\SIS\Certificates\Pages;

use App\Filament\Resources\SIS\Certificates\CertificateResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Notifications\Notification;

class ListCertificates extends ListRecords
{
    protected static string $resource = CertificateResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('course.name')
                    ->label('Course')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('certificate_number')
                    ->label('Certificate #')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono'),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('enrolled_at')
                    ->label('Enrolled')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('completed_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('course')
                    ->relationship('course', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Action::make('download')
                    ->label('Download PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn ($record): string => route('student.certificate.download', [
                        'student' => $record->user_id,
                        'type'    => 'course',
                        'id'      => $record->course_id,
                    ]))
                    ->openUrlInNewTab(),

                Action::make('revoke')
                    ->label('Revoke')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Revoke Certificate')
                    ->modalDescription('This will permanently clear the certificate number. The student will no longer be able to download their certificate. This cannot be undone automatically.')
                    ->modalSubmitActionLabel('Yes, Revoke')
                    ->action(function ($record): void {
                        $record->update(['certificate_number' => null]);

                        Notification::make()
                            ->title('Certificate revoked')
                            ->body("Certificate #{$record->certificate_number} has been revoked.")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulk_revoke')
                        ->label('Revoke Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Revoke Certificates')
                        ->modalDescription('This will revoke all selected certificates. Students will no longer be able to download them.')
                        ->action(function ($records): void {
                            $records->each(fn ($r) => $r->update(['certificate_number' => null]));

                            Notification::make()
                                ->title('Certificates revoked')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
