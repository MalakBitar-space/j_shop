<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceCategoryController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $categories = ServiceCategory::all()->map(function ($category) {
            $photoUrl = $category->getFirstMediaUrl('category_image');
            if (empty($photoUrl)) {
                $photoUrl = asset('images/default-category.jpg');
            }
            return [
                'id' => $category->id,
                'category_name' => $category->category_name,
                'category_desc' => $category->category_desc,
                'photo_url' => $photoUrl,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
            ];
        });
        return $this->successResponse(['categories' => $categories], 'تم جلب جميع التصنيفات بنجاح');
    }

    public function show($id)
    {
        $category = ServiceCategory::find($id);
        if (!$category) {
            return $this->errorResponse('التصنيف غير موجود', 404);
        }
        $photoUrl = $category->getFirstMediaUrl('category_image');
        if (empty($photoUrl)) {
            $photoUrl = asset('images/default-category.jpg');
        }
        return $this->successResponse([
            'category' => [
                'id' => $category->id,
                'category_name' => $category->category_name,
                'category_desc' => $category->category_desc,
                'photo_url' => $photoUrl,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
            ]
        ], 'تم جلب التصنيف بنجاح');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|unique:service_categories,category_name',
            'category_desc' => 'required|string', // removed unique constraint
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $category = ServiceCategory::create($validated);
        if ($request->hasFile('photo')) {
            $category->addMediaFromRequest('photo')->toMediaCollection('category_image');
        }
        $photoUrl = $category->getFirstMediaUrl('category_image');
        if (empty($photoUrl)) {
            $photoUrl = asset('images/default-category.jpg');
        }
        return $this->successResponse([
            'category' => [
                'id' => $category->id,
                'category_name' => $category->category_name,
                'category_desc' => $category->category_desc,
                'photo_url' => $photoUrl,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
            ]
        ], 'تم إنشاء التصنيف بنجاح', 201);
    }

    // public function update(Request $request, $id)
    // {
    //     $category = ServiceCategory::find($id);
    //     if (!$category) {
    //         return $this->errorResponse('التصنيف غير موجود', 404);
    //     }

    //     $validator = Validator::make($request->all(), [
    //         'category_name' => 'nullable|string|unique:service_categories,category_name,' . $id,
    //         'category_desc' => 'nullable|string|unique:service_categories,category_desc,' . $id,
    //         'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    //     ]);

    //     if ($validator->fails()) {
    //         return $this->errorResponse($validator->errors()->first(), 422);
    //     }

    //     $data = $validator->validated();

    //     if (isset($data['category_name'])) {
    //         $category->category_name = $data['category_name'];
    //     }
    //     if (isset($data['category_desc'])) {
    //         $category->category_desc = $data['category_desc'];
    //     }

    //     if ($request->hasFile('photo')) {
    //         $category->clearMediaCollection('category_image');
    //         $category->addMediaFromRequest('photo')->toMediaCollection('category_image');
    //     }

    //     $category->save();

    //     $photoUrl = $category->getFirstMediaUrl('category_image');
    //     if (empty($photoUrl)) {
    //         $photoUrl = asset('images/default-category.jpg');
    //     }

    //     return $this->successResponse([
    //         'category' => [
    //             'id' => $category->id,
    //             'category_name' => $category->category_name,
    //             'category_desc' => $category->category_desc,
    //             'photo_url' => $photoUrl,
    //             'created_at' => $category->created_at,
    //             'updated_at' => $category->updated_at,
    //         ]
    //     ], 'تم تحديث التصنيف بنجاح');
    // }

    public function destroy($id)
{
    $category = ServiceCategory::find($id);
    if (!$category) {
        return $this->errorResponse('التصنيف غير موجود', 404);
    }

    $category->delete();

    return response()->json([
        'message' => 'تم حذف التصنيف بنجاح'
    ], 200);
}

}
