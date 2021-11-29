<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Models\AssetInfo;
use App\Models\AssetSetting;
use App\Models\AssetType;
use App\Http\Controllers\Controller;
use App\Traits\PCGateTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class AssetTypeController extends Controller
{
    use PCGateTrait;
    /**
     * index
     * @return Application|Factory|View
     */
    public function index()
    {
        $assettypes = AssetType::all();
        return view('admin.assettypes.index', compact('assettypes'));
    }

    /**
     * create
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('admin.assettypes.create');
    }

    /**
     * store
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function store(Request $request)
    {
        $this->deniesForModify(AssetSetting::class);
        $validator = Validator::make($request->all(),[
            'asset_type_name' => ['bail','required','unique:asset_types','max:50'],
            'asset_type_code' => ['bail','required','unique:asset_types','max:50'],
        ]);
        if ($validator->fails()) {
            return Reply::fail($validator->errors()->first());
        }
        $reqData = $request->all();
        AssetType::create($reqData);
        $assettypes = AssetType::orderBy('asset_type_code')->get();
        return Reply::success('資産種類を追加しました！',['data'=>$assettypes]);
    }

    /**
     * edit
     * @param AssetType $assettype
     * @return JsonResponse
     */
    public function edit(AssetType $assettype)
    {
        $result = [
            'assettype'=> $assettype
        ];
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * update
     * @param Request $request
     * @param AssetType $assettype
     * @return array|bool[]|string[]
     */
    public function update(Request $request, AssetType $assettype)
    {
        $this->deniesForModify(AssetSetting::class);
        $validator = Validator::make($request->all(),[
            'asset_type_name' => ['bail','required','max:50',
                Rule::unique('asset_types','asset_type_name')->whereNot('id',$assettype->id)],
            'asset_type_code' => ['bail','required','max:50',
                Rule::unique('asset_types','asset_type_code')->whereNot('id',$assettype->id)],
        ]);
        if ($validator->fails()) {
            return Reply::fail($validator->errors()->first());
        }else{
            if((AssetInfo::where('asset_type_id',$assettype->id)->count())>0 && $assettype->asset_type_code!=$request->asset_type_code){
                return Reply::fail(__('当該資産種類は使用中であり、資産タイプコードを編集できません'));
            }else{
                $reqData = $request->all();
                $assettype->update($reqData);
                $assettypes = AssetType::orderBy('asset_type_code')->get();
                return Reply::success(__('当該資産種類が正常に変更されました。'),['data'=>$assettypes]);
            }
        }
    }

    /**
     * 削除
     * @param AssetType $assettype
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function destroy(AssetType $assettype,Request $request)
    {
        $this->deniesForModify(AssetSetting::class);
        if((AssetInfo::where('asset_type_id',$assettype->id)->count())>0){
            return Reply::fail(__('当該資産種類は使用中であり、削除できません'));
        }else{
            $assettype->delete();
            $assettypes = AssetType::orderBy('asset_type_code')->get();
            return Reply::success(__('当該資産種類は削除されました。'),['data'=>$assettypes]);
        }
    }
}
