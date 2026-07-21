<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MahasiswaResource\Pages;
use App\Models\Mahasiswa;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MahasiswaResource extends Resource
{
    protected static ?string $model = Mahasiswa::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    protected static string | \UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Mahasiswa')
                    ->schema([
                        TextInput::make('nim')
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true)
                            ->label('NIM'),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(100)
                            ->label('Nama'),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(100),
                        Select::make('prodi_id')
                            ->relationship('prodi', 'nama')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Program Studi'),
                    ])->columns(2),

                Section::make('Akademik')
                    ->schema([
                        TextInput::make('semester_tempuh')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(14)
                            ->label('Semester Tempuh'),
                        TextInput::make('sks_tempuh')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(160)
                            ->label('SKS Tempuh'),
                        TextInput::make('sks_lulus')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(160)
                            ->label('SKS Lulus'),
                        TextInput::make('dosen_wali')
                            ->maxLength(100)
                            ->label('Dosen Wali'),
                        Select::make('status_akademik')
                            ->options([
                                'Aktif' => 'Aktif',
                                'Cuti' => 'Cuti',
                                'Mengundurkan Diri' => 'Mengundurkan Diri',
                            ])
                            ->required()
                            ->label('Status Akademik'),
                    ])->columns(3),
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
                TextColumn::make('prodi.nama')
                    ->sortable()
                    ->label('Prodi'),
                TextColumn::make('semester_tempuh')
                    ->sortable()
                    ->label('Semester'),
                TextColumn::make('status_akademik')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Cuti' => 'warning',
                        'Mengundurkan Diri' => 'danger',
                    })
                    ->label('Status'),
                TextColumn::make('dosen_wali')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Dosen Wali'),
            ])
            ->filters([
                SelectFilter::make('status_akademik')
                    ->options([
                        'Aktif' => 'Aktif',
                        'Cuti' => 'Cuti',
                        'Mengundurkan Diri' => 'Mengundurkan Diri',
                    ])
                    ->label('Status'),
                SelectFilter::make('prodi_id')
                    ->relationship('prodi', 'nama')
                    ->label('Prodi'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && $user->isAdminFakultas()) {
            $query->whereHas('prodi', function (Builder $q) use ($user) {
                $q->where('fakultas_id', $user->fakultas_id);
            });
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMahasiswas::route('/'),
            'create' => Pages\CreateMahasiswa::route('/create'),
            'edit' => Pages\EditMahasiswa::route('/{record}/edit'),
        ];
    }
}
