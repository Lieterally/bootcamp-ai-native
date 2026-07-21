<?php

namespace App\Filament\Resources\FakultasResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProdiRelationManager extends RelationManager
{
    protected static string $relationship = 'prodi';

    protected static ?string $title = 'Program Studi';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')->sortable(),
                TextColumn::make('nama')->sortable()->searchable(),
                TextColumn::make('jenjang')->badge(),
                TextColumn::make('mahasiswa_count')
                    ->counts('mahasiswa')
                    ->label('Mahasiswa'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}
