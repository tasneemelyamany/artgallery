<?php

namespace App\Http\Controllers;

use App\Models\Egyptian_Images;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class ImageController extends Controller
{
    public function storeImage(Request $request)
    {
        $request->validate([
            'caption' => 'required|max:255',
            'category' => 'required',
            'image' => 'required|image|mimes:png,jpg,jpeg,bmp',
        ], [
            'category.required' => 'Please select a Category to upload the image',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_name = rand(1000, 9999) . time() . '.' . $file->getClientOriginalExtension();
            $thumbpath = public_path('user_images/thumb');

            $resize_image = (new ImageManager())->make($file->getRealPath())
                ->resize(300, 200, function ($c) {
                    // You can add any additional options here if needed
                });

            // Save the resized image to the thumb directory
            $resize_image->save($thumbpath . '/' . $image_name);

            // Move the original image to the user_images directory
            $file->move(public_path('user_images'), $image_name);

            // Save the image information to the database
            $user = auth()->user(); // Assuming you have a User model and the user is logged in
            $image = new Egyptian_Images([
                'user_id' => $user->id,
                'caption' => $request->input('caption'),
                'category' => $request->input('category'),
                'image' => $image_name,
            ]);

            $image->save();

            return redirect()->back()->with('success', 'Image uploaded successfully.');
        }

        return redirect()->back()->with('error', 'Image not uploaded. Please try again.');
    }
}
