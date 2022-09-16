<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\City;
use Filament\Tables;
use App\Models\State;
use App\Models\Country;
use App\Models\Employee;
use App\Models\Department;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Dflydev\DotAccessData\Data;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EmployeeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use Filament\Tables\Filters\SelectFilter;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('firstname')
                    ->required()
                    ->autofocus()
                    ->placeholder('John')->label('First Name'),
                TextInput::make('lastname')
                    ->required()
                    ->placeholder('Vladmir')->label('Last Name'),
                Textarea::make('address')
                    ->minLength(1)
                    ->maxLength(500),
                Select::make('department_id')
                    ->label('Department')
                    ->options(Department::all()->pluck('name', 'id'))
                    ->required(),
                Select::make('country_id')
                    ->label('Country')
                    ->options(Country::all()->pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $get, Data $data) {
                        $data->set('state_id', null);
                    }),
                Select::make('state_id')
                    ->label('State/Region')
                    ->options(function (callable $get){
                        return State::where('country_id', $get('country_id'))
                                        ->get()->pluck('name', 'id')->toArray();
                    })
                    ->reactive()
                    ->afterStateUpdated(function (callable $get, Data $data) {
                        $data->set('city_id', null);
                    }),
                Select::make('city_id')
                    ->label('City')
                    ->options(function (callable $get){
                        return City::where('state_id', $get('state_id'))
                                        ->get()->pluck('name', 'id')->toArray();
                    })->required(),
                TextInput::make('zip_code')
                    ->required()
                    ->placeholder('33036')->label('Zip Code'),
                DatePicker::make('date_of_birth')
                    ->required()
                    ->placeholder('1990-01-01')
                    ->label('Date of Birth'),
                DatePicker::make('date_of_hire')
                    ->placeholder('2022-01-01')
                    ->label('Date Hired')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->label('ID'),
                TextColumn::make('firstname')
                    ->sortable()
                    ->searchable()
                    ->label('First Name'),
                TextColumn::make('lastname')
                    ->sortable()
                    ->searchable()
                    ->label('Last Name'),
                // TextColumn::make('address')->sortable()->searchable(),
                TextColumn::make('department.name')
                    ->label('Department'),
                TextColumn::make('country.name')
                    ->label('Country'),
                // TextColumn::make('state.name')
                //     ->sortable()
                //     ->searchable()
                //     ->label('State'),
                // TextColumn::make('city.name')
                //     ->sortable()
                //     ->searchable()
                //     ->label('City'),
                // TextColumn::make('zip_code')->sortable()->searchable()->label('Zip Code'),
                // TextColumn::make('date_of_birth')
                //     ->since()
                //     ->label('Age'),
                TextColumn::make('date_of_hire')
                    ->date()
                    ->label('Date Hired'),
                // TextColumn::make('created_at')->since()->label('Created'),

            ])
            ->filters([
                SelectFilter::make('department_id')
                    ->label('Department')
                    ->options(Department::all()->pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
