<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p class="font-semibold text-xl text-gray-800 leading-tight mb-2">Hi, {{ $user->name }}</p>
                    @if ($lists->count())
                    <div class="flex">
                        <p class="inline-flex flex-grow font-semibold text-l text-gray-800">Here are your current To-Dos:</p> 
                        <a href="{{route('list.create')}}"  class="inline-flex rounded-md border border-transparent shadow-sm px-4 py-2 border-indigo-600 text-base font-medium text-indigo-600 hover:bg-indigo-100 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                             {{ __('checklist.create') }}</a>
                    </div>
                        @foreach ($lists as $checklist)
                        <div class="my-2">
                            <x-checklist.show :checklist="$checklist"/>
                        </div>
                        @endforeach
                        
                    @else
                        <p class="font-semibold text-xl text-gray-800">You don't have any To-Do Lists yet!</p> 
                        <span class="text-xl text-gray-800">Get started by clicking <a class="font-medium text-indigo-600 hover:text-indigo-500" href="{{route('list.create')}}">here</a>.</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
