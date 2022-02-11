<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('todo.edit') }}
        </h2>
    </x-slot>

    <div class="my-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('todo.update', $todo) }}">
                        @method('PATCH')
                        @csrf
            
                        <div class="flex gap-3 items-center">
                            <div class="form-check">
                                <input type="hidden" name="is_complete" value="0">
                                <input class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 my-1 align-top bg-no-repeat bg-center bg-contain float-left cursor-pointer" 
                                    type="checkbox" value="1" id="todo_complete_{{ $todo->id }}" name="is_complete" {{ $todo->is_complete ? 'checked' : ''}}>
                            </div>
                            <input id="title" aria-labell="{{ __('todo.create')}}" class="flex-auto rounded-md placeholder:italic placeholder:text-slate-400" placeholder="{{ __('todo.title')}}" type="text" name="title" value="{{old('title', $todo->title)}}" required />
                        
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
