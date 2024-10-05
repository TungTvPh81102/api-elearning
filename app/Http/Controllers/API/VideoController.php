<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use MuxPhp\Configuration;
use MuxPhp;
use GuzzleHttp;

class VideoController extends Controller
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
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $config = Configuration::getDefaultConfiguration()
            ->setUsername(env('MUX_TOKEN_ID'))
            ->setPassword(env('MUX_TOKEN_SECRET'));

        $assetsApi = new MuxPhp\Api\AssetsApi(
            new GuzzleHttp\Client(),
            $config
        );
        $data = [];
        if ($request->hasFile('video')) {
            $video = $request->file('video');

            if ($video->isValid()) {
                $uploadResult = $this->cloudinary->uploadApi()->upload($video->getRealPath(), [
                    'resource_type' => 'video',
                    'folder' => 'videos',
                ]);

                $data['url'] = $uploadResult['secure_url'];
            } else {
                return response()->json([
                    'message' => 'File ảnh không hợp lệ',
                ], Response::HTTP_BAD_REQUEST);
            }
        } else {
            $existingVideoUrl = $this->getExistingVideoUrl($request);
            $data['url'] = $existingVideoUrl;
        }

        $input = new MuxPhp\Models\InputSettings([
            "url" => $data['url']
        ]);

        $createAssetRequest = new MuxPhp\Models\CreateAssetRequest(["input" => $input, "playback_policy" => [MuxPhp\Models\PlaybackPolicy::_PUBLIC]]);

        $result = $assetsApi->createAsset($createAssetRequest);

        if (!$result) {
            throw new \Exception('Có lỗi xảy ra khi tạo bài học');
        }

        $data = $result->getData()->getPlaybackIds()[0]->getId();

        return response()->json([
            'message' => 'OK',
            'data' => $data
        ], Response::HTTP_CREATED);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
