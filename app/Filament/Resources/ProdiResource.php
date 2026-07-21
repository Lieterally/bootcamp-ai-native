<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdiResource\Pages;
use App\Models\Prodi;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProdiResource extends Resource
{
    protected static ?string $model = Prodi::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string | \UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Program Studi';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('fakultas_id')
                    ->relationship('fakultas', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('kode')
                    ->required()
                    ->maxLength(10)
                    ->unique(ignoreRecord: true),
                TextInput::make('nama.id')
                    ->label('Nama (ID)')
                    ->required(),
                TextInput::make('nama.en')
                    ->label('Nama (EN)'),
                Select::make('jenjang')
                    ->options([
                        'D3' => 'D3',
                        'D4' => 'D4',
                        'S1' => 'S1',
                        'S2' => 'S2',
                        'S3' => 'S3',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenjang')
                    ->badge()
                    ->sortable(),
                TextColumn::make('fakultas.nama')
                    ->sortable(),
                TextColumn::make('mahasiswa_count')
                    ->counts('mahasiswa')
                    ->label('Jumlah Mahasiswa'),
            ])
            ->filters([
                SelectFilter::make('fakultas_id')
                    ->relationship('fakultas', 'nama')
                    ->label('Fakultas'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProdis::route('/'),
            'create' => Pages\CreateProdi::route('/create'),
            'edit' => Pages\EditProdi::route('/{record}/edit'),
        ];
    }
}
