<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpotRequest;
use App\Models\Category;
use App\Models\Spot;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class SpotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $spots = Spot::with([
                'user:id,name',
                'category:category,spot_id'
            ])

            ->withCount([
                'reviews'
            ])
            ->withSum('reviews', 'rating')
            ->orderBy('created_at', 'desc')
            ->paginate(request('size', 10));

            return Response::json([
                'message' => "List Spot",
                'data' => $spots
            ], 200);
        } catch(Exception $e) {
            return Response::json([
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
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
    public function store(StoreSpotRequest $request)
    {
        try {
            $validated = $request->safe()->all();

            $picture_path = Storage::disk('public')->putFile('picture', $request->file('picture'));
            $validated['user_id'] = Auth::user()->id;
            $validated['picture'] = $picture_path;

            $spot = Spot::create($validated);

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
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Spot $spot)
    {
        try {
            return Response::json([
                'message' => "Detail Spot",
                'data' => $spots->load([
                    'user:id,name',
                    'category:category,spot_id'
                ])
                ->loudCount([
                    'reviews'
                ])
                ->loadSum('reviews','rating')
            ], 200);
        } catch(Exception $e) {
            return Response::json([
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
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
    public function update(UpdateSpotRequest $request, Spot $spot)
    {
        try{
            $validate = $request->safe()->all();

            if(isset($validated['picture'])) {
                $picture_path = Storage::disk('public')->putFile('spots',
                $request->file('picture'));
            }

            if(isset(validated['category'])) {
                Category::where('spot_id', $spot_id)->delete();

                $categories = [];

                foreach($validated['category'] as $category) {
                    $categories[] = [
                        'spot_id' => $spot->id,
                        'category' => $category
                    ];
                }

                Category::fillAndInsert($categories);
            }

            $spot->update([
                'name' => $validated['name'],
                'picture' => $picture_path ?? $spot->picture,
                'address' => $validated['address']
            ]);

            return Response::json([
                'message' => "Berhasil Update spot",
                'data' => $spot
            ], 200);

            } catch(Exception $e) {
            return Response::json([
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Spot $spot)
    {
        try {
            $user = Auth::user();
            if($spot->user_id == $user_id || $user->role == 'ADMIN') {
                if ($spot->delete()) {
                    return Response::json([
                        'message' => "Spot berhasil di hapus",
                        'data' => null
                    ], 200);
                }
            } else {
                return response::json([
                    'message' => "Spot gagal di hapus",
                    'data' => null
                ], 200);
            }
        } catch(Exception $e) {
            return Response::json([
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
