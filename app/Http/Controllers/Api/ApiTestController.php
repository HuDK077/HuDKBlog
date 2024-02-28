<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ApiTestController extends Controller
{
    public function test(Request $request)
    {
        $this->validate($request, [
            'text' => 'required',
        ]);
        try {
            $img = QrCode::format('png')
                ->size(100)
                ->margin(1)
                ->encoding('UTF-8')
                ->generate($request->text);
            $data = 'data:image/png;base64,' . base64_encode($img);
            return "<img src='$data'>";
        } catch (\Exception $e) {
            return apiResponse('500', [], $e->getMessage());
        }
    }


}
