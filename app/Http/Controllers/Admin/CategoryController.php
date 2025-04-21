<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::with('parent')
                ->select('id', 'name', 'parent_id', 'created_at');

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('parent', function ($category) {
                    return $category->parent ? $category->parent->name : '-';
                })
                ->addColumn('action', function ($category) {
                    return '
                        <a href="'.route('categories.show', $category->id).'" class="btn btn-info btn-sm">عرض</a>
                        <a href="'.route('categories.edit', $category->id).'" class="btn btn-warning btn-sm">تعديل</a>
                        <form action="'.route('categories.destroy', $category->id).'" method="POST" style="display:inline;">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'هل أنت متأكد؟\')">حذف</button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.categories.index');
    }



    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validate request data (Laravel will automatically redirect back with errors)
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'subcategories' => 'nullable|array',
            'subcategories.*.name' => 'required_with:subcategories|string|max:255|unique:categories,name',
        ]);
        try {
            // Start database transaction
            DB::beginTransaction();

            // Create the main category
            $category = Category::create([
                'name' => $request->name,
            ]);

            if ($request->has('subcategories')) {
                foreach ($request->subcategories as $subcategory) {
                    $category->subcategories()->create([
                        'name' => $subcategory['name'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('categories.index')->with('success', __('translation.CategoryCreatedSuccessfully'));
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => __('translation.dd')])->withInput();
        }
    }

    public function show(Category $category)
    {
        $category->load('parent', 'subcategories');
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $categories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string|unique:categories,name,' . $category->id,
            'subcategories' => 'nullable|array',
            'subcategories.*.id' => 'nullable|exists:categories,id',
            'subcategories.*.name' => 'required_with:subcategories|string|max:255',
        ]);



        try {
            // Start transaction
            DB::beginTransaction();

            // Update category details
            $category->update([
                'name' => $request->name,
            ]);

            // Handle subcategories update or creation
            if ($request->has('subcategories')) {
                foreach ($request->subcategories as $subcategoryData) {
                    if (isset($subcategoryData['id'])) {
                        // Update existing subcategory
                        $subcategory = $category->subcategories()->find($subcategoryData['id']);
                        if ($subcategory) {
                            $subcategory->update([
                                'name' => $subcategoryData['name'],
                            ]);
                        }
                    } else {
                        // Create new subcategory
                        $category->subcategories()->create([
                            'name' => $subcategoryData['name'],
                        ]);
                    }
                }
            }

            // Commit transaction
            DB::commit();

            return redirect()->route('categories.index')->with('success', __('translation.CategoryUpdatedSuccessfully'));
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            return back()->withErrors(__('translation.SomethingWentWrong'))->withInput();
        }
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'تم حذف الفئة بنجاح');
    }
}
