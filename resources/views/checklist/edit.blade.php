<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('checklist.edit') }}
        </h2>
    </x-slot>

    <div class="my-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('list.update', $checklist) }}">
                        @method('PATCH')
                        @csrf
            
                        <div class="flex gap-3 items-center">
                            <label for="title" class="flex-initial text-gray-500">{{ __('checklist.title') }}</label>
                            <input id="title" class="flex-auto rounded-md" type="text" name="title" value="{{old('title', $checklist->title)}}" required autofocus />
                        
                            <button class="flex-initial py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
