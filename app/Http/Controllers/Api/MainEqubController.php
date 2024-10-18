<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\MainEqub;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Repositories\Equb\IEqubRepository;
use App\Http\Requests\MainEqub\UpdateEqubRequest;
use App\Models\EqubType;

class MainEqubController extends Controller
{
    public function getTypes() {
        $types = EqubType::with('mainEqub')->get();
        return response()->json([
            'data' => $types
        ]);

        return view('admin/equbType.equbTypeList', $types);
    }

    public function index() {
        $mainEqubs = MainEqub::with('subEqub')->get();
        return response()->json([
            'data' => $mainEqubs
        ]);
    }


    public function store(Request $request) {
        // dd($request->input('created_by'), Auth::user());
        try {
            $userData = Auth::user();
            $this->validate($request, [
                'name' => 'required',
                'created_by' => 'required',
                // 'image' => 'required',
                'remark' => 'nullable'
            ]);
            $name = $request->input('name');
            $created_by = $request->input('created_by');
            $image = $request->file('image');
            $remark = $request->input('remark');

            $mainEqub = [
                'name' => $name,
                'created_by' => $created_by,
                'remark' => $remark,
            ];
            if ($request->file('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/mainEqub', $imageName);
                $mainEqub['image'] = 'mainEqub/' . $imageName;
            }
            $create = MainEqub::create($mainEqub);
            
            return response()->json([
                'code' => 200,
                'message' => 'Successfully Created Main Equb',
                'data' => $create
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'code' => 400,
                'message' => 'Something happened!',
                "error" => $ex->getMessage()
            ]);
        }
        
    }

    public function show($id) {
        $mainEqub = MainEqub::where('id', $id)->first();

        return response()->json([
            'data' => $mainEqub
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            // dd($request->all());
            $userData = Auth::user();
            
            if ($userData && ($userData['role'] == "admin" || $userData['role'] == "general_manager" || $userData['role'] == "operation_manager" || $userData['role'] == "it")) {

                // Fetch the MainEqub by ID
                $mainEqub = MainEqub::where('id', $id)->first();

                // Validate the incoming request
                $request->validate([
                    'name' => 'required|string',
                    'created_by' => 'required|integer',
                    'remark' => 'nullable|string',
                    'status' => 'nullable|string',
                    'active' => 'nullable|boolean',
                    'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',  // Image validation
                ]);

                // Build the update array
                $update = [
                    'name' => $request->input('name'),
                    'created_by' => $request->input('created_by'),
                    'active' => $request->input('active'),
                    'status' => $request->input('status'),
                    'remark' => $request->input('remark'),
                ];

                // Handle image upload
                if ($request->file('image')) {
                    $image = $request->file('image');
                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('public/mainEqub', $imageName);
                    $update['image'] = 'mainEqub/' . $imageName;
                }

                // Update the MainEqub
                $mainEqub->update($update);

                // Return success response
                return response()->json([
                    'data' => $mainEqub,
                    'code' => 200,
                    'message' => 'The Equb was successfully updated'
                ]);
            }

            return response()->json([
                'code' => 403,
                'message' => 'Unauthorized to update this Equb'
            ]);

        } catch (Exception $ex) {
            return response()->json([
                'code' => 500,
                'message' => 'Something went wrong: ' . $ex->getMessage()
            ]);
        }
    }

    public function delete() {
        //
    }
}
