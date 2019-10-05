<?php

namespace App\Http\Controllers;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Helpers\GeneralHelper;

class UnitController extends Controller
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

    public function index() {
        $unit = Unit::all();

        if ($unit) {
            $response = $this->response->formatResponseWithPages("OK", $unit, $this->response->STAT_OK());
            $headers = $this->response->HEADERS_REQUIRED('GET');
            return response()->json($response, $this->response->STAT_OK(), $headers);
        } else {
            $headers = $this->response->HEADERS_REQUIRED('GET');
            $response = $this->response->formatResponseWithPages("Failed to load data",[],$this->response->STAT_NOT_FOUND());
            return response()->json($response, $this->response->STAT_NOT_FOUND(), $headers);
        }

    }

    public function detail($id) {
 
        try {
            $unit = Unit::where('id', $id)->firstOrFail();
            $response = $this->response->formatResponseWithPages("OK", $unit, $this->response->STAT_OK());
            $headers = $this->response->HEADERS_REQUIRED('GET');
            return response()->json($response, $this->response->STAT_OK(), $headers);
        } catch (\Exception $e) {
            $headers = $this->response->HEADERS_REQUIRED('GET');
            $response = $this->response->formatResponseWithPages("Failed to load data",[],$this->response->STAT_NOT_FOUND());
            return response()->json($response, $this->response->STAT_NOT_FOUND(), $headers);
        }
    }

    public function store(Request $request) {

        $this->validate($request, [
            'code_unit' => 'required',
        ]);

        $unit = new Unit;
        $unit->code_unit = $request->code_unit;
        $unit->save();

        $response = $this->response->formatResponseWithPages("Berhasil Menambah Data",$unit,$this->response->STAT_OK());
        return response()->json($response, $this->response->STAT_OK());
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'code_unit' => 'required'
        ]);

        $unit = Unit::find($id);
        $unit->code_unit = $request->code_unit;
        $unit->save();

        $response = $this->response->formatResponseWithPages("Berhasil Mengubah Data",$unit,$this->response->STAT_OK());
        return response()->json($response, $this->response->STAT_OK());
    }

    public function delete(Request $request) {
        try {
            $id_unit = $request->get('id');
            $unit = Unit::where('id', $id_unit)->firstOrFail();
            if ($unit == null) {
                $response = $this->response->formatResponseWithPages("Unit Telah Dihapus", [], $this->response->STAT_OK());
                return response()->json($response, $this->response->STAT_OK());
            } else {
                $unit->delete();
                $response = $this->response->formatResponseWithPages("Berhasil Menghapus Unit ", [], $this->response->STAT_OK());
                return response()->json($response, $this->response->STAT_OK());
            }
        } catch (\Exception $e) {
            $response = $this->response->formatResponseWithPages("Hapus Unit Gagal", [], $this->response->STAT_OK());
            return response()->json($response, $this->response->STAT_OK());
        }
    }

}
