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
        $providers = ServiceProvider::with('user')->get()->map(function ($provider) {
            $identityImgUrl = $provider->getFirstMediaUrl('identity_img') ?: asset('images/default-identity.jpg');
            $coverImgUrl = $provider->getFirstMediaUrl('cover_img') ?: asset('images/default-cover.jpg');
            $profileImgUrl = $provider->getFirstMediaUrl('profile_img') ?: asset('images/default-profile.jpg');
            return [
                'id' => $provider->id,
                'user_id' => $provider->user_id,
                'user_name' => $provider->user ? $provider->user->name : null,
                'service_id' => $provider->service_id,
                'specialization' => $provider->specialization,
                'professional_desc' => $provider->professional_desc,
                'years_of_experience' => $provider->years_of_experience,
                'min_price' => $provider->min_price,
                'phone_number' => $provider->phone_number,
                'identity_img' => $identityImgUrl,
                'cover_img' => $coverImgUrl,
                'profile_img' => $profileImgUrl,
                'address' => $provider->address,
                'created_at' => $provider->created_at,
                'updated_at' => $provider->updated_at,
            ];
        });
        return $this->successResponse($providers, "تم جلب جميع مقدمي الخدمات بنجاح!");
    }

    /**
     * عرض مقدم خدمة معين
     */
    public function show($id)
    {
        $provider = ServiceProvider::with('user')->find($id);

        if (!$provider) {
            return $this->errorResponse("مقدم الخدمة غير موجود!", 404);
        }

        $identityImgUrl = $provider->getFirstMediaUrl('identity_img') ?: asset('images/default-identity.jpg');
        $coverImgUrl = $provider->getFirstMediaUrl('cover_img') ?: asset('images/default-cover.jpg');
        $profileImgUrl = $provider->getFirstMediaUrl('profile_img') ?: asset('images/default-profile.jpg');

        return $this->successResponse([
            'id' => $provider->id,
            'user_id' => $provider->user_id,
            'user_name' => $provider->user ? $provider->user->name : null,
            'service_id' => $provider->service_id,
            'specialization' => $provider->specialization,
            'professional_desc' => $provider->professional_desc,
            'years_of_experience' => $provider->years_of_experience,
            'min_price' => $provider->min_price,
            'phone_number' => $provider->phone_number,
            'identity_img' => $identityImgUrl,
            'cover_img' => $coverImgUrl,
            'profile_img' => $profileImgUrl,
            'address' => $provider->address,
            'created_at' => $provider->created_at,
            'updated_at' => $provider->updated_at,
        ], "تم جلب بيانات مقدم الخدمة بنجاح!");
    }

    /**
     * إنشاء مقدم خدمة جديد
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'service_id' => 'required|exists:services,id',
            'specialization' => 'required|string',
            'professional_desc' => 'nullable|string',
            'years_of_experience' => 'nullable|integer',
            'min_price' => 'nullable|numeric',
            'phone_number' => 'nullable|string',
            'identity_img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cover_img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'profile_img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'address' => 'nullable|string',
        ]);

        $provider = ServiceProvider::create(array_merge(
            $request->except(['identity_img', 'cover_img', 'profile_img']),
            ['user_id' => $user->id]
        ));

        // Handle Spatie images
        foreach (['identity_img', 'cover_img', 'profile_img'] as $imgField) {
            if ($request->hasFile($imgField)) {
                $provider->addMediaFromRequest($imgField)->toMediaCollection($imgField);
            }
        }

        // Get image URLs with fallback
        $identityImgUrl = $provider->getFirstMediaUrl('identity_img') ?: asset('images/default-identity.jpg');
        $coverImgUrl = $provider->getFirstMediaUrl('cover_img') ?: asset('images/default-cover.jpg');
        $profileImgUrl = $provider->getFirstMediaUrl('profile_img') ?: asset('images/default-profile.jpg');

        return $this->successResponse([
            'id' => $provider->id,
            'user_id' => $provider->user_id,
            'user_name' => $user->name,
            'service_id' => $provider->service_id,
            'specialization' => $provider->specialization,
            'professional_desc' => $provider->professional_desc,
            'years_of_experience' => $provider->years_of_experience,
            'min_price' => $provider->min_price,
            'phone_number' => $provider->phone_number,
            'identity_img' => $identityImgUrl,
            'cover_img' => $coverImgUrl,
            'profile_img' => $profileImgUrl,
            'address' => $provider->address,
            'created_at' => $provider->created_at,
            'updated_at' => $provider->updated_at,
        ], "تم إنشاء مقدم الخدمة بنجاح!", 201);
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

        $validator = Validator::make($request->all(), [
            'specialization' => 'nullable|string',
            'professional_desc' => 'nullable|string',
            'years_of_experience' => 'nullable|integer',
            'min_price' => 'nullable|numeric',
            'phone_number' => 'nullable|string',
            'identity_img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cover_img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'profile_img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $data = $validator->validated();

        foreach (['specialization', 'professional_desc', 'years_of_experience', 'min_price', 'phone_number', 'address'] as $field) {
            if (isset($data[$field])) {
                $provider->$field = $data[$field];
            }
        }

        // Handle Spatie images
        foreach (['identity_img', 'cover_img', 'profile_img'] as $imgField) {
            if ($request->hasFile($imgField)) {
                $provider->clearMediaCollection($imgField);
                $provider->addMediaFromRequest($imgField)->toMediaCollection($imgField);
            }
        }

        $provider->save();
        $provider->load('user');

        $identityImgUrl = $provider->getFirstMediaUrl('identity_img') ?: asset('images/default-identity.jpg');
        $coverImgUrl = $provider->getFirstMediaUrl('cover_img') ?: asset('images/default-cover.jpg');
        $profileImgUrl = $provider->getFirstMediaUrl('profile_img') ?: asset('images/default-profile.jpg');

        return $this->successResponse([
            'id' => $provider->id,
            'user_id' => $provider->user_id,
            'user_name' => $provider->user ? $provider->user->name : null,
            'service_id' => $provider->service_id,
            'specialization' => $provider->specialization,
            'professional_desc' => $provider->professional_desc,
            'years_of_experience' => $provider->years_of_experience,
            'min_price' => $provider->min_price,
            'phone_number' => $provider->phone_number,
            'identity_img' => $identityImgUrl,
            'cover_img' => $coverImgUrl,
            'profile_img' => $profileImgUrl,
            'address' => $provider->address,
            'created_at' => $provider->created_at,
            'updated_at' => $provider->updated_at,
        ], "تم تحديث بيانات مقدم الخدمة بنجاح!");
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
        $providers = ServiceProvider::with('user')->where('service_id', $service_id)->get()->map(function ($provider) {
            $identityImgUrl = $provider->getFirstMediaUrl('identity_img') ?: asset('images/default-identity.jpg');
            $coverImgUrl = $provider->getFirstMediaUrl('cover_img') ?: asset('images/default-cover.jpg');
            $profileImgUrl = $provider->getFirstMediaUrl('profile_img') ?: asset('images/default-profile.jpg');
            return [
                'id' => $provider->id,
                'user_id' => $provider->user_id,
                'user_name' => $provider->user ? $provider->user->name : null,
                'service_id' => $provider->service_id,
                'specialization' => $provider->specialization,
                'professional_desc' => $provider->professional_desc,
                'years_of_experience' => $provider->years_of_experience,
                'min_price' => $provider->min_price,
                'phone_number' => $provider->phone_number,
                'identity_img' => $identityImgUrl,
                'cover_img' => $coverImgUrl,
                'profile_img' => $profileImgUrl,
                'address' => $provider->address,
                'created_at' => $provider->created_at,
                'updated_at' => $provider->updated_at,
            ];
        });
        return $this->successResponse($providers, "تم جلب مقدمي الخدمة حسب الخدمة بنجاح!");
    }
}
