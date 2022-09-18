<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->autofocus()
                    ->placeholder('Moses Mutagana')
                    ->label('Full Name'),
                TextInput::make('email')
                    ->required()
                    ->autofocus()
                    ->placeholder('moses@mutagana.com')
                    ->label('Email')
                    ->type('email'),
                TextInput::make('password')
                    ->password()
                    ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                    ->minLength(8)
                    ->same('password_confirmation')
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->label('Password'),
                TextInput::make('password_confirmation')
                    ->label('Confirm Password')
                    ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                    ->minLength(8)
                    ->dehydrated(false)
                    ->password(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->label('ID'),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Names'),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->since()
                    ->label('Last Updated'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
