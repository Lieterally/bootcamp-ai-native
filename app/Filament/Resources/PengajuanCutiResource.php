<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanCutiResource\Pages;
use App\Models\PengajuanCuti;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
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

class PengajuanCutiResource extends Resource
{
    protected static ?string $model = PengajuanCuti::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static string | \UnitEnum | null $navigationGroup = 'Pengajuan';

    protected static ?string $navigationLabel = 'Pengajuan Cuti';

    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Mahasiswa')
                    ->schema([
                        TextInput::make('nim')->disabled(),
                        TextInput::make('name')->disabled()->label('Nama'),
                        TextInput::make('prodi')->disabled()->label('Program Studi'),
                        TextInput::make('semester_tempuh')->disabled()->label('Semester'),
                        TextInput::make('sks_tempuh')->disabled()->label('SKS Tempuh'),
                        TextInput::make('sks_lulus')->disabled()->label('SKS Lulus'),
                        TextInput::make('dosen_wali')->disabled()->label('Dosen Wali'),
                    ])->columns(3),

                Section::make('Detail Pengajuan')
                    ->schema([
                        Textarea::make('alasan_cuti')->disabled()->label('Alasan Cuti'),
                        TextInput::make('status')->disabled(),
                        TextInput::make('catatan')->disabled()->label('Catatan Admin'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nim')
                    ->searchable()
                    ->sortable()
                    ->label('NIM'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama'),
                TextColumn::make('prodi')
                    ->sortable()
                    ->label('Prodi'),
                TextColumn::make('periodeAkademik.tahun_akademik')
                    ->label('Periode'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Menunggu Persetujuan' => 'warning',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                    }),
                TextColumn::make('submitted_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Tanggal Pengajuan'),
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
                    ->modalHeading('Setujui Pengajuan Cuti?')
                    ->modalDescription('Status mahasiswa akan diubah menjadi "Cuti".')
                    ->form([
                        Textarea::make('catatan')
                            ->label('Catatan (opsional)')
                            ->maxLength(500),
                    ])
                    ->visible(fn(PengajuanCuti $record) => $record->isMenungguPersetujuan())
                    ->action(function (PengajuanCuti $record, array $data) {
                        $mahasiswa = $record->mahasiswa;

                        if ($mahasiswa->semester_tempuh < 2) {
                            Notification::make()->title('Gagal: Mahasiswa belum menempuh 2 semester')->danger()->send();
                            return;
                        }
                        if ($mahasiswa->jumlahCutiDisetujui() >= 2) {
                            Notification::make()->title('Gagal: Kuota cuti sudah habis')->danger()->send();
                            return;
                        }

                        $record->update([
                            'status' => 'Disetujui',
                            'approved_by' => auth()->id(),
                            'catatan' => $data['catatan'] ?? null,
                            'processed_at' => now(),
                        ]);

                        $mahasiswa->update(['status_akademik' => 'Cuti']);

                        Notification::make()->title('Pengajuan cuti disetujui')->success()->send();
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pengajuan Cuti?')
                    ->form([
                        Textarea::make('catatan')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->maxLength(500),
                    ])
                    ->visible(fn(PengajuanCuti $record) => $record->isMenungguPersetujuan())
                    ->action(function (PengajuanCuti $record, array $data) {
                        $record->update([
                            'status' => 'Ditolak',
                            'approved_by' => auth()->id(),
                            'catatan' => $data['catatan'],
                            'processed_at' => now(),
                        ]);

                        Notification::make()->title('Pengajuan cuti ditolak')->success()->send();
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
            'index' => Pages\ListPengajuanCutis::route('/'),
            'view' => Pages\ViewPengajuanCuti::route('/{record}'),
        ];
    }
}
