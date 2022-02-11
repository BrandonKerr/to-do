<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->is(Auth::user()) ? __('user.me') :  __('user.edit') }}
        </h2>
    </x-slot>

    <div class="my-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="flex-1 text-2xl leading-6 font-large text-indigo-900 mb-4">{{$user->name}}
                        <a href="mailto:{{ $user->email }}" class="text-lg leading-6 font-large text-slate-400 hover:text-indigo-400 ml-1">{{$user->email}}</a>
                    </h3>
                    
                    <form method="POST" action="{{ route('user.update', $user) }}">
                        @method('PATCH')
                        @csrf
                        <!-- Name -->
                        <div>
                            <x-label for="name" :value="__('Name')" />

                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-label for="email" :value="__('Email')" />

                            <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                        </div>

                        @can('assignRoles', App\Models\User::class)
                            <div class="form-check mt-4">
                                <input type="hidden" name="is_admin" value="0">
                                <input class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 my-1 align-top bg-no-repeat bg-center bg-contain float-left cursor-pointer" 
                                    type="checkbox" value="1" id="is_admin" name="is_admin" {{ old('is_admin', $user->is_admin) ? 'checked' : ''}}>
                                <label class="form-check-label inline-block text-gray-800 ml-2" for="is_admin">
                                    {{ __('Administrator') }}
                                </label>
                            </div>
                        @endcan

                        {{-- 
                            It would be nice to have a better UX here and toggle whether or not to show the password fields and do validation and password update  
                            accordingly. But in the interest of time, let's just make use of the existing password reset functionality at the login page
                        --}}

                        <div class="flex justify-end gap-3 mt-3">
                            <a href="{{ URL::previous() }}" class="inline-flex rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">{{ __('Cancel') }}</a>
                            
                            <button type="submit" class="flex-initial py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
