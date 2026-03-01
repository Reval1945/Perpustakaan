<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use App\Interfaces\BookInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Requests\Book\StoreBookRequest;
use App\Http\Requests\Book\UpdateBookRequest;
use App\Exports\BooksExport;
use Maatwebsite\Excel\Facades\Excel;

class BookController extends Controller
{
    private BookInterface $repository;

    public function __construct(BookInterface $repository,)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only([
                'judul',
                'kode_buku',
                'rak',
                'nomor_rak',
                'category'
            ]);

            return BookResource::collection(
                $this->repository->getAll($filters)
            );
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal mengambil data buku',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function store(StoreBookRequest $request)
    {
        try {
            $book = $this->repository->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil ditambahkan',
                'data' => new BookResource($book)
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan buku',
                'error' => $e->getMessage()
            ], 500);
        }
        catch (Throwable $e) {
            return $e->getMessage();
        }
    }

    public function show (string $id){
        try {
            return new BookResource(
                $this->repository->findById($id)
            );
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal mengambil detail buku',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateBookRequest $request, string $id)
    {
        try {
            $book = $this->repository->findById($id);
            $updated = $this->repository->update($book, $request->validated());

            return response()->json([
                'message' => 'Buku berhasil diperbarui',
                'data'    => new BookResource($updated)
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal memperbarui buku',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $book = $this->repository->findById($id);
            $this->repository->delete($book);

            return response()->json([
                'message' => 'Buku berhasil dihapus'
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal menghapus buku',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $filters = $request->only([
                'judul',
                'kode_buku',
                'rak',
                'nomor_rak',
                'category'
            ]);

            return Excel::download(
                new BooksExport($filters),
                'data-buku.xlsx'
            );
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Gagal export data buku',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
