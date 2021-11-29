<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContractType;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContractTypeController extends Controller
{
    /**
     * インデックス
     * @return Application|Factory|View
     */
    public function index()
    {
        $contracttypes = ContractType::all();
        return view('admin.contracttypes.index', compact('contracttypes'));
    }

    /**
     * 新規
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('admin.contracttypes.create');
    }


    /**
     * 登録
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $request->validate([
            'contract_type_name' => 'bail|required|unique:contracttypes|max:255',
        ]);
        $reqData = $request->all();
        ContractType::create($reqData);
        return redirect()->route('contracttypes.index')->withStatus(__('ContractType is added successfully.'));
    }

    /**
     * 編集
     * @param ContractType $contracttype
     * @return JsonResponse
     */
    public function edit(ContractType $contracttype)
    {
        $result = [
            'contracttype' => $contracttype
        ];
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }


    /**
     * 更新
     * @param Request $request
     * @param ContractType $contracttype
     * @return mixed
     */
    public function update(Request $request, ContractType $contracttype)
    {
        $request->validate([
            'contract_type_name' => 'bail|required|max:255'
        ]);
        $reqData = $request->all();
        $contracttype->update($reqData);
        return redirect()->route('contracttypes.index')->withStatus(__('ContractType is updated successfully.'));
    }

    /**
     * 削除
     * @param ContractType $contracttype
     * @return mixed
     */
    public function destroy(ContractType $contracttype)
    {
        $contracttype->delete();
        return back()->withStatus(__('ContractType is deleted successfully.'));
    }
}
