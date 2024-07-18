<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\table_atg_members;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
class Atg_memberController extends Controller
{
    public function index()
    {
        return response()->json(table_atg_members::all(), 200);
    }

    public function show($id)
    {
        $post = table_atg_members::find($id);
        if ($post) {
            return response()->json(['message'=>'Found information',$post], 200);
        }
        return response()->json(['message' => 'Post not found'], 404);
    }

    public function store(Request $request)
    {
         $post = table_atg_members::create($request->all());
         return response()->json($post, 201);
        
        // $validator = Validator::make($request->all(), [
        //     'username' => 'required|string|max:255',
        //     'email' => 'required|email|unique:table_atg_members,email',
        //     'address' => 'nullable|string|max:255',
        // ]);
    
        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }
    
        // $post = table_atg_members::create($request->all());
        // return response()->json($post, 201);
    }

    public function update(Request $request, $id) //method Put
    {
        $post = table_atg_members::find($id);
        if ($post) {
            $post->update($request->all());
            return response()->json($post, 200);
        }
        return response()->json(['message' => 'Post not found'], 404);
    }

    public function destroy($id)
    {
        $post = table_atg_members::find($id);
        if ($post) {
            $post->delete();
            return response()->json(['message' => 'Post deleted'], 200);
        }
        return response()->json(['message' => 'Post not found'], 404);
    }
}
