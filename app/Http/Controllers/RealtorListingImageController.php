<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\ListingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RealtorListingImageController extends Controller
{
    public function create(Listing $listing) {
        $listing->load(['images']);     // load all the related images of the listing
        return inertia(
            'Realtor/ListingImage/Create',
            ['listing' => $listing]
        );
    }

    public function store(Listing $listing, Request $request) {
        if ($request->hasFile('images')) {        // check if there is file uploaded
            $request->validate([        // will automatically stop the request once there is any sent data does not pass the validation
                'images.*' => 'mimes:jpg,png,jpeg,webp|max:5000'     // '*' is used so all the elements in the array must follow the rules stated, 5000 means 5 megabytes
            ], [
                'images.*.mimes' => 'The file should be in one of the formats: jpg, png, jpeg, webp'
            ]);

            foreach ($request->file('images') as $file) {       // handling multiple files
                $path = $file->store('images', 'public');        // move the file to the public disk

                $listing->images()->save(new ListingImage([     // save relationship
                    'filename' => $path
                ]));
            }
        }

        return redirect()->back()->with('success', 'Images uploaded!');
    }

    public function destroy($listing, ListingImage $image) {
        Storage::disk('public')->delete($image->filename);      // Access the local disk and delete the image
        $image->delete();       // Remove record form the database

        return redirect()->back()->with('success', 'Image was deleted');
    }
}
