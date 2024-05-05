<div class="flex flex-col h-screen p-3 w-60 bg-current text-white overflow-auto">
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h2>SIMS Web App</h2>
            <button class="p-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-5 h-5 fill-current dark:text-gray-800">
                    <rect width="352" height="32" x="80" y="96"></rect>
                    <rect width="352" height="32" x="80" y="240"></rect>
                    <rect width="352" height="32" x="80" y="384"></rect>
                </svg>
            </button>
        </div>
        <div class="flex-1">
            <ul class="pt-2 pb-4 space-y-1 text-sm">
                <li class="rounded-sm menu {{ Route::is('product.*') ? 'menu-active' : '' }}">
                    <a href="{{ route('product.list') }}" class="flex items-center p-2 space-x-3 rounded-md">
                        <img src="{{ asset('images/Package.png') }}">
                        <span>Produk</span>
                    </a>
                </li>
                <li class="rounded-sm menu {{ Route::is('profile') ? 'menu-active' : '' }}">
                    <a href="{{ route('profile') }}" class="flex items-center p-2 space-x-3 rounded-md">
                        <img src="{{ asset('images/User.png') }}">
                        <span>Profil</span>
                    </a>
                </li>
                <li class="rounded-sm menu">
                    <a href="{{ route('do.logout') }}" class="flex items-center p-2 space-x-3 rounded-md">
                        <img src="{{ asset('images/SignOut.png') }}">
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>