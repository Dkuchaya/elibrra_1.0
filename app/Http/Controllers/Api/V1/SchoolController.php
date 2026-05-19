<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\SchoolService;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function __construct(
        protected SchoolService $schoolService
    ) {}

    public function index(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $this->schoolService->paginate(
                $request->get('search', ''),
                $request->get('per_page', 10)
            ),
        ]);
    }

    public function edit(SchoolService $schoolService, $id)
{
    $school = $schoolService->find($id);

    $this->schoolId = $school->id;
    $this->name = $school->name;
    $this->email = $school->email;
    $this->phone = $school->phone;
    $this->address = $school->address;
    $this->is_active = $school->is_active;
    $this->isEditing = true;
}

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'School created successfully.',
            'data' => $this->schoolService->create($data),
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'success' => true,
            'data' => $this->schoolService->find($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'School updated successfully.',
            'data' => $this->schoolService->update($id, $data),
        ]);
    }

    public function destroy($id)
    {
        $this->schoolService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'School deleted successfully.',
        ]);
    }

    

    public function toggleStatus($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'School status updated.',
            'data' => $this->schoolService->toggleStatus($id),
        ]);
    }
}