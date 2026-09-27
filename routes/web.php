<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\{
    AdminController,
    LoginController,
    RegistrationController,
    ProfileController,
    ClubController,
    EventController,
    LocationController,
    SearchController,
    ChatController
};
use App\Models\{Club, User, Event, Chat, Country, State, City};


/*
|--------------------------------------------------------------------------
| Route Model Binding
|--------------------------------------------------------------------------
*/
Route::model('club', Club::class);
Route::model('event', Event::class);
Route::model('user', User::class);
Route::model('chat', Chat::class);
Route::model('country', Country::class);
Route::model('state', State::class);
Route::model('city', City::class);


Route::get('/', function (Request $request) {

    $ip = $request->ip(); 
    // $response = Http::get("http://ipinfo.io/{$ip}/json")->json(); 
    $response = Http::get("http://ipinfo.io/106.76.94.122/json")->json(); 

    
    $location = null;

    // $location->city = $response['city'];
    // $location->state = $response['region'];
    // $location->country = $response['country'];

    return view('welcome', [
        'location' => $location
    ]);
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::post('/clubs/{club}/approve', [AdminController::class, 'approveClub'])->name('club.approve');
        Route::post('/clubs/{club}/reject',  [AdminController::class, 'rejectClub'])->name('club.reject');
    });


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {

    Route::get('/login',  [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.perform');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/register',          [RegistrationController::class, 'show'])->name('register.show');
    Route::post('/register',         [RegistrationController::class, 'register'])->name('register.perform');
    Route::get('/register/verify',   [RegistrationController::class, 'verifyEmail'])->name('email.verify');
    Route::get('/check-mail', fn (Request $request) => view('auth.check-email'))->name('check-email');
    Route::get('/expired-url', fn (Request $request) => view('auth.expired-url'))->name('expired-url');
});


/*
|--------------------------------------------------------------------------
| Club Routes
|--------------------------------------------------------------------------
*/
Route::prefix('clubs/{club}')
    ->name('club.')
    ->middleware(['auth'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Club Dashboard + Member-only routes
        |--------------------------------------------------------------------------
        */
        Route::middleware(['clubOwner'])->group(function () {
            // Join Request Management
            Route::post('/join/{user}/accept', [ClubController::class, 'acceptJoinRequest'])->name('join.accept');
            Route::post('/join/{user}/reject', [ClubController::class, 'rejectJoinRequest'])->name('join.reject');

            // Blocking
            Route::post('/block/{user}',   [ClubController::class, 'blockUser'])->name('user.block');
            Route::post('/unblock/{user}', [ClubController::class, 'unblockUser'])->name('user.unblock');
        });

        Route::middleware(['clubMember'])->group(function () {
            Route::delete('/members/{user}', [ClubController::class, 'removeMember'])->name('member.remove');

            // Events
            Route::get('/events/create',  [EventController::class, 'create'])->name('event.create');
            Route::post('/events',        [EventController::class, 'store'])->name('event.store');
            Route::put('/events/{event}', [EventController::class, 'update'])->name('event.update');
            Route::delete('/events/{event}', [EventController::class, 'delete'])->name('event.delete');
        });

        Route::get('/dashboard', [ClubController::class, 'dashboard'])->name('dashboard');
        Route::get('/events/{event}', [EventController::class, 'show'])->name('event.show');
        Route::post('/join/request', [ClubController::class, 'sendJoinRequest'])->name('join.request');

        /*
        |--------------------------------------------------------------------------
        | Ticket purchasing — available for ALL authenticated users
        |--------------------------------------------------------------------------
        */
        Route::post('/events/{event}/purchase', 
            [EventController::class, 'purchaseTicket'])->name('event.purchase');
        /*
        |--------------------------------------------------------------------------
        | Chats — available ONLY for club members + club owner
        |--------------------------------------------------------------------------
        */
        Route::middleware('clubMember')->group(function () {
            Route::post('/chats',          [ChatController::class, 'store'])->name('chat.store');
            Route::delete('/chats/{chat}', [ChatController::class, 'delete'])->name('chat.delete');
            Route::get('/chats/poll', [ChatController::class, 'pollChats'])->name('chat.poll');
        });

    });


/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    
    Route::get('/clubs/create',
[ClubController::class, 'create'])->name('club.create');

    Route::post('clubs/store', 
[ClubController::class, 'store'])->name('club.store');

});

    
/*
|--------------------------------------------------------------------------
| API Routes (Public)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->name('api.')->group(function () {

    // Search
    Route::get('/search/clubs',  [SearchController::class, 'clubs']);
    Route::get('/search/users',  [SearchController::class, 'users']);
    Route::get('/search/events', [SearchController::class, 'events']);

    // Locations
    Route::get('/countries',                  [LocationController::class, 'countries']);
    Route::get('/countries/{country}/states', [LocationController::class, 'states']);
    Route::get('/states/{state}/cities',      [LocationController::class, 'city']);
});
