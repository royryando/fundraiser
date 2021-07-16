@extends('layouts.app.account.default')
@section('title', 'My Campaigns')
@php($active = 'my-campaigns')
@section('content')
    <div class="">
        <h2 class="text-xl">My Campaigns</h2>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                <button class="px-3 py-2 text-sm text-gray-100 rounded-lg bg-blue-700 hover:bg-blue-600">Create a new campaign</button>
            </div>
        </div>
        <div class="col-span-12 overflow-auto lg:overflow-visible mt-6">
            <table class="min-w-full leading-normal">
                <thead>
                <tr>
                    <th class="text-left whitespace-nowrap pl-3">CAMPAIGN NAME</th>
                    <th class="text-left whitespace-nowrap">TARGET / COLLECTED</th>
                    <th class="text-center whitespace-nowrap">SINCE</th>
                    <th class="text-center whitespace-nowrap">STATUS</th>
                    <th class="text-center whitespace-nowrap">ACTIONS</th>
                </tr>
                </thead>
                <tbody>
                @for($i=0;$i<10;$i++)
                <tr class="shadow-sm rounded-md">
                    <td class="py-4 my-2 pl-3">Lorem Ipsum dolor sit amet</td>
                    <td>Rp25,000,000 / Rp12,354,500 (48%)</td>
                    <td class="text-center">April 24, 2000</td>
                    <td class="w-40">
                        {{--<div class="flex items-center justify-center text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-2 w-4 h-4 mr-2">
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                            Inactive
                        </div>--}}
                        <div class="flex items-center justify-center text-green-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-2 w-4 h-4 mr-2">
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                            Active
                        </div>
                    </td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center mr-3" href="javascript:;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-1 w-4 h-4 mr-1">
                                    <polyline points="9 11 12 14 22 4"></polyline>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                </svg>
                                Edit
                            </a>
                            <a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1 w-4 h-4 mr-1">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                                Delete
                            </a>
                        </div>
                    </td>
                </tr>
                @endfor
                </tbody>
            </table>
        </div>
    </div>
@endsection
