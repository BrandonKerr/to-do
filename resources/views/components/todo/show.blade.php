<div class="flex gap-3 justify-center">
    <form method="POST" action="{{ route('todo.update', $todo) }}">
        @method('PATCH')
        @csrf
        <div class="form-check">
            {{-- replicate the title value here to simplify routing and logic for updates --}}
            <input type="hidden" name="title" value="{{ $todo->title }}">
            <input type="hidden" name="is_complete" value="0">
            <input class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 my-1 align-top bg-no-repeat bg-center bg-contain float-left {{ $todo->trashed() ? 'cursor-not-allowed' : 'cursor-pointer' }}" 
                type="checkbox" value="1" id="todo_complete_{{ $todo->id }}" name="is_complete" {{ $todo->is_complete ? 'checked' : ''}} 
                @if ($todo->trashed())
                    disabled
                @else
                    onChange='submit();'
                @endif
                >
        </div>
    </form>
    <div class="flex-1 text-lg text-gray-900">
        {{ $todo->title }}
        @if ($todo->trashed())
            <span class="text-gray-500 italic ml-2"> {{ __('Deleted') }}</span>
        @endif
    </div>
    
    @if (!$todo->trashed())
        <div class="flex-none flex gap-3 text-gray-500">
            <a href="{{ route('todo.edit', $todo) }}" class="inline-flex hover:text-teal-600">
                <span class="sr-only">{{__('Edit')}}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </a>

            <a href="{{ route('todo.delete', $todo) }}" class="inline-flex hover:text-red-400">
                <span class="sr-only">{{__('Delete')}}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </a>
        </div> 
    @endif
</div>