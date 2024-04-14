<?php

namespace App\Http\Controllers;

use App\Models\Mydetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use file;

class WebbitechController extends Controller
{
    public function insert_form()
    {
        return view('insert_form');
    }

    // public function fileupload($file, $destinationPath, $dir)
    // {
    //     $fileName = time() . '_' . $file->getClientOriginalName(); // Add timestamp to avoid filename conflicts
    //     $file->move($destinationPath, $fileName);
    //     return $fileName; // Return only the filename, not the full path
    // }

    public function insert(Request $request)
    {
        // Validate the form data, including the uploaded image
        $request->validate([
            'name' => 'required',
            'password' => 'required',
            'email' => 'required|email',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validating the image upload
        ]);

        // Handle the image upload
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = time() . '_' . $image->getClientOriginalName(); // Adding timestamp to the image name to avoid conflicts
            $image->move(public_path('images'), $imageName); // Move the uploaded image to the public/images directory
            $photoPath = 'images/' . $imageName; // Save the path to the image in the database
        } else {
            $photoPath = ''; // If no new image is uploaded, retain an empty string as the path
        }

        // Insert data into the database
        $list = Mydetails::create([
            'name' => $request->name,
            'password' => $request->password,
            'email' => $request->email,
            'photo' => $photoPath, // Save the path to the image in the database
        ]);

        return redirect('/list')->with('success', 'Record inserted successfully');
    }


    public function list()
    {
        $list = Mydetails::all();
        return view('list',compact('list'));
    }

    public function edit($id)
    {
        $edits = Mydetails::find($id);
        return view('update',compact('edits'));
    }

    public function update(Request $request, $id)
    {
        // Validate the form data, including the uploaded image
        $request->validate([
            'uname' => 'required',
            'upassword' => 'required',
            'uemail' => 'required|email',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Example validation for image upload
        ]);

        // Handle the image upload
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $photoPath = 'images/'.$imageName;
        } else {
            $photoPath = ''; // If no new image is uploaded, retain the existing image path
        }

        // Update the user data in the database
        $user = Mydetails::findOrFail($id);
        $user->name = $request->uname;
        $user->password = $request->upassword;
        $user->email = $request->uemail;
        $user->photo = $photoPath; // Update the photo path with the new path or the existing one
        $user->save();

        return redirect('list')->with('success', 'User updated successfully');
    }

    public function delete($id)
    {
        DB::delete('delete from mydetails where id=?',[$id]);
        return redirect('list');
    }
}
