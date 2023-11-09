<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListingController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Listing::class, 'listing');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'priceFrom', 'priceTo', 'beds', 'baths', 'areaFrom', 'areaTo'
        ]);

        return inertia(
            'Listing/Index',
            [
                'filters' => $filters,
                // when() method allows us to conditionally build queries. The first argument that is passed will be evaluated 
                // to check whether it is true or false. If it is false, the fn() function will not be executed and vice versa.
                // If it is true, we will get two predefined arguments, the query and the value from the first expression ($filters[''])
                'listings' => Listing::mostRecent()     // sort the listing based on the created date time
                    ->filter($filters)          // filter listings based on the request information
                    ->withoutSold()             // retrive the listings that have not been sold
                    ->paginate(10)      // how many listing in each page (return Object)
                    ->withQueryString()     // the query string can be maintained even though we have switched to other pages
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Listing $listing)
    {
        // option 1
        // if (Auth::user()->cannot('view', $listing)) {
        //     abort(403);     // HTTP code for action forbidden, this action will stop the controller
        // }

        // option 2
        // $this->authorize('view', $listing);     // check if the current user is authorized to perform this view operation on this model
        // if not, 403 code will automatically being returned

        $listing->load('images');
        $offer = !Auth::user() ? null : $listing->offers()->byMe()->first();
        
        return inertia(
            'Listing/Show',
            [
                'listing' => $listing,
                'offerMade' => $offer
            ]
        );
    }
}