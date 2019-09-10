<?php
    return [
        App\Core\Route::get("|^hall/([0-9]+)/?$|",                               "Hall",                    "show"),
        App\Core\Route::get("|^hall/([0-9]+)/delete/?$|",                        "Hall",                    "delete"),

        # Pages
        App\Core\Route::get("|^contact/?$|",                                     "Main",                    "contact"),
        App\Core\Route::get("|^aboutUs/?$|",                                     "Main",                    "aboutUs"),


        # Admininstrator registration (extra)
        App\Core\Route::get("|^administrator/register/?$|",                      "AdminDashboard",          "getRegister"),
        App\Core\Route::post("|^administrator/register/?$|",                     "AdminDashboard",          "postRegister"),

        # Administrator login
        App\Core\Route::get("|^administrator/login/?$|",                         "Main",                    "getLogin"),
        App\Core\Route::post("|^administrator/login/?$|",                        "Main",                    "postLogin"),

        # User role route
        App\Core\Route::get("|^administrator/halls/?$|",                         "AdminHallManagement",     "halls"),
        App\Core\Route::get("|^administrator/halls/edit/([0-9]+)/?$|",           "AdminHallManagement",     "getEdit"),
        App\Core\Route::post("|^administrator/halls/edit/([0-9]+)/?$|",          "AdminHallManagement",     "postEdit"),
        App\Core\Route::get("|^administrator/halls/add/?$|",                     "AdminHallManagement",     "getAdd"),
        App\Core\Route::post("|^administrator/halls/add/?$|",                    "AdminHallManagement",     "postAdd"),

        #API routes
        App\Core\Route::get("|^api/hall/([0-9]+)/?$|",                           "ApiHall",                 "show"),

        # Fallback route
        App\Core\Route::any("|^.*$|",                                            "Main",                    "home")
    ];