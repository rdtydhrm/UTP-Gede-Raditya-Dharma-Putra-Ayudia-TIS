<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Item",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "nama_barang", type: "string", example: "Laptop"),
        new OA\Property(property: "harga", type: "number", example: 15000000),
        new OA\Property(property: "stok", type: "integer", example: 10),
        new OA\Property(property: "created_at", type: "string", example: "2026-04-19 10:00:00"),
        new OA\Property(property: "updated_at", type: "string", example: "2026-04-19 10:00:00"),
    ]
)]
class SwaggerSchemas {}