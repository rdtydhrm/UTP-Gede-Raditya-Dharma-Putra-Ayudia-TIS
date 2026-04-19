<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

#[OA\Info(
    version: "1.0.0",
    description: "Simple Ecommerce-like Backend API menggunakan Laravel dengan mock data JSON",
    title: "UTP TIS - Ecommerce API"
)]
#[OA\Server(
    url: "http://localhost:8000",
    description: "Local Dev Server"
)]
class ItemController extends Controller
{
    private string $filePath = 'items.json';

    private function readItems(): array
    {
        if (!Storage::exists($this->filePath)) {
            Storage::put($this->filePath, json_encode([]));
        }
        return json_decode(Storage::get($this->filePath), true) ?? [];
    }

    private function writeItems(array $items): void
    {
        Storage::put($this->filePath, json_encode(array_values($items), JSON_PRETTY_PRINT));
    }

    #[OA\Get(
        path: "/api/items",
        summary: "Tampilkan semua item",
        tags: ["Items"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar semua item"
            )
        ]
    )]
    public function index()
    {
        $items = $this->readItems();
        return response()->json([
            'success' => true,
            'data'    => $items,
        ], 200);
    }

    #[OA\Get(
        path: "/api/items/{id}",
        summary: "Tampilkan item berdasarkan ID",
        tags: ["Items"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Item ditemukan"),
            new OA\Response(response: 404, description: "Item tidak ditemukan")
        ]
    )]
    public function show(int $id)
    {
        $items = $this->readItems();
        $item  = collect($items)->firstWhere('id', $id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => "Item dengan ID {$id} tidak Ditemukan",
            ], 404);
        }

        return response()->json(['success' => true, 'data' => $item], 200);
    }

    #[OA\Post(
        path: "/api/items",
        summary: "Tambah item baru",
        tags: ["Items"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["nama_barang", "harga"],
                properties: [
                    new OA\Property(property: "nama_barang", type: "string", example: "Laptop"),
                    new OA\Property(property: "harga", type: "number", example: 15000000),
                    new OA\Property(property: "stok", type: "integer", example: 10),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Item berhasil dibuat"),
            new OA\Response(response: 422, description: "Validasi gagal")
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'sometimes|integer|min:0',
        ]);

        $items = $this->readItems();
        $newId = count($items) > 0 ? max(array_column($items, 'id')) + 1 : 1;

        $newItem = [
            'id'          => $newId,
            'nama_barang' => $validated['nama_barang'],
            'harga'       => $validated['harga'],
            'stok'        => $validated['stok'] ?? 0,
            'created_at'  => now()->toDateTimeString(),
            'updated_at'  => now()->toDateTimeString(),
        ];

        $items[] = $newItem;
        $this->writeItems($items);

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil ditambahkan',
            'data'    => $newItem,
        ], 201);
    }

    #[OA\Put(
        path: "/api/items/{id}",
        summary: "Update seluruh data item",
        tags: ["Items"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["nama_barang", "harga", "stok"],
                properties: [
                    new OA\Property(property: "nama_barang", type: "string", example: "Laptop Pro"),
                    new OA\Property(property: "harga", type: "number", example: 18000000),
                    new OA\Property(property: "stok", type: "integer", example: 5),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Item berhasil diupdate"),
            new OA\Response(response: 404, description: "Item tidak ditemukan"),
            new OA\Response(response: 422, description: "Validasi gagal")
        ]
    )]
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
        ]);

        $items = $this->readItems();
        $index = collect($items)->search(fn($i) => $i['id'] === $id);

        if ($index === false) {
            return response()->json([
                'success' => false,
                'message' => "Item dengan ID {$id} tidak Ditemukan",
            ], 404);
        }

        $items[$index] = array_merge($items[$index], $validated, [
            'updated_at' => now()->toDateTimeString(),
        ]);

        $this->writeItems($items);

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil diupdate (full)',
            'data'    => $items[$index],
        ], 200);
    }

    #[OA\Patch(
        path: "/api/items/{id}",
        summary: "Update sebagian data item",
        tags: ["Items"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "nama_barang", type: "string", example: "Laptop Pro Max"),
                    new OA\Property(property: "harga", type: "number", example: 20000000),
                    new OA\Property(property: "stok", type: "integer", example: 3),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Item berhasil diupdate sebagian"),
            new OA\Response(response: 404, description: "Item tidak ditemukan"),
            new OA\Response(response: 422, description: "Validasi gagal")
        ]
    )]
    public function patch(Request $request, int $id)
    {
        $validated = $request->validate([
            'nama_barang' => 'sometimes|string|max:255',
            'harga'       => 'sometimes|numeric|min:0',
            'stok'        => 'sometimes|integer|min:0',
        ]);

        if (empty($validated)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada field yang dikirim untuk diupdate',
            ], 422);
        }

        $items = $this->readItems();
        $index = collect($items)->search(fn($i) => $i['id'] === $id);

        if ($index === false) {
            return response()->json([
                'success' => false,
                'message' => "Item dengan ID {$id} tidak Ditemukan",
            ], 404);
        }

        $items[$index] = array_merge($items[$index], $validated, [
            'updated_at' => now()->toDateTimeString(),
        ]);

        $this->writeItems($items);

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil diupdate (partial)',
            'data'    => $items[$index],
        ], 200);
    }

    #[OA\Delete(
        path: "/api/items/{id}",
        summary: "Hapus item berdasarkan ID",
        tags: ["Items"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Item berhasil dihapus"),
            new OA\Response(response: 404, description: "Item tidak ditemukan")
        ]
    )]
    public function destroy(int $id)
    {
        $items    = $this->readItems();
        $filtered = collect($items)->reject(fn($i) => $i['id'] === $id);

        if ($filtered->count() === count($items)) {
            return response()->json([
                'success' => false,
                'message' => "Item dengan ID {$id} tidak Ditemukan",
            ], 404);
        }

        $this->writeItems($filtered->toArray());

        return response()->json([
            'success' => true,
            'message' => "Item dengan ID {$id} berhasil dihapus",
        ], 200);
    }
}