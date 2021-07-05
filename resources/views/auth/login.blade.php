@extends('layouts.auth-layout')
@section('title', 'Login')
@section('content')
    <div class="p-10 xs:p-0 mx-auto md:w-full md:max-w-md">
        <div class="grid grid-cols-1 py-3">
            <img class="box-border h-24 w-24 mx-auto" src="{{ asset('img/logo.png') }}" alt="Fundraiser">
        </div>
        <div class="bg-white shadow w-full rounded-lg divide-y divide-gray-200">
            @include('layouts.alert')
            <div class="px-5 py-7">
                <form action="{{ route('auth.post-login') }}" method="POST">
                    @csrf
                    <label for="email" class="font-semibold text-sm text-gray-600 pb-1 block">E-mail</label>
                    <input id="email" name="email" type="email" class="border rounded-lg px-3 py-2 mt-1 mb-5 text-sm w-full" required />
                    <label for="password" class="font-semibold text-sm text-gray-600 pb-1 block">Password</label>
                    <input id="password" name="password" type="password" class="border rounded-lg px-3 py-2 mt-1 mb-5 text-sm w-full" />
                    <button type="submit" class="transition duration-200 bg-blue-500 hover:bg-blue-600 focus:bg-blue-700 focus:shadow-sm focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 text-white w-full py-2.5 rounded-lg text-sm shadow-sm hover:shadow-md font-semibold text-center inline-block">
                        <span class="inline-block mr-2">Login</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 inline-block">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </button>
                </form>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-2 gap-1">
                    <button type="button" class="transition duration-200 border border-gray-200 text-gray-500 w-full py-2.5 rounded-lg text-sm shadow-sm hover:shadow-md font-normal text-center inline-block">Google</button>
                    <button type="button" class="transition duration-200 border border-gray-200 text-gray-500 w-full py-2.5 rounded-lg text-sm shadow-sm hover:shadow-md font-normal text-center inline-block">Facebook</button>
                </div>
            </div>
            <div class="py-5">
                <div class="grid grid-cols-1 gap-1">
                    <div class="text-center sm:text-left whitespace-nowrap">
                        <button class="transition duration-200 mx-5 px-5 py-4 cursor-pointer font-normal text-sm rounded-lg text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-200 focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50 ring-inset">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 inline-block align-text-top">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                            <span class="inline-block ml-1">Forgot Password</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-5">
            <div class="grid grid-cols-2 gap-1">
                <div class="text-center sm:text-left whitespace-nowrap">
                    <a href="{{ route('index') }}" class="transition duration-200 mx-5 px-5 py-4 cursor-pointer font-normal text-sm rounded-lg text-gray-500 hover:bg-gray-200 focus:outline-none focus:bg-gray-300 focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50 ring-inset">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 inline-block align-text-top">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span class="inline-block ml-1">Back to {{ config('app.name') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
