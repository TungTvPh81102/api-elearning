<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LectureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $lecture = Lecture::query()
                ->with('course', 'lesson')
                ->latest('id')
                ->get();

            return response()->json([
                'message' => 'Danh sách bài học ',
                'data' => $lecture
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'message' => 'Lỗi server',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            if (!isset($data['course_id'])) {
                throw new \Exception('Có lỗi xảy ra, vui lòng thử lại');
            }

            if (!isset($data['lesson_id'])) {
                throw new \Exception('Có lỗi xảy ra, vui lòng thử lại');
            }

            if ($data['title']) {
                $data['slug'] = Str::slug($data['title'], '-');
            }

            $newLecture = Lecture::query()->create($data);

            if (!$newLecture) {
                throw new \Exception('Có lỗi xảy ra khi tạo bài học');
            }

            return response()->json([
                'message' => 'Thêm bài học thành công',
                'data' => $newLecture,
            ]);
        } catch (\Exception $e) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'message' => 'Lỗi server',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $lecture = Lecture::query()
                ->with('course', 'lesson')
                ->find($id);

            $lecture = Lecture::query()->find($id);

            if (!$lecture) {
                throw new \Exception('Không tìm thấy bài học');
            }

            return response()->json([
                'message' => 'Chi tiết bài học: ' . $lecture->title,
                'data' => $lecture,
            ]);
        } catch (\Exception $e) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'message' => 'Lỗi server',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = $request->all();

            $lecture = Lecture::query()->find($id);

            if (!$lecture) {
                throw new \Exception('Không tìm thấy bài học');
            }

            $lecture->update($data);

            return response()->json([
                'message' => 'Cập nhật bài học thành công',
                'data' => $lecture,
            ]);
        } catch (\Exception $e) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'message' => 'Lỗi server',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $lecture = Lecture::query()->find($id);

            if (!$lecture) {
                throw new \Exception('Không tìm thấy bài học');
            }

            if ($lecture->delete()) {
                return response()->json([
                    'message' => 'Cập nhật bài học thành công',
                    'data' => $lecture,
                ]);
            }

            throw  new \Exception('Không thể xoá bài học');
        } catch (\Exception $e) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'message' => 'Lỗi server',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
