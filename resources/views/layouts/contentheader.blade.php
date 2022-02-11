<div @if( session('success') || session('warning') || (!empty($errors) && $errors->any()) ) class="max-w-7xl mx-auto sm:px-6 lg:px-8" @endif>

    @if (session('success'))
    <div id="error-alert" class="flex p-4 -mb-12 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <div>
            {{ session('success') }}
        </div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-100 text-green-700 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-green-200 dark:text-green-600 dark:hover:bg-green-300" data-collapse-toggle="error-alert" aria-label="Close" onclick="closeAlert(event)">
            <span class="sr-only">Close</span>
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
        </button>
    </div>
    @endif

    @if (session('warning'))
    <div id="error-alert" class="flex p-4 -mb-12 bg-amber-100 border border-amber-400 text-amber-700 px-4 py-3 rounded relative" role="alert">
        <div>
            {{ session('warning') }}
        </div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-amber-100 text-amber-700 rounded-lg focus:ring-2 focus:ring-amber-400 p-1.5 hover:bg-amber-200 inline-flex h-8 w-8 dark:bg-amber-200 dark:text-amber-600 dark:hover:bg-amber-300" data-collapse-toggle="error-alert" aria-label="Close" onclick="closeAlert(event)">
            <span class="sr-only">Close</span>
            <svg class="fill-current h-6 w-6 text-amber-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
        </button>
    </div>
    @endif

    @if (!empty($errors) && $errors->any())
    <div id="error-alert" class="flex p-4 -mb-12 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-700 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-red-200 dark:text-red-600 dark:hover:bg-red-300" data-collapse-toggle="error-alert" aria-label="Close" onclick="closeAlert(event)">
            <span class="sr-only">Close</span>
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
        </button>
    </div>
    @endif
</div>

@push('scripts')
  <script>
    function closeAlert(event){
      let element = event.target;
      while(element.nodeName !== "BUTTON"){
        element = element.parentNode;
      }
      element.parentNode.parentNode.removeChild(element.parentNode);
    }
  </script>
@endpush
