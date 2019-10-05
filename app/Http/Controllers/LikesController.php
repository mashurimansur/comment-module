<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Likes;
use App\Helpers\GeneralHelper;

class LikesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $response;

    public function __construct()
    {
        $this->response = new GeneralHelper;
    }

    public function getLikes($module, Request $request)
    {   
        $code = $request->header('code-unit');
        $status = false;
        $userId = 3; //ganti jadi id user yang login

        $like = Likes::with(['user' => function($query) {
            $query->select('id', 'username', 'nama_lengkap', 'email');
                }])
                ->where('module_unit_id', $module)
                ->where('code_unit', $code)
                ->get();
                
        $findId = Likes::where('module_unit_id', $module)
                ->where('code_unit', $code)
                ->where('user_id', $userId)
                ->first();

        if ($findId) {
            $status = true;
        }

        $data = [
            'likes' => $like,
            'count' => count($like),
            'statusLike' => $status
        ];

        if ($like) {
            $response = $this->response->formatResponseWithPages("OK", $data, $this->response->STAT_OK());
            $headers = $this->response->HEADERS_REQUIRED('GET');
            return response()->json($response, $this->response->STAT_OK(), $headers);
        } else {
            $headers = $this->response->HEADERS_REQUIRED('GET');
            $response = $this->response->formatResponseWithPages("Failed to load data", [], $this->response->STAT_NOT_FOUND());
            return response()->json($response, $this->response->STAT_NOT_FOUND(), $headers);
        }
    }

    public function like(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'code_unit' => 'required',
            'module_unit_id' => 'required',
        ]);

        $like = new Likes;
        $like->user_id = $request->user_id;
        $like->code_unit = $request->code_unit;
        $like->module_unit_id = $request->module_unit_id;
        $like->save();

        $response = $this->response->formatResponseWithPages("Like Success",$like,$this->response->STAT_OK());
        return response()->json($response, $this->response->STAT_OK());
    }

    public function unlike(Request $request)
    {
        try {
            $id = $request->get('id');
            $unlike = Likes::where('id', $id)->firstOrFail();
            if ($unlike == null) {
                $response = $this->response->formatResponseWithPages("You don't like it yet", [], $this->response->STAT_OK());
                return response()->json($response, $this->response->STAT_OK());
            } else {
                $unlike->delete();
                $response = $this->response->formatResponseWithPages("Unlike Success", [], $this->response->STAT_OK());
                return response()->json($response, $this->response->STAT_OK());
            }
        } catch (\Exception $e) {
            $response = $this->response->formatResponseWithPages("You don't like it yet", [], $this->response->STAT_OK());
            return response()->json($response, $this->response->STAT_OK());
        }
    }
}
