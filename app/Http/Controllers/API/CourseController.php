<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreCourseRequest;
use App\Models\Course;
use Cloudinary\Cloudinary;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Course::query()
                ->latest('id')->get();

            return response()->json([
                'message' => 'Danh sách khoá học ',
                'data' => $data
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy danh sách khoá học.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        try {
            $data = $request->validated();

            if (isset($data['name'])) {
                $data['slug'] = Str::slug($data['name'], '-');
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image');

                if ($image->isValid()) {
                    $uploadResult = $this->cloudinary->uploadApi()->upload($image->getRealPath(), [
                        'folder' => 'courses',
                    ]);
                    $data['image'] = $uploadResult['secure_url'];
                } else {
                    return response()->json([
                        'message' => 'File ảnh không hợp lệ',
                    ], Response::HTTP_BAD_REQUEST);
                }
            }

            $data['requirements'] = json_encode($request->input('requirements'));
            $data['benefits'] = json_encode($request->input('benefits'));
            $data['qa'] = json_encode($request->input('qa'));

            $course = Course::create($data);
            return response()->json([
                'message' => 'Tạo khoá học thành công',
                'data' => $course
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [
                'message' => $th->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'message' => 'Có lỗi xảy ra, vui lòng thử lại',
                'error' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        try {
            $course = Course::query()
                ->where('slug', $slug)
                ->first();

            if (!$course) {
                throw new \Exception('Không tìm thấy khoá học');
            }

            return response()->json([
                'message' => 'Chi tiết khoá học: ' . $course->name,
                'data' => $course
            ]);
        } catch (\Exception $e) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Lỗi server'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        try {
            $course->delete();
            return response()->json([
                'message' => 'Xoá khoá học thành công'
            ], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Không thể xoá khoá học: ' . $course->name
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

    public function getLessons(string $slug)
    {
        try {
            $course = Course::query()
                ->with(['lessons', 'lessons.lectures'])
                ->where('slug', $slug)
                ->first();

            if (!$course) {
                throw new \Exception('Không tìm thấy khoá học');
            }

            return response()->json([
                'message' => 'Danh sách bài học: ' . $course->name,
                'data' => $course->lessons
            ]);
        } catch (\Exception $e) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Lỗi server'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
