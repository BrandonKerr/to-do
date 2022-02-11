<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $all ?  __('checklist.all') :  __('checklist.completed')}}
        </h2>
    </x-slot>

    <div class="my-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @forelse ($checklists as $checklist)
                        <div class="my-2">
                            <x-checklist.show :checklist="$checklist" :all="$all"/>
                        </div>
                    @empty
                        <p class="font-semibold text-xl text-gray-800">You haven't completed any To-Do Lists yet!</p> 
                        <span class="text-xl text-gray-800">Go back to your <a class="font-medium text-indigo-600 hover:text-indigo-500" href="{{route('dashboard')}}">dashboard</a> and get to it</a> :)</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
