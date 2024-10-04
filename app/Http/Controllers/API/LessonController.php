<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreLessonRequest;
use App\Http\Requests\API\UpdateLessonRequest;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Lesson::query()
                ->with('course')
                ->latest('id')
                ->get();

            return response()->json([
                'message' => 'Danh sách bài học ',
                'data' => $data
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy danh sách bài học.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLessonRequest $request)
    {
        try {
            $data = $request->validated();

            $lesson = Lesson::query()->create($data);

            if (!$lesson) {
                throw new \Exception('Có lỗi xảy ra khi tạo bài học');
            }

            return response()->json([
                'message' => 'Tạo bài học thành công',
                'data' => $lesson
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [
                'message' => $e->getMessage(),
                'request' => $request->all(),
                'line' => $e->getLine(),
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = $request->all();

            $lesson = Lesson::query()->find($id);

            if (!$lesson) {
                throw new \Exception('Không tìm thấy bài học');
            }

            $lesson->update($data);

            return response()->json([
                'message' => 'Cập nhật bài học thành công',
                'data' => $lesson
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [
                'message' => $e->getMessage(),
                'request' => $request->all(),
                'line' => $e->getLine(),
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
            $lesson = Lesson::query()->find($id);

            if (!$lesson) {
                throw new \Exception('Không tìm thấy bài học');
            }

            if ($lesson->delete()) {
                return response()->json([
                    'message' => 'Xóa bài học thành công'
                ], Response::HTTP_NO_CONTENT);
            }
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
