<x-app-layout>
  <h1><font size=10> La vue ABOUT</font></h1>
  @if (Route::has('login'))
  <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
      @auth
         <!-- <a href="{{ url('redirects') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>-->
      @else
         <!-- <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">&#128273 CONNEXION</a>-->

          @if (Route::has('register'))
              <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">ENREGISTREMENT</a>
          @endif
      @endauth
  </div>
@endif
</x-app-layout>
