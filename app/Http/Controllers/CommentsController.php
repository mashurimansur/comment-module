<?php

namespace App\Http\Controllers;
use App\Models\Comments;
use App\Models\Treepath;
use Illuminate\Http\Request;
use App\Helpers\GeneralHelper;
use DB;
use App\Models\Users;

class CommentsController extends Controller
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

    public function index(Request $request) {
        // $user = Users::where('id', 32)->select('id', 'username', 'nama_lengkap', 'email')->get();
        // return $user;
        $comment = Comments::with('unit')->with(['user' => function($query) {
            $query->select('id', 'username', 'nama_lengkap', 'email');
        }])->get();

        if ($comment) {
            $response = $this->response->formatResponseWithPages("OK", $comment, $this->response->STAT_OK());
            $headers = $this->response->HEADERS_REQUIRED('GET');
            return response()->json($response, $this->response->STAT_OK(), $headers);
        } else {
            $headers = $this->response->HEADERS_REQUIRED('GET');
            $response = $this->response->formatResponseWithPages("Failed to load data", [], $this->response->STAT_NOT_FOUND());
            return response()->json($response, $this->response->STAT_NOT_FOUND(), $headers);
        }
    }

    public function comments($module, Request $request) {
        $code_unit = $request->header('code-unit');

        $data = $this->GetListComment(0, 0, $module, $code_unit);

        $response = $this->response->formatResponseWithPages("OK", $data, $this->response->STAT_OK());
        $headers = $this->response->HEADERS_REQUIRED('GET');
        return response()->json($response, $this->response->STAT_OK(), $headers);
    }

    protected function GetListComment($id = 0, $depth = 0, $module, $code_unit)
    {
        if($depth == 0){
            $model = Comments::with(['user' => function($query) {
                $query->select('id', 'username', 'nama_lengkap', 'email');
                    }])->join("treepath", "comments.id", "=", "treepath.ancestor")
                ->where("treepath.depth", $depth)
                ->where('module_unit_id', $module)
                ->where('code_unit_id', $code_unit)
                ->orderBy("comments.id", "asc")
                ->get();
        }else{
            $model = Comments::with(['user' => function($query) {
                $query->select('id', 'username', 'nama_lengkap', 'email');
                    }])->join("treepath", "comments.id", "=", "treepath.offspring")
                ->where("treepath.depth", $depth)
                ->where("treepath.ancestor", $id)
                ->where('module_unit_id', $module)
                ->where('code_unit_id', $code_unit)
                ->orderBy("comments.id", "asc")
                ->orderBy("treepath.ancestor", "asc")
                ->get();
        }
        $result = [];
        foreach ($model as $key => $value) {
            $data["id"] = $value->id;
            $data["comment"] = $value->comment;
            $data["depth"] = $value->depth;
            $data["user"] = $value->user;
            $isHasChild = count($this->GetListComment($value->id, $value->depth+1, $value->module_unit_id, $value->code_unit_id));
            if($isHasChild){
                $data["reply"] = $this->GetListComment($value->id, $value->depth+1, $value->module_unit_id, $value->code_unit_id);
            }else{
                $data["reply"] = [];
            }

            $result[] = $data;
        }

        return $result;
    }

    public function store(Request $request) {
        $this->validate($request, [
            'user_id' => 'required',
            'comment' => 'required',
            'module_unit_id' => 'required',
            'code_unit_id' => 'required'
        ]);
        
        $comment = new Comments;
        $comment->user_id = $request->user_id;
        $comment->comment = $request->comment;
        $comment->count_comment_likes = 0;
        $comment->module_unit_id = $request->module_unit_id;
        $comment->code_unit_id = $request->code_unit_id;
        $comment->save();
        
        $ancestor = $request->input('ancestor');
        $treepath = new Treepath;
        if ($ancestor) {
            try {
                $ori = Treepath::where('offspring', $ancestor)->firstOrFail();
                $treepath->ancestor = (int)$ancestor;
                $treepath->offspring = $comment->id;
                $treepath->depth = $ori->depth + 1;
            } catch(\Exception $e) {
                $headers = $this->response->HEADERS_REQUIRED('POST');
                $response = $this->response->formatResponseWithPages("Failed to save data", [], $this->response->STAT_NOT_FOUND());
                return response()->json($response, $this->response->STAT_OK(), $headers);
            }
        } else {
            $treepath->ancestor = $comment->id;
            $treepath->offspring = $comment->id;
            $treepath->depth = 0;
        }
        $treepath->save();

        $response = $this->response->formatResponseWithPages("Berhasil Menambah Data", [$comment, $treepath], $this->response->STAT_OK());
        return response()->json($response, $this->response->STAT_OK());
    }

    public function delete(Request $request) {

    }
}
