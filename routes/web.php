<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ListingController::class, 'showAllListings']);
Route::get('/listings/create', [ListingController::class, 'goToCreateListingPage'])->middleware('auth');
Route::post('/listings', [ListingController::class, 'createListing'])->middleware('auth');
Route::get('/listings/{listing}/edit', [ListingController::class, 'goToEditListingPage'])->middleware('auth');
Route::put('/listings/{listing}', [ListingController::class, 'editListing'])->middleware('auth');
Route::delete('/listings/{listing}', [ListingController::class, 'deleteListing'])->middleware('auth');
Route::get('/listings/manage', [ListingController::class, 'goToManageListingsPage'])-> middleware('auth');
Route::get('/listings/{listing}', [ListingController::class, 'showSingleListing']);

Route::get('/register', [UserController::class, 'goToRegisterPage'])->middleware('guest');
Route::post('/users', [UserController::class, 'createUser']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');
Route::get('/login', [UserController::class, 'goToLoginPage'])->name('login')->middleware('guest');
Route::post('/users/authenticate', [UserController::class, 'authenticateUser']);
