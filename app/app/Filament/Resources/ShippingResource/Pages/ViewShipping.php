<?php

namespace App\Filament\Resources\ShippingResource\Pages;

use App\Filament\Resources\ShippingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewShipping extends ViewRecord
{
    protected static string $resource = ShippingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
