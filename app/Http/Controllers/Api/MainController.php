<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\models\BloodType;
use App\models\Category;
use App\models\City;
use App\models\Client;
use App\models\Donation_request;
use App\models\Post;
use App\models\Governorate;
use App\models\Notification;
use App\models\Setting;
use Illuminate\Container\RewindableGenerator;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;

class MainController extends Controller
{

    //governorates api
    public function governorates()
    {
        $governorates = Governorate::all();
        return responseJson(1, 'success', $governorates);
    }

    //blood_types api
    public function blood_types()
    {
        $bloodTypes = BloodType::all();
        return responseJson(1, 'success', $bloodTypes);
    }

    //cities api
    public function cities(Request $request)
    {
        $cities = City::where(function ($query) use ($request) {
            if ($request->has('governorate_id')) {
                $query->where('governorate_id', $request->governorate_id);
            }
        })->get();
        return responseJson(1, 'success', $cities);
    }

    //posts api after auth
    public function posts()
    {
        $posts = Post::with('category')->get();
        return responseJson(1, 'success', $posts);
    }

    //categories api
    public function categories()
    {
        $categories = Category::all();
        return responseJson(1, 'success', $categories);
    }

    //settings api 
    public function settings()
    {
        $settings = Setting::all();
        return responseJson(1, 'success', $settings);
    }

    //notifications api needs is_seen
    public function notifications(Request $request)
    {

        $notifications = $request->user()::with('notifications')->find($request->user());
        return responseJson(1, 'success', $notifications);
    }

    //search api
    public function search(Request $request)
    {
        $posts = Post::where('category_id', $request->category_id)->get();
        return responseJson(1, $posts);
    }

    // favourit api
    public function favourites(Request $request)
    {
        // $client = Client::where('api_token', $request->api_token)->first();
        $favourites = $request->user()::with('posts')->find($request->user());
        return responseJson(1, 'success', $favourites);
    }

    //get and update notification settings
    public function updateNotifications(Request $request)
    {
        $bloodTypes = $request->user()->bloodTypes()->pluck('blood_type_id')->toArray();
        $governorates = $request->user()->governorates()->pluck('governorate_id')->toArray();
        if ($request->governorate_id) {
            $request->user()->governorates()->sync($request->governorate_id);
        }

        if ($request->blood_type_id) {
            $request->user()->bloodTypes()->sync($request->blood_types_id);
        }
    }

    // favourite toggle api
    public function favouritePost(Request $request)
    {
        $request->user()->posts()->toggle($request->post_id);
        return responseJson(1, 'post is favourited ');
    }

    // donation search api
    public function donationSearch(Request $request)
    {

        /*if ($request->blood_type_id) {
            $donationRequests = Donation_request::where('blood_type_id', $request->blood_type_id)->get();
        }

        if ($request->city_id) {
            $donationRequests = Donation_request::where('city_id', $request->city_id)->get();
        }*/

        if ($request->blood_type_id || $request->city_id) {
            $donationRequests = Donation_request::where('city_id', $request->city_id)
                ->orwhere('blood_type_id', $request->blood_type_id)->get();
        }
        return responseJson(1, $donationRequests);
    }
}
