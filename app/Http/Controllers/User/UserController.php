<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Interfaces\UserInterface;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    private UserInterface $repository;
    private UserService $service;

    public function __construct(
        UserInterface $repository,
        UserService $service
    ) {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only([
            'role',
            'name',
            'class'
        ]);

        $users = $this->repository->getAll($filters);

        return response()->json([
            'message' => 'Berhasil Menampilkan User',
            'data'    =>  $users,
        ], 201);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $this->service->prepareCreateData($request->validated());
        $user = $this->repository->create($data);

        return response()->json([
            'message' => 'User berhasil ditambahkan',
            'data'    => new UserResource($user)
        ], 201);
    }

    public function show(string $id)
    {
        $user = $this->repository->findById($id);

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, string $id)
    {
        $user = $this->repository->findById($id);

        $data = $this->service->prepareUpdateData($request->validated());
        $updated = $this->repository->update($user, $data);

        return response()->json([
            'message' => 'User berhasil diperbarui',
            'data'    => new UserResource($updated)
        ]);
    }

    public function destroy(string $id)
    {
        $user = $this->repository->findById($id);
        $this->repository->delete($user);

        return response()->json([
            'message' => 'User berhasil dihapus'
        ]);
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->only(['role', 'name', 'email']);

        return Excel::download(
            new UsersExport($filters),
            'data-users.xlsx'
        );
    }
}
