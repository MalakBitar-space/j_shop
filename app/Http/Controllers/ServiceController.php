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
            return [
                'id' => $service->id,
                'category_id' => $service->category_id,
                'category_name' => $service->category ? $service->category->category_name : null,
                'service_title' => $service->service_title,
                'service_desc' => $service->service_desc,
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
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $service = Service::create($validator->validated());

        return $this->successResponse([
            'service' => [
                'id' => $service->id,
                'category_id' => $service->category_id,
                'service_title' => $service->service_title,
                'service_desc' => $service->service_desc,
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
        return $this->successResponse([
            'service' => [
                'id' => $service->id,
                'category_id' => $service->category_id,
                'category_name' => $service->category ? $service->category->category_name : null,
                'service_title' => $service->service_title,
                'service_desc' => $service->service_desc,
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
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $data = $validator->validated();

        if (isset($data['category_id'])) {
            $service->category_id = $data['category_id'];
        }
        if (isset($data['service_title'])) {
            $service->service_title = $data['service_title'];
        }
        if (isset($data['service_desc'])) {
            $service->service_desc = $data['service_desc'];
        }

        $service->save();

        return $this->successResponse([
            'service' => [
                'id' => $service->id,
                'category_id' => $service->category_id,
                'service_title' => $service->service_title,
                'service_desc' => $service->service_desc,
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
            return [
                'id' => $service->id,
                'category_id' => $service->category_id,
                'service_title' => $service->service_title,
                'service_desc' => $service->service_desc,
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at,
            ];
        });
        return $this->successResponse(['services' => $services], 'تم جلب الخدمات حسب التصنيف بنجاح');
    }
}
