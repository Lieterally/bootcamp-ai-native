<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanAktifStudiResource\Pages;
use App\Models\PengajuanAktifStudi;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PengajuanAktifStudiResource extends Resource
{
    protected static ?string $model = PengajuanAktifStudi::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrow-path';

    protected static string | \UnitEnum | null $navigationGroup = 'Pengajuan';

    protected static ?string $navigationLabel = 'Pengajuan Aktif Studi';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dokumen')
                    ->schema([
                        FileUpload::make('file_khs')->disabled()->label('KHS'),
                        FileUpload::make('file_bukti_ukt')->disabled()->label('Bukti UKT'),
                    ])->columns(2),
                Section::make('Status')
                    ->schema([
                        TextInput::make('status')->disabled(),
                        TextInput::make('catatan')->disabled()->label('Catatan'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mahasiswa.nim')->searchable()->sortable()->label('NIM'),
                TextColumn::make('mahasiswa.name')->searchable()->sortable()->label('Nama'),
                TextColumn::make('mahasiswa.prodi.nama')->sortable()->label('Prodi'),
                TextColumn::make('periodeAkademik.tahun_akademik')->label('Periode'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Menunggu Persetujuan' => 'warning',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                    }),
                TextColumn::make('submitted_at')->dateTime()->sortable()->label('Tanggal Pengajuan'),
            ])
            ->defaultSort('submitted_at', 'asc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Menunggu Persetujuan' => 'Menunggu Persetujuan',
                        'Disetujui' => 'Disetujui',
                        'Ditolak' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->form([
                        Textarea::make('catatan')->label('Catatan (opsional)')->maxLength(500),
                    ])
                    ->visible(fn(PengajuanAktifStudi $record) => $record->isMenungguPersetujuan())
                    ->action(function (PengajuanAktifStudi $record, array $data) {
                        $record->update([
                            'status' => 'Disetujui',
                            'approved_by' => auth()->id(),
                            'catatan' => $data['catatan'] ?? null,
                            'processed_at' => now(),
                        ]);
                        $record->mahasiswa->update(['status_akademik' => 'Aktif']);
                        Notification::make()->title('Pengajuan aktif studi disetujui')->success()->send();
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        Textarea::make('catatan')->label('Alasan Penolakan')->required()->maxLength(500),
                    ])
                    ->visible(fn(PengajuanAktifStudi $record) => $record->isMenungguPersetujuan())
                    ->action(function (PengajuanAktifStudi $record, array $data) {
                        $record->update([
                            'status' => 'Ditolak',
                            'approved_by' => auth()->id(),
                            'catatan' => $data['catatan'],
                            'processed_at' => now(),
                        ]);
                        Notification::make()->title('Pengajuan aktif studi ditolak')->success()->send();
                    }),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && $user->isAdminFakultas()) {
            $query->whereHas('mahasiswa.prodi', function (Builder $q) use ($user) {
                $q->where('fakultas_id', $user->fakultas_id);
            });
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengajuanAktifStudis::route('/'),
            'view' => Pages\ViewPengajuanAktifStudi::route('/{record}'),
        ];
    }
}
