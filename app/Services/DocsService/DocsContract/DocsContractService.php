<?php

namespace App\Services\DocsService\DocsContract;

use App\Http\Resources\DocsContractResource;
use App\Models\DocsContract;
use App\Models\DocsContractFile;
use App\Models\DocsContractPayment;
use App\Services\File\FileStore;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class DocsContractService
{

    private FileStore $file_store_service;

    public function __construct(FileStore $file_store_service)
    {
        $this->file_store_service = $file_store_service;
    }

    public function get($id){
        $contract = DocsContract::find($id);
        return response()->json(new DocsContractResource($contract));
    }

    public function list($query): \Illuminate\Http\JsonResponse
    {
        $docs_contract = DocsContract::with(['file', 'agent', 'department'])->where('parent_id', 0)->orderByDesc('id');

        if ($query->agent) {
            $docs_contract->where('agent_id', 'like', '%' . $query->agent . '%');
        }

        if ($query->contract_id) {
            $docs_contract->where('contract_id', $query->contract_id);
        }

        if ($query->date) {
            $docs_contract->where('date', Carbon::parse($query->date)->timestamp);
        }

        $contract = DocsContractResource::collection($docs_contract->get());
        return response()->json($contract);
    }

    public function update($query, $id): JsonResponse
    {
        $contract = DocsContract::find($id);
        $contract->name = $query->name;
        $contract->agent_id = $query->agent;
        $contract->amount = $query->amount;
        $contract->date_start = Carbon::parse($query->date_start)->timestamp;
        $contract->date_end = Carbon::parse($query->date_end)->timestamp;
        $contract->date = Carbon::parse($query->date)->timestamp;
        $contract->date_service = Carbon::parse($query->date_service)->timestamp;
        $contract->department_id = $query->department;

        $file = $query->file;
        if ($contract->save()) {
            if ($file) {
                $this->storeFile($file, $contract->id);
            }
        }

        return response()->json(new DocsContractResource($contract));
    }


    public function store($query): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($query->all(),
            [
                'name' => 'required|max:255',
                'agent' => 'required|max:255',
                'amount' => 'required',
                'date_start' => 'required',
                'date_end' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                $validator->messages(),
                Response::HTTP_EXPECTATION_FAILED
            );
        }
        $parent_id = (int)$query->id;

        $contract_id = $this->generateContractId($query->date, $parent_id);

        $contract = new DocsContract;
        $contract->contract_id = $contract_id;
        $contract->name = $query->name;
        $contract->agent_id = $query->agent;
        $contract->amount = $query->amount;
        $contract->date_start = Carbon::parse($query->date_start)->timestamp;
        $contract->date_end = Carbon::parse($query->date_end)->timestamp;
        $contract->date = Carbon::parse($query->date)->timestamp;
        $contract->parent_id = $parent_id;
        $contract->date_service = Carbon::parse($query->date_service)->timestamp;
        $contract->department_id = $query->department;

        $file = $query->file;
        if ($contract->save()) {
            if ($file) {
                $this->storeFile($file, $contract->id);
            }
        }

        return response()->json($contract, Response::HTTP_OK);
    }

    public function generateContractId($date, $parent_id): string
    {

        $id = $parent_id;

        $date = Carbon::parse($date)->format('Y');
        $generated_id = '01-01-' . $date;

        $latestContract = DocsContract::orderBy('created_at', 'DESC')->where('parent_id', 0)->first();
        if ($id > 0) {
            $latestChildContract = DocsContract::orderBy('created_at', 'DESC')->where('parent_id', $id)->first();
            $contract = DocsContract::find($id);
        } else {
            $latestChildContract = false;
            $contract = false;
        }


        if ($latestContract) {

            $row = explode("-", $latestContract->contract_id);

            $new_id = [];
            $contract_id_parent = intval($row[0]) + 1;

            if ($contract_id_parent <= 9) {
                $new_id[0] = '0' . $contract_id_parent;
            } else {
                $new_id[0] = $contract_id_parent;
            }


            if ($latestChildContract) {
                $row2 = explode("-", $contract->contract_id);
                $row3 = explode("-", $latestChildContract->contract_id);
                $contract_id_child = intval($row3[1]);
                if ($contract_id_child < 11) {
                    $contract_id_child = $contract_id_child + 10;
                } else {
                    $contract_id_child = $contract_id_child + 1;
                }
                $new_id[1] = $contract_id_child;
                $new_id[0] = $row2[0];
            } else {
                $new_id[1] = $row[1];
                if ($contract) {
                    $row2 = explode("-", $contract->contract_id);
                    $contract_id_child = intval($row2[1]);
                    if ($contract_id_child < 11) {
                        $contract_id_child = $contract_id_child + 10;
                    } else {
                        $contract_id_child = $contract_id_child + 1;
                    }
                    $new_id[1] = $contract_id_child;
                    $new_id[0] = $row2[0];
                }
            }

            $new_id[2] = $date;

            $generated_id = implode("-", $new_id);
        }

        return $generated_id;
    }

    public function storePayment($query): JsonResponse
    {
        $contract_id = $query->contract_id;
        $amount = $query->amount;
        $date = Carbon::parse($query->date)->timestamp;

        $payment = new DocsContractPayment;
        $payment->date = $date;
        $payment->amount = $amount;
        $payment->docs_contract_id = $contract_id;
        $payment->save();

        $result = [
            'message' => 'created successfully'
        ];

        return response()->json($result, Response::HTTP_OK);
    }

    public function getPayment($id): JsonResponse
    {
        $contract = DocsContract::find($id);
        $payment = DocsContractPayment::where('docs_contract_id', $id)->orderBy('date', 'desc')->get();

        $total_completed = 0;
        $total_not_completed = 0;

        for ($i = 0; $i < count($payment); $i++) {
            $total_completed += $payment[$i]->amount;
        }

        $total_not_completed = ($total_completed >= $contract->amount) ? 0 : $contract->amount - $total_completed;

        $result = [
            'payment' => $payment,
            'total_completed' => $total_completed,
            'total_not_completed' => $total_not_completed,
        ];

        return response()->json($result, Response::HTTP_OK);
    }

    public function getAdditional($id): JsonResponse
    {
        $contract = DocsContract::where('parent_id', $id)->get();
        return response()->json(DocsContractResource::collection($contract));
    }

    public function deletePayment($id): \Illuminate\Http\JsonResponse
    {
        $payment = DocsContractPayment::find($id);
        $payment->delete();

        $result = ['message' => 'deleted successfully'];

        return response()->json($result, Response::HTTP_OK);
    }

    public function deleteAdditional($id): JsonResponse
    {
        $contract = DocsContract::find($id);
        foreach ($contract->get() as $item) {
            $additional_file = DocsContractFile::where('docs_contract_id', $item->id)->first();
            if ($additional_file) {
                if (Storage::exists('public/uploads/' . $additional_file->name)) {
                    Storage::delete('public/uploads/' . $additional_file->name);
                    $additional_file->delete();
                }
            }
        }
        $contract->delete();
        $result = ['deleted successfully'];
        return response()->json($result, Response::HTTP_OK);
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $contract = DocsContract::find($id);
        $additional = DocsContract::where('parent_id', $contract->id);
        $payment = DocsContractPayment::where('docs_contract_id', $contract->id);


        $file = DocsContractFile::where('docs_contract_id', $contract->id)->first();
        if ($file) {
            if (Storage::exists('public/uploads/' . $file->name)) {
                Storage::delete('public/uploads/' . $file->name);
                $file->delete();
            }
        }

        foreach ($additional->get() as $item) {
            $additional_file = DocsContractFile::where('docs_contract_id', $item->id)->first();
            if ($additional_file) {
                if (Storage::exists('public/uploads/' . $additional_file->name)) {
                    Storage::delete('public/uploads/' . $additional_file->name);
                    $additional_file->delete();
                }
            }
        }


        if ($additional->count() > 0) {
            $additional->delete();
        }

        if ($payment->count() > 0) {
            $payment->delete();
        }

        $contract->delete($contract->id);

        $result = [
            'success' => true,
            'deleted successfully'
        ];

        return response()->json($result, Response::HTTP_OK);
    }


    public function storeFile($file, $id)
    {
        $path = 'docs/contract';
        $filename = $this->file_store_service->store($file, $path);
        $file = new DocsContractFile();
        $file->name = $filename;
        $file->docs_contract_id = $id;
        $file->save();
    }

    public function agentList()
    {
        $agent = DocsContract::select('agent')->get();
        return response()->json($agent);
    }

}
