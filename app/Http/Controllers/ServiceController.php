<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    use ApiResponseTrait;

    // GET /index-service
    public function index()
    {
        $services = Service::with('category')->get()->map(function ($service) {
            $photoUrl = $service->getFirstMediaUrl('service_image');
            if (empty($photoUrl)) {
                $photoUrl = asset('images/default-service.jpg');
            }
            return [
                'id' => $service->id,
                'category_id' => $service->category_id,
                'category_name' => $service->category ? $service->category->category_name : null,
                'service_title' => $service->service_title,
                'service_desc' => $service->service_desc,
                'service_creator_name' => $service->service_creator_name,
                'service_creator_address' => $service->service_creator_address,
                'service_creator_phone_number' => $service->service_creator_phone_number,
                'service_price' => $service->service_price,
                'photo_url' => $photoUrl,
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at,
            ];
        });
        return $this->successResponse(['services' => $services], 'تم جلب جميع الخدمات بنجاح');
    }

    // POST /create-service
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:service_categories,id',
            'service_title' => 'required|string',
            'service_desc' => 'nullable|string',
            'service_creator_name' => 'nullable|string|max:100',
            'service_creator_address' => 'nullable|string|max:255',
            'service_creator_phone_number' => 'nullable|string|max:20',
            'service_price' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $service = Service::create($validator->validated());

        if ($request->hasFile('photo')) {
            $service->addMediaFromRequest('photo')->toMediaCollection('service_image');
        }

        $photoUrl = $service->getFirstMediaUrl('service_image');
        if (empty($photoUrl)) {
            $photoUrl = asset('images/default-service.jpg');
        }

        return $this->successResponse([
            'service' => [
                'id' => $service->id,
                'category_id' => $service->category_id,
                'service_title' => $service->service_title,
                'service_desc' => $service->service_desc,
                'service_creator_name' => $service->service_creator_name,
                'service_creator_address' => $service->service_creator_address,
                'service_creator_phone_number' => $service->service_creator_phone_number,
                'service_price' => $service->service_price,
                'photo_url' => $photoUrl,
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at,
            ]
        ], 'تم إنشاء الخدمة بنجاح', 201);
    }

    // GET /show-service/{id}
    public function show($id)
    {
        $service = Service::with('category')->find($id);
        if (!$service) {
            return $this->errorResponse('الخدمة غير موجودة', 404);
        }
        $photoUrl = $service->getFirstMediaUrl('service_image');
        if (empty($photoUrl)) {
            $photoUrl = asset('images/default-service.jpg');
        }
        return $this->successResponse([
            'service' => [
                'id' => $service->id,
                'category_id' => $service->category_id,
                'category_name' => $service->category ? $service->category->category_name : null,
                'service_title' => $service->service_title,
                'service_desc' => $service->service_desc,
                'service_creator_name' => $service->service_creator_name,
                'service_creator_address' => $service->service_creator_address,
                'service_creator_phone_number' => $service->service_creator_phone_number,
                'service_price' => $service->service_price,
                'photo_url' => $photoUrl,
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at,
            ]
        ], 'تم جلب بيانات الخدمة بنجاح');
    }

    // PUT /update-service/{id}
    public function update(Request $request, $id)
    {
        $service = Service::find($id);
        if (!$service) {
            return $this->errorResponse('الخدمة غير موجودة', 404);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'nullable|exists:service_categories,id',
            'service_title' => 'nullable|string',
            'service_desc' => 'nullable|string',
            'service_creator_name' => 'nullable|string|max:100',
            'service_creator_address' => 'nullable|string|max:255',
            'service_creator_phone_number' => 'nullable|string|max:20',
            'service_price' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $data = $validator->validated();

        foreach ([
            'category_id', 'service_title', 'service_desc',
            'service_creator_name', 'service_creator_address',
            'service_creator_phone_number', 'service_price'
        ] as $field) {
            if (isset($data[$field])) {
                $service->$field = $data[$field];
            }
        }

        if ($request->hasFile('photo')) {
            $service->clearMediaCollection('service_image');
            $service->addMediaFromRequest('photo')->toMediaCollection('service_image');
        }

        $service->save();

        $photoUrl = $service->getFirstMediaUrl('service_image');
        if (empty($photoUrl)) {
            $photoUrl = asset('images/default-service.jpg');
        }

        return $this->successResponse([
            'service' => [
                'id' => $service->id,
                'category_id' => $service->category_id,
                'service_title' => $service->service_title,
                'service_desc' => $service->service_desc,
                'service_creator_name' => $service->service_creator_name,
                'service_creator_address' => $service->service_creator_address,
                'service_creator_phone_number' => $service->service_creator_phone_number,
                'service_price' => $service->service_price,
                'photo_url' => $photoUrl,
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at,
            ]
        ], 'تم تحديث الخدمة بنجاح');
    }

    // DELETE /delete-service/{id}
    public function destroy($id)
    {
        $service = Service::find($id);
        if (!$service) {
            return $this->errorResponse('الخدمة غير موجودة', 404);
        }
        $service->delete();
        return $this->successResponse(null, 'تم حذف الخدمة بنجاح');
    }

    // GET /services-by-category/{category_id}
    public function getByCategory($category_id)
    {
        $services = Service::where('category_id', $category_id)->get()->map(function ($service) {
            $photoUrl = $service->getFirstMediaUrl('service_image');
            if (empty($photoUrl)) {
                $photoUrl = asset('images/default-service.jpg');
            }
            return [
                'id' => $service->id,
                'category_id' => $service->category_id,
                'service_title' => $service->service_title,
                'service_desc' => $service->service_desc,
                'photo_url' => $photoUrl,
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at,
            ];
        });
        return $this->successResponse(['services' => $services], 'تم جلب الخدمات حسب التصنيف بنجاح');
    }
}
