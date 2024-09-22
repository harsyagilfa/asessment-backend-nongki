<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profile = Profile::where('user_id',Auth::id())->first();
        if (!$profile) {
            return response()->json(['message' => 'Profile Tidak Ditemukan'], 404);
        }
        return response()->json($profile);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'status_pernikahan' => 'required|string',
        ]);
        $user = $request->attributes->get('user');
        $profile = Profile::create([
            'user_id' => auth()->id(),
            'nama_lengkap' => $request->nama_lengkap,
            'alamat' => $request->alamat,
            'jenis_kelamin' => $request->jenis_kelamin,
            'status_pernikahan' => $request->status_pernikahan,
        ]);

        return response()->json($profile,201);
    }


    public function show()
    {
        $profile = Profile::where('user_id', Auth::id())->first();
        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }
        return response()->json($profile);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'sometimes|required|string|max:255',
            'alamat' => 'sometimes|required|string|max:255',
            'jenis_kelamin' => 'sometimes|required|in:Laki-laki,Perempuan',
            'status_pernikahan' => 'sometimes|required|string|max:255',
        ]);
        $profile = Profile::where('user_id', Auth::id())->first();
        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }
        $profile->update($request->only(['nama_lengkap', 'alamat', 'jenis_kelamin', 'status_pernikahan']));
        return response()->json($profile);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $profile = Profile::where('user_id', Auth::id())->first();

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $profile->delete();

        return response()->json(['message' => 'Profile deleted successfully']);
    }
}
