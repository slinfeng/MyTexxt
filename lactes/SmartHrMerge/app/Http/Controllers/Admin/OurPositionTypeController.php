<?php

namespace App\Http\Controllers\Admin;

use App\Models\OurPositionType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OurPositionTypeController extends Controller
{

    public function index()
    {
        $ourpositiontypes = OurPositionType::all();
        return view('admin.ourpositiontypes.index', compact('ourpositiontypes'));
    }


    public function create()
    {
        return view('admin.ourpositiontypes.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'our_position_type_name' => 'bail|required|unique:ourpositiontypes|max:200',
        ]);
        $reqData = $request->all();
        OurPositionType::create($reqData);
        return redirect()->route('ourpositiontypes.index')->withStatus(__('OurPositionType is added successfully.'));
    }

    public function edit(OurPositionType $ourpositiontype)
    {
        $result = [
            'ourpositiontype'        => $ourpositiontype
        ];

        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }


    public function update(Request $request, OurPositionType $ourpositiontype)
    {
        $request->validate([
            'our_position_type_name' => 'bail|required|max:200'
        ]);
        $reqData = $request->all();
        $ourpositiontype->update($reqData);
        return redirect()->route('ourpositiontypes.index')->withStatus(__('OurPositionType is updated successfully.'));
    }

    public function destroy(OurPositionType $ourpositiontype)
    {
        $ourpositiontype->delete();
        return back()->withStatus(__('OurPositionType is deleted successfully.'));
    }
}
