<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\LogQueue;
use App\Jobs\Queue;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ApiTestController extends Controller
{
    public function test(Request $request)
    {
        $this->validate($request, [
            'text' => 'required',
        ]);
        try {
            if (!file_exists(public_path('/uploads/api/qr_codes'))) {
                mkdir(public_path('/uploads/api/qr_codes'));
            }
            $fileName = get_rand_code(12, 3) . time() . '.png';
            $filePath = public_path('/uploads/api/qr_codes/' . $fileName);
             QrCode::format('png')
                ->size(100)
                ->margin(1)
                ->encoding('UTF-8')
                ->generate($request->text,$filePath);
            $imageFile = file_get_contents($filePath);
            return response($imageFile, 200)->header('Content-Type', 'image/png');
        } catch (\Exception $e) {
            return apiResponse('500', [], $e->getMessage());
        }
    }


}
