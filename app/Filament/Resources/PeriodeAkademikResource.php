<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeriodeAkademikResource\Pages;
use App\Models\PeriodeAkademik;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PeriodeAkademikResource extends Resource
{
    protected static ?string $model = PeriodeAkademik::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string | \UnitEnum | null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Periode Akademik';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Periode')
                    ->schema([
                        TextInput::make('tahun_akademik')
                            ->required()
                            ->maxLength(9)
                            ->placeholder('2024/2025')
                            ->helperText('Format: YYYY/YYYY'),
                        Select::make('semester')
                            ->options([
                                'Ganjil' => 'Ganjil',
                                'Genap' => 'Genap',
                            ])
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Periode Aktif')
                            ->helperText('Hanya satu periode yang dapat aktif pada satu waktu'),
                    ])->columns(3),

                Section::make('Rentang Pengajuan Cuti')
                    ->schema([
                        DatePicker::make('tanggal_buka_cuti')
                            ->label('Tanggal Buka'),
                        DatePicker::make('tanggal_tutup_cuti')
                            ->label('Tanggal Tutup')
                            ->after('tanggal_buka_cuti'),
                    ])->columns(2),

                Section::make('Rentang Pengajuan Aktif Studi')
                    ->schema([
                        DatePicker::make('tanggal_buka_aktif_studi')
                            ->label('Tanggal Buka'),
                        DatePicker::make('tanggal_tutup_aktif_studi')
                            ->label('Tanggal Tutup')
                            ->after('tanggal_buka_aktif_studi'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tahun_akademik')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('semester')
                    ->badge()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Aktif'),
                TextColumn::make('tanggal_buka_cuti')
                    ->date()
                    ->label('Buka Cuti'),
                TextColumn::make('tanggal_tutup_cuti')
                    ->date()
                    ->label('Tutup Cuti'),
                TextColumn::make('tanggal_buka_aktif_studi')
                    ->date()
                    ->label('Buka Aktif Studi'),
                TextColumn::make('tanggal_tutup_aktif_studi')
                    ->date()
                    ->label('Tutup Aktif Studi'),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('activate')
                    ->label('Set Aktif')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Aktifkan Periode Ini?')
                    ->modalDescription('Periode aktif sebelumnya akan dinonaktifkan.')
                    ->visible(fn(PeriodeAkademik $record) => !$record->is_active)
                    ->action(function (PeriodeAkademik $record) {
                        PeriodeAkademik::where('is_active', true)->update(['is_active' => false]);
                        $record->update(['is_active' => true]);
                    }),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeriodeAkademiks::route('/'),
            'create' => Pages\CreatePeriodeAkademik::route('/create'),
            'edit' => Pages\EditPeriodeAkademik::route('/{record}/edit'),
        ];
    }
}
