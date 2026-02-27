<?php

namespace App\Filament\Resources\Payment\DiscountCodes\Pages;

use App\Filament\Resources\Payment\DiscountCodes\DiscountCodeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDiscountCode extends CreateRecord
{
    protected static string $resource = DiscountCodeResource::class;
}
