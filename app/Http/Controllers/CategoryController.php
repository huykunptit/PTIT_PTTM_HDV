<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch categories from the external API
        $response = Http::get(env('GATEWAY_URL') . '/api/category/categories');
        $data = $response->json();

        // Prepare the toast message
        $toast = [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Danh sách danh mục đã được tải thành công.'
                : ($data['message'] ?? 'Không thể tải danh sách danh mục. Vui lòng thử lại.')
        ];

        // Store the toast message in session
        session()->flash('toast', $toast);

        // Return the view with categories data
        return view('admin.category.index', compact('data'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteCategory($id, Request $request)
    {
        // Validate the category ID
        $request->validate(['category_id' => 'required|integer']);
        
        // Perform the delete request to the external API
        $response = Http::delete(env('GATEWAY_URL') . "/api/category/categories/{$id}");
        $data = $response->json();

        // Prepare the toast message
        $toast = [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Danh mục đã được xóa thành công.'
                : ($data['message'] ?? 'Không thể xóa danh mục. Vui lòng thử lại.')
        ];

        // Store the toast message in session
        session()->flash('toast', $toast);

        // Redirect back to the category management page with the toast message
        return redirect()->route('categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'parent_id' => 'nullable|integer'
        ]);

        // Send the create request to the external API
        $response = Http::post(env('GATEWAY_URL') . '/api/category/categories', $validated);
        $data = $response->json();

        // Prepare the toast message
        $toast = [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Danh mục đã được tạo thành công.'
                : ($data['message'] ?? 'Không thể tạo danh mục. Vui lòng thử lại.')
        ];

        // Store the toast message in session
        session()->flash('toast', $toast);

        // Redirect to the category index page
        return redirect()->route('categories.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.category.edit', ['id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the input data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'parent_id' => 'nullable|integer',
        ]);

        // Send the update request to the external API
        $response = Http::put(env('GATEWAY_URL') . "/api/category/categories/{$id}", $validated);
        $data = $response->json();

        // Prepare the toast message
        $toast = [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Danh mục đã được cập nhật thành công.'
                : ($data['message'] ?? 'Không thể cập nhật danh mục. Vui lòng thử lại.')
        ];

        // Store the toast message in session
        session()->flash('toast', $toast);

        // Redirect to the category index page
        return redirect()->route('categories.index');
    }

    //write function destroy to fix Method App\Http\Controllers\CategoryController::destroy does not exist.
    public function destroy(string $id)
    {
        // Send the delete request to the external API
        $response = Http::delete(env('GATEWAY_URL') . "/api/category/categories/{$id}");
        $data = $response->json();

        // Prepare the toast message
        $toast = [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Danh mục đã được xóa thành công.'
                : ($data['message'] ?? 'Không thể xóa danh mục. Vui lòng thử lại.')
        ];

        // Store the toast message in session
        session()->flash('toast', $toast);

        // Redirect to the category index page
        return redirect()->route('categories.index');
    }
}
