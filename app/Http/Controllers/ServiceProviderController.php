<?php
namespace App\Http\Controllers;

use App\Models\ServiceProvider;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceProviderController extends Controller
{
    use ApiResponseTrait;

    /**
     * عرض قائمة جميع مقدمي الخدمات
     */
    public function index()
    {
        $providers = ServiceProvider::all();
        return $this->successResponse($providers, "تم جلب جميع مقدمي الخدمات بنجاح!");
    }

    /**
     * عرض مقدم خدمة معين
     */
    public function show($id)
    {
        $provider = ServiceProvider::find($id);

        if (!$provider) {
            return $this->errorResponse("مقدم الخدمة غير موجود!", 404);
        }

        return $this->successResponse($provider, "تم جلب بيانات مقدم الخدمة بنجاح!");
    }

    /**
     * إنشاء مقدم خدمة جديد
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'specialization' => 'required|string',
            'professional_desc' => 'nullable|string',
            'years_of_experience' => 'nullable|integer',
            'min_price' => 'nullable|numeric',
            'phone_number' => 'nullable|string',
            'profile_img' => 'nullable|string',
            'identity_img' => 'nullable|string',
            'address' => 'nullable|string',
            'cover_img' => 'nullable|string',
        ]);

        $provider = ServiceProvider::create($validatedData);

        return $this->successResponse($provider, "تم إنشاء مقدم الخدمة بنجاح!", 201);
    }

    /**
     * تحديث بيانات مقدم خدمة
     */
    public function update(Request $request, $id)
    {
        $provider = ServiceProvider::find($id);

        if (!$provider) {
            return $this->errorResponse("مقدم الخدمة غير موجود!", 404);
        }

        $provider->update($request->only([
            'specialization', 'professional_desc', 'years_of_experience', 'min_price',
            'phone_number', 'profile_img', 'identity_img', 'address', 'cover_img'
        ]));

        return $this->successResponse($provider, "تم تحديث بيانات مقدم الخدمة بنجاح!");
    }

    /**
     * حذف مقدم خدمة
     */
    public function destroy($id)
    {
        $provider = ServiceProvider::find($id);

        if (!$provider) {
            return $this->errorResponse("مقدم الخدمة غير موجود!", 404);
        }

        $provider->delete();

        return $this->successResponse(null, "تم حذف مقدم الخدمة بنجاح!");
    }

    /**
     * الحصول على مقدمي الخدمة حسب الخدمة
     */
    public function getByService($service_id)
    {
        $providers = ServiceProvider::where('service_id', $service_id)->get();
        return $this->successResponse($providers, "تم جلب مقدمي الخدمة حسب الخدمة بنجاح!");
    }
}
