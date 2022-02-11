<div>
    <div id="new-todo-{{ $checklist->id }}" class="text-lg leading-6 font-large text-indigo-900">{{ __('todo.create')}}</div>
    <form method="POST" action="{{ route('todo.store', $checklist) }}">
        @method('PUT')
        @csrf
        <div class="flex gap-3 items-center">
            <input id="title" aria-labelledby="new-todo-{{ $checklist->id }}" class="flex-auto rounded-md placeholder:italic placeholder:text-slate-400" placeholder="{{ __('todo.title')}}" type="text" name="title" required />
            <button class="flex-initial rounded-md border border-transparent shadow-sm px-4 py-2 border-indigo-600 text-base font-medium text-indigo-600 hover:bg-indigo-100 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                {{ __('Save') }}
            </button>
        </div>
    </form>
</div>