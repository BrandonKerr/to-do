<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('user.delete_title') }}
        </h2>
    </x-slot>

    <div class="my-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-2xl leading-6 font-large text-indigo-900 mb-4">{{$user->name}}
                        <a href="mailto:{{ $user->email }}" class="text-lg leading-6 font-large text-slate-400 hover:text-indigo-400 ml-1">{{$user->email}}</a>
                    </h3>
                    
                    <p class="border-t border-1 pt-3">{{ __('user.delete_are_you_sure') }}</p>

                    <div class="flex justify-end gap-3">
                    <a href="{{ URL::previous() }}" class="inline-flex rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">{{ __('Cancel') }}</a>
                    <form method="POST" action="{{ route('user.destroy', $user) }}">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="flex-initial py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            {{ __('Delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
