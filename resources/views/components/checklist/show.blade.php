<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 border-b border-indigo-200 bg-indigo-50 flex">
        <h3 class="flex-1 text-lg leading-6 font-large text-indigo-900">{{$checklist->title}}</h3>

        <div class="flex-none flex gap-3 text-indigo-500"">
            <a href="{{ route('list.edit', $checklist) }}" class="inline-flex hover:text-teal-600">
                <span class="sr-only">{{__('Edit')}}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </a>

            <a href="{{ route('list.delete', $checklist) }}" class="inline-flex hover:text-red-400">
                <span class="sr-only">{{__('Delete')}}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </a>
        </div> 
    </div>
    
    <ul>
        @forelse ($todos as $todo)
            <li class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} px-8 py-5">
                <x-todo.show :todo="$todo"/>
            </li>
        @empty
            <div class="text-slate-500 tracking-tight text-lg p-6 -mb-4">
                {{ __('checklist.empty_notice') }}
            </div>
        @endforelse

        <div class="p-6 pt-4 border-t border-slate-200">
            <x-todo.create :checklist="$checklist"/>
        </div>
    </ul>
</div>
