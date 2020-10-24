<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\models\BloodType;
use App\models\Client;
use App\models\ContactUs;
use App\models\Donation_request;
use App\models\Notification;
use Dotenv\Validator as DotenvValidator;
use Illuminate\Auth\Events\Validated;
use Illuminate\Contracts\Validation\Validator as ContractsValidationValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{

    //register api
    public function register(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'phone' => 'required|unique:clients',
            'password' => 'required|confirmed',
            'name' => 'required',
            'email' => 'required|unique:clients|email',
            'd_o_b' => 'required|date',
            'last_donation_date' => 'required|date',
            'blood_type_id' => 'required',
            'city_id' => 'required'
        ]);
        if ($validation->fails()) {
            return responseJson(0, $validation->errors()->first(), $validation->errors());
        }

        $request->merge(['password' => bcrypt($request->password)]);
        $client = Client::create($request->all());
        $client->api_token = str::random(60);
        $client->save();
        return responseJson(1, 'registeration succeeded');
    }


    //login api
    public function login(Request $request)
    {

        $client = client::where('phone', $request->phone)->first();

        if ($client) {
            if (Hash::check($request->password, $client->password)) {
                return responseJson(1, 'logged in', [
                    'api_token' => $client->api_token,
                    'client' => $client
                ]);
            } else {
                return responseJson(0, 'wrong password entered..');
            }
        } else {
            return responseJson(0, 'wrong phone number!');
        }
    }

    //contactUs api
    public function contactUs(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'phone' => 'required',
            'title' => 'required',
            'message' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:clients'
        ]);

        if ($validation->fails()) {
            return responseJson(0, $validation->errors()->first(), $validation->errors());
        }

        $contactUs = ContactUs::create($request->all());
        $contactUs->save();
        return responseJson(1, 'we will reach out to you shortly');
    }

    //edit profile api
    public function editProfile(Request $request)
    {
        $vali = validator()->make($request->all(), [
            'phone' => Rule::unique('clients')->ignore($request->user()->id),
            'email' => [Rule::unique('clients')->ignore($request->user()->id), 'email'],
            'password' => 'confirmed'
        ]);

        if ($vali->fails()) {
            return responseJson(0, $vali->errors()->first(), $vali->errors());
        }

        //$client = Client::where('api_token', $request->api_token)->first();
        $client = $request->user();
        $request->merge(['password' => bcrypt($request->password)]);
        $client->update($request->all());
        $client->save();

        /*if ($request->password) {
            $request->merge(['password' => bcrypt($request->password)]);
            $client->password = $request->password;
            $client->save();
        }

        if ($request->phone) {
            $client->phone = $request->phone;
            $client->save();
        }

        if ($request->name) {
            $client->name = $request->name;
            $client->save();
        }


        if ($request->email) {
            $client->email = $request->email;
            $client->save();
        }

        if ($request->d_o_b) {
            $client->d_o_b = $request->d_o_b;
            $client->save();
        }

        if ($request->blood_type_id) {
            $client->blood_type_id = $request->blood_type_id;
            $client->save();
        }

        if ($request->last_donation_date) {
            $client->last_donation_date = $request->last_donation_date;
            $client->save();
        }

        if ($request->city_id) {
            $client->city_id = $request->city_id;
            $client->save();
        }*/


        return responseJson(1, $client);
    }


    //password reset api

    public function passwordReset(Request $request)
    {
        $user = Client::where('phone', $request->phone)->first();
        if ($user) {
            $code = rand(1111, 9999);
            $update = $user->update(['pin_code' => $code]);
            if ($update) {

                Mail::to($user->email)
                    ->bcc("m7md7alim@hotmail.com")
                    ->send(new ResetPassword($code));
                return responseJson(1, 'your pin code is', $code);
            } else {
                return responseJson(0, 'something went wrong please try again');
            }
        } else {
            return responseJson(0, 'there is no account associated with this phone number');
        }
    }

    //New password api
    public function newPassword(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'phone' => 'required',
            'password' => 'confirmed',
            'pin_code' => 'required'
        ]);

        if ($validation->fails()) {
            return responseJson(0, $$validation->errors()->first(), $validation->errors());
        }
        $client = Client::Where('phone', $request->phone)->first();

        if ($request->password) {
            $request->merge(['password' => bcrypt($request->password)]);
            $client->password = $request->password;
            $client->save();
            return responseJson(1, 'your password has be updated');
        }
    }


    //create donation request api
    public function createDonation(Request $request)
    {
        $user = $request->user();
        $request->merge(['client_id' => $user->id]);
        $validation = validator()->make($request->all(), [
            'patient_name' => 'required',
            'patient_age' => 'required',
            'hospital_name' => 'required',
            'blood_type_id' => 'required',
            'num_of_blood_bags' => 'required',
            'hospital_address' => 'required',
            'city_id' => 'required',
            'patient_phone' => 'required',
            'notes' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);
        if ($validation->fails()) {
            return responseJson(0, $validation->errors()->first(), $validation->errors);
        }

        $donation = Donation_request::create($request->all());
        $donation->save();
        //update notifications
        $bloodtype = BloodType::where('id', $donation->blood_type_id)->first();

        $clients = $donation->city->governorate->clients()->whereHas(
            'bloodTypes',
            function ($q) use ($request) {
                $q->where('blood_type_id', $request->blood_type_id);
            }
        )->pluck('clients.id')->toArray();
        if ($clients) {
            $notification = $donation->notifications()->create([
                'title' =>  $bloodtype->name,
                'content' => $donation->notes
            ]);
            $notification->clients()->attach($clients);
        }

        return responseJson(1, 'donation request is created', $notification);
    }
}
