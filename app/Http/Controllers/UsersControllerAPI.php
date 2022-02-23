<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UsersControllerAPI extends Controller
{
    public function index()
    {
        $posts = User::all();

        return response()->json([
            'success' => true,
            'message' =>'List All Users',
            'data'    => $posts
        ], 200);
    }

    public function store(Request $request)
	{
	    $validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required',
			'password' => 'required'
		]);

			$nama = $request->input('name');
			$email = $request->input('email');
			$password = Hash::make($request->input('password'));
			$photo = $request->file('photo');
			$path = 'Assets/UsersPhoto'; 
			$photo_name = time().$photo->getClientOriginalName();
			$photo_validate = str_replace(" ", "", $photo_name);
			$photo->move($path, $photo_validate);

		if($validator->fails()) {
			return response()->json([
				'success' => false,
				'message' => 'Data Gagal disimpan',
				'data' => $validator->errors(),
			], 401);
		} else {
			$user = User::create([
				"user_name" => $nama,
				"user_photo" => url('Assets/UsersPhoto/'.$photo_name),
				"user_email" => $email,
				"user_password" => $password,
				"user_role" => 'customer',
			]);

			if($user) {
				return response()->json([
					'success' => true,
					'message' => 'User berhasil disimpan',
					'data' => $user
				], 201);
			} else {
				return response()->json([
					'success' => false,
					'message' => 'User gagal disimpan',
				], 400);
			}
		}
	}

	public function show($id)
	{
	   $post = User::find($id);

	   if ($post) {
	       return response()->json([
	           'success'   => true,
	           'message'   => 'Detail User!',
	           'data'      => $post
	       ], 200);
	   } else {
	       return response()->json([
	           'success' => false,
	           'message' => 'User Tidak Ditemukan!',
	       ], 404);
	   }
	}

	public function update(Request $request, $id)
	{

			$dt = [];	

			$nama = $request->input('name');
			$email = $request->input('email');
			$password = Hash::make($request->input('password'));
			$photo = $request->file('photo');

			if($nama !== null && !empty($nama)) {
				$dt['user_name'] = $nama;
			}

			if($email !== null && !empty($email)) {
				$dt['user_email'] = $email;
			} 

			if($password !== null && !empty($password)) {
				$dt['user_password'] = $password;
			}
			
			if($photo !== null && !empty($photo)) {
				$path = 'Assets/UsersPhoto'; 
				$photo_name = time().$photo->getClientOriginalName();
				$photo_validate = str_replace(" ", "", $photo_name);
				$photo->move($path, $photo_validate);
				$dt['user_photo'] = url('Assets/UsersPhoto/'.$photo_name);
			}
			
			$post = User::where('user_id', $id)->update($dt);

			if($post) {
				return response()->json([
					'success' => true,
					'message' => 'Berhasil update User',
					'data' => $dt
				], 201);
			} else {
				return response()->json([
					'success' => false,
					'message' => 'Gagal update User',
				], 400);
			}
		
	}

	public function destroy($id)
	{
	$post = User::where('user_id', $id)->first();
			
	$post->delete();

	    if ($post) {
	        return response()->json([
	            'success' => true,
	            'message' => 'User Berhasil Dihapus!',
	        ], 200);
	    }

	}
}