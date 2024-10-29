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
use App\Http\Resources\Api\MainEqubResource;
use App\Models\EqubType;

class MainEqubController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
        $this->middleware('permission:update main_equb', ['only' => ['update', 'edit']]);
        $this->middleware('permission:delete main_equb', ['only' => ['destroy']]);
        $this->middleware('permission:view main_equb', ['only' => ['index', 'show']]);
        $this->middleware('permission:create main_equb', ['only' => ['store', 'create']]);
    }
    public function getTypes() {
        $types = EqubType::with('mainEqub')->get();
        return response()->json([
            'data' => $types
        ]);

        return view('admin/equbType.equbTypeList', $types);
    }

    public function index() {
        $userData = Auth::user();
        // dd($userData);
        try {
            
            $mainEqubs = MainEqub::with('subEqub')->get();

            // $mainEqubData = $mainEqubs->map(function ($equb) {
            //     return [
            //         'id' => $equb->id,
            //         'name' => $equb->name,
            //         'created_by' => $equb->created_by,
            //         'remark' => $equb->remark,
            //         'status' => $equb->status,
            //         'active' => $equb->active,
            //         'created_at' => $equb->created_at,
            //         'updated_at' => $equb->updated_at,
            //         'image_url' => $equb->image ? asset('storage/' . $equb->image) : null, // Generates the full URL
            //         'subEqub' => $equb->subEqub, // Include related subEqubs if needed
            //     ];
            // });
            return response()->json([
                'data' => MainEqubResource::collection($mainEqubs),
                'code' => 200,
            ]); 
            
        } catch (Exception $ex) {
            return response()->json([
                'code' => 500,
                'message' => 'Unable to process your request, Please try again!',
                "error" => $ex->getMessage()
            ]);
        }
        
    }


    public function store(Request $request) {
        $userData = Auth::user();
        
        try {
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
                    'created_by' => $userData->id,
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
                    'data' => [
                        'main_equb' => $create,
                        'image_url' => url($create->image)
                    ],
                ]);
            
        } catch (Exception $ex) {
            return response()->json([
                'code' => 400,
                'message' => 'Something went wrong!',
                "error" => $ex->getMessage()
            ]);
        }
        
    }

    public function show($id) {
        
        $mainEqub = MainEqub::where('id', $id)->with('subEqub')->first();
        return response()->json([
            'data' => $mainEqub
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            // dd($request->all());
            $userData = Auth::user();
            
            // if ($userData && in_array($userData['role'], ['admin', "equb_collector", "role", "it"])) {

                // Fetch the MainEqub by ID
                $mainEqub = MainEqub::where('id', $id)->with('subEqub')->first();

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
                    'created_by' => $userData->id,
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
            // } else {
            //     return response()->json([
            //         'code' => 403,
            //         'message' => 'Unauthorized to update this Equb'
            //     ]);
            // }

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
