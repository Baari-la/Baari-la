<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
{
    /*
    |--------------------------------------------------------------------------
    | Locale
    |--------------------------------------------------------------------------
    */

    $currentLocale =
        session(
            'locale',
            config('app.locale')
        );

    /*
    |--------------------------------------------------------------------------
    | Authenticated User
    |--------------------------------------------------------------------------
    */

    $user = $request->user();

    /*
    |--------------------------------------------------------------------------
    | Digital Directory Program
    |--------------------------------------------------------------------------
    |
    | User dianggap sebagai peserta program apabila sudah terhubung dengan
    | digital_directory_participants melalui user_id.
    |
    | Ownership verification / company_id tidak menentukan apakah user
    | boleh melihat Program Portal.
    |
    */

    $digitalDirectoryParticipant = null;

    if ($user) {

        $digitalDirectoryParticipant =
            \App\Models\DigitalDirectoryParticipant::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->latest('id')
                ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Shared Props
    |--------------------------------------------------------------------------
    */

    return array_merge(
        parent::share($request),
        [

            /*
            |--------------------------------------------------------------------------
            | Login & Locale
            |--------------------------------------------------------------------------
            */

            'isLoggedIn' =>
                !is_null($user),

            'locale' =>
                $currentLocale,

            /*
            |--------------------------------------------------------------------------
            | Translations
            |--------------------------------------------------------------------------
            */

            'translations' =>
                file_exists(
                    lang_path(
                        "{$currentLocale}.json"
                    )
                )
                    ? json_decode(
                        file_get_contents(
                            lang_path(
                                "{$currentLocale}.json"
                            )
                        ),
                        true
                    )
                    : [],

            /*
            |--------------------------------------------------------------------------
            | Flash
            |--------------------------------------------------------------------------
            */

            'flash' => [
                'message' =>
                    $request
                        ->session()
                        ->get('message'),
            ],

            /*
            |--------------------------------------------------------------------------
            | Authentication
            |--------------------------------------------------------------------------
            */

            'auth' => [

                'user' =>
                    $user
                        ? [
                            'id' =>
                                $user->id,

                            'name' =>
                                $user->name,

                            'email' =>
                                $user->email,

                            'role' =>
                                $user->role,

                            'company_id' =>
                                $user->company_id,

                            'member_status' =>
                                $user->member_status ??
                                'Free',

                            'locale' =>
                                $user->locale ??
                                $currentLocale,

                            'is_api_member' =>
                                !empty(
                                    $user->member_number
                                ),

                            'member_number' =>
                                $user->member_number,
                        ]
                        : null,

                /*
                |--------------------------------------------------------------------------
                | Digital Directory Program
                |--------------------------------------------------------------------------
                */

                'has_digital_directory_program' =>
                    !is_null(
                        $digitalDirectoryParticipant
                    ),

                'digital_directory_program' =>
                    $digitalDirectoryParticipant
                        ? [
                            'id' =>
                                $digitalDirectoryParticipant->id,

                            'package' =>
                                $digitalDirectoryParticipant->package,

                            'payment_status' =>
                                $digitalDirectoryParticipant->payment_status,

                            'activation_status' =>
                                $digitalDirectoryParticipant->activation_status,

                            'company_id' =>
                                $digitalDirectoryParticipant->company_id,
                        ]
                        : null,
            ],
        ]
    );
}
}