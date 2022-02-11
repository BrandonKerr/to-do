<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 border-b border-indigo-200 bg-indigo-50 flex">
        <h3 class="flex-1 text-lg leading-6 font-large text-indigo-900">{{$checklist->title}}</h3>

        <div class="flex-none flex gap-3">
            <a href="{{ route('list.edit', $checklist) }}" class="inline-flex hover:text-teal-600">
                <span class="sr-only">{{__('Edit')}}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </a>

            <x-checklist.delete ::class="inline-flex" :checklist="$checklist"/>
        </div> 
    </div>
    
    <ul>
        @foreach ($todos as $todo)
        <li class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} px-4 py-5">
            {{$todo}}
        </li>
        @endforeach
    </ul>
</div>
