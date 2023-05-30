<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Person;
use Validator;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        {
            try {
                $people = Person::orderBy('id', 'desc')->paginate(5);
                return response()->json(['message' => 'People fetched successfully', 'data' => $people], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required'],
            'name' => 'required|string',
            'identification' => 'required|string',
            'email' => 'string|email|max:191|unique:people|',
            'phone_number' => 'required|string',
            'description' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),400);
        }

        $person = Person::create([
            'type' => $request->type,
            'name' => $request->name,
            'identification' => $request->identification,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'description' => $request->description,

        ]);

        return response()->json(['person' => $person], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $person = Person::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Person not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Person retrieved successfully.',
            'data' => $person
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Person $person)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required'],
            'name' => 'required|string',
            'identification' => 'required|string',
            'email' => 'string|email|max:191|unique:people|',
            'phone_number' => 'required|string',
            'description' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $person->update($request->except('type'));

        return response()->json([
            "success" => true,
            "message" => "Person updated successfully.",
            'data' => $person->refresh()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        try {
            $person->delete();
            return response()->json([
                "success" => true,
                "message" => "Person deleted successfully.",
                "data" => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Failed to delete person.",
                "data" => null
            ], 500);
        }
    }
}
