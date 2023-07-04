<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\DataLog;
use Illuminate\Http\Request;

class DataLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getLog(Data $data)
    {
        $dataLog = DataLog::where('data_id',$data->id)->orderBy('id','DESC')->select([
            'id',
            'date',
            'property',
            'new',
            'created_at',
        ])->get();
        $name =  $data->name;

        return view('admin.data-log',compact(['dataLog','name']));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $dataLog = DataLog::where('id',$request->id)->select([
            'property',
            'new',
        ])->get();

        echo $dataLog;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataLog $dataLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $dataLog = DataLog::where('id',$request->id)->update(["new"=>$request->new]);
        echo json_encode(array("message"=>"log updated"));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $dataLog = DataLog::find($request->id);

        if ($dataLog) {
            $dataLog->delete();
        }
        echo json_encode(array("message" => "log successfully deleted"));
    }
}
