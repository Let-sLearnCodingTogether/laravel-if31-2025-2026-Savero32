<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Http\Request;

class SpotController extends Controller
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
    public function store(StoreSpotRequest $request)
    {
        try {
            $validated = $request->safe()->all();

            $picture_path = Storage::disk('public')->putFile('spots', $request->file('picture'));
            $validated['user_id'] = Auth::user()->id;
            $validated['picture'] = $picture_path;

            $Spot = Spot::create($validated);

            if($spot) {
                $categories = [];

                foreach($validated['category'] as $category) {
                    $categories[] = [
                        'spot_id' => $spot->id,
                        'category' => $category
                    ];
                }

                
                Category::fillAndInsert($categories);

                return Response::json([
                    'message' => "Berhasil Menyimpan Data",
                    'data' => null
                ], 201);
            }
        } catch(Exception $e) {
            return Response::json([
                'message' => $e->getMassage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Spot $spot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Spot $spot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Spot $spot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Spot $spot)
    {
        //
    }
}
