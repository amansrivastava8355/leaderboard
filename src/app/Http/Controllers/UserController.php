<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Winner;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Info(
 *     title="Leaderboard API",
 *     version="1.0.0",
 *     description="API documentation for the Leaderboard application"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="apiToken",
 *     type="apiKey",
 *     in="header",
 *     name="api-token",
 *     description="API token to authorize requests"
 * )
 */
class UserController extends Controller
{
    /**
    * @OA\Get(
    * path="/api/users",
    * summary="Get a list of users",
    * tags={"Users"},
    * security={{"apiToken":{}}},
    * @OA\Response(
    * response=200,
    * description="List of users",
    * ),
    * )
    */
    public function index()
    {
        return User::orderBy('points', 'desc')->get();
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     security={{"apiToken":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="age", type="integer"),
     *                 @OA\Property(property="address", type="string"),
     *                 @OA\Property(property="photo", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     tags={"Users"},
     *     @OA\Response(response="201", description="Create a user.")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'age' => 'required|integer',
            'address' => 'required',
            'photo' => 'required|image'
        ]);

        $photo = $request->file('photo');
        $path = $photo->storePublicly('photos', 's3');

        $user = User::create([
            'name' => $request->name,
            'age' => $request->age,
            'address' => $request->address,
            'photo_url' => Storage::disk('s3')->url($path),
            'points' => 0
        ]);

        return response()->json($user, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{user}",
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     tags={"Users"},
     *     @OA\Response(response="200", description="Display a user.")
     * )
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{user}",
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     tags={"Users"},
     *     @OA\Response(response="204", description="Delete a user.")
     * )
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }

    /**
     * @OA\Patch(
     *     path="/api/users/{user}/points",
     *     security={{"apiToken":{}}},
     *     summary="Update user points",
     *     description="Increment or decrement user points based on the value provided. Positive values increment the points, while negative values decrement the points.",
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="ID of the user whose points are to be updated"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="points", type="integer", description="The points to be added or subtracted. Positive values increment points, negative values decrement points.")
     *             )
     *         )
     *     ),
     *     tags={"Users"},
     *     @OA\Response(response="200", description="User points updated successfully."),
     *     @OA\Response(response="404", description="User not found."),
     * )
     */
    public function updatePoints(Request $request, User $user)
    {
        $request->validate([
            'points' => 'required|integer'
        ]);

        $user->points += $request->points;
        $user->save();

        return response()->json($user, 200);
    }
}
