<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
	public function changePassword(){
		return view('setting-password');
	}

	public function changePasswordSave(Request $request){

		        // Validate the incoming request data
        $validatedData = $request->validate([
            'old-password' => 'required|string',
            'password' => [
					        'required',
					        'string',
					        'min:6',              // Minimum 6 characters
					        'regex:/[a-zA-Z]/',   // Must contain at least one letter
					        'regex:/[0-9]/',      // Must contain at least one digit
					        'regex:/[^a-zA-Z0-9]/', // Must contain at least one symbol
					        'confirmed'
					    ],
            'password_confirmation' => 'required|string'
        ], [
            'old-password.required' => 'Password lama tidak boleh kosong',
            'password.required' => 'Password baru tidak boleh kosong',
		    'password.min' => 'Password minimal 6 karakter',
    		'password.regex' => 'Password harus memiliki minimal satu huruf, satu angka, dan satu simbol',
    		'password.confirmed' => 'Konfirmasi password baru tidak cocok',
            'password_confirmation.required' => 'Konfirmasi password baru tidak boleh kosong'
        ]);

        $userId = (Auth::user())->id;

        // take current password
        $data = User::select('password')
                ->where('id', $userId)
                ->first();

        // check if same as input
        $oldPassword = $request->input('old-password');
        $newPassword = $request->input('password');

        $md5_oldPass = md5($oldPassword);
        $md5_newPass = md5($newPassword);

        if($md5_oldPass == $data['password']){
        	// Find the user by ID
	        $user = User::findOrFail($userId);

	        // Update the user's data
	        $user->password = $md5_newPass;

	        // Save the updated data to the database
	        $user->save();

	        return redirect()->back()->with('success', 'Berhasil merubah password');

        } else {
        	// Incorrect old password
        	session()->flash('error', 'Password lama salah');
        	return redirect()->back();
        }

        


	}
}