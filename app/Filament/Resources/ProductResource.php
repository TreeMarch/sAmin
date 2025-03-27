<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\{TextInput, Select, Group, MarkdownEditor, Section};
use Filament\Tables\Columns\{TextColumn, ImageColumn, SelectColumn};
use Filament\Tables\Filters\{Filter, SelectFilter, Layout};
use Filament\Forms\Components\DatePicker;
use App\Models\User;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Forms\Components\Toggle;
use App\Filament\Resources\UserResource\RelationManagers\UserProductsRelationManager;
class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = "Sản phẩm";
    protected static ?string $navigationIcon = 'heroicon-m-squares-2x2';

    public static function getNavigationBadge(): ?string
    {
        return Product::query()->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getNavigationBadge() > 5 ? 'success' : 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $discount = $get('discount') ?? 0;
                                $finalPrice = $state - ($state * ($discount / 100));
                                $set('final_price', $finalPrice);
                            }),
                        TextInput::make('discount')
                            ->numeric()
                            ->default(0) // Giá trị mặc định cho discount
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $price = $get('price') ?? 0;
                                $finalPrice = $price - ($price * ($state / 100));
                                $set('final_price', $finalPrice);
                            }),
                        TextInput::make('final_price')
                            ->label('Giá cuối cùng')
                            ->disabled() // Không cho phép chỉnh sửa trực tiếp
                            ->dehydrated(false) // Không lưu giá trị này vào database
                            ->afterStateHydrated(function (TextInput $component, $state, Forms\Get $get) {
                                // Tính toán giá cuối cùng khi form được hydrate (lần đầu)
                                $price = $get('price') ?? 0;
                                $discount = $get('discount') ?? 0;

                                $finalPrice = $price - ($price * ($discount / 100));
                                $component->state($finalPrice);
                            }),
                        Toggle::make('isActive')
                            ->label('Active')
                            ->default(true), // Trạng thái hoạt động mặc định là true
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required(),
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'archived' => 'Archived',
                            ])
                            ->required(),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required(),
                        MarkdownEditor::make('description')
                            ->columnSpan(3)
                            ->required()
                            ->columnSpanFull(),
                    ])->columnSpan(1)->columns(2),

                Group::make()->schema([
                    Section::make("Image")
                        ->collapsible()
                        ->schema([
                            Forms\Components\FileUpload::make('image')
                                ->image()
                                ->directory('public')
                                ->multiple()
                                ->reorderable()
                                ->label('Hình ảnh sản phẩm'),
                        ])->columnSpan(1),
                ])
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->width('600')
                    ->circular()
                    ->disk('public')
                    ->stacked()
                    ->visibility('public'),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('price')
                    ->sortable()
                    ->money('VND'),
                TextColumn::make('discount')
                    ->sortable()
                    ->label('Discount (%)'), // Hiển thị giảm giá
                ToggleColumn::make('isActive')
                    ->label('Active'),
                TextColumn::make('category.name')
                    ->label('Category name')
                    ->sortable()
                    ->searchable(),
                SelectColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->options([
                        'archived' => 'Archived',
                        'published' => 'Published',
                        'draft' => 'Draft',
                    ])
                    ->searchable()
                    ->default('draft')
                    ->selectablePlaceholder(false),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User'),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // UserProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
