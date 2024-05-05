<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/Handbag.png') }}">
    
    @vite('resources/css/app.css')

    <title>Login</title>

    <style>
        input::-ms-reveal,
        input::-ms-clear {
            display: none;
        }
    </style>
</head>
<body>
    <div class="flex w-screen h-screen">
        <div class="flex-auto w-auto grid justify-items-center items-center p-10">
            <div>
                <div class="flex items-center justify-center mb-10 font-semibold gap-2">
                    <img src="{{ asset('images/Handbag.png') }}" width="25px" height="25px">
                    <div class="">SIMS Web App</div>
                </div>

                @error('credentials')
                    <x-alert type="error" :message="$message"/>
                @enderror

                <div class="font-semibold text-2xl">
                    Masuk atau buat akun untuk memulai
                </div>

                <form spellcheck="false" action="{{ route('do.login') }}" method="POST">
                    @csrf

                    <div class="mt-5">
                        <div class="relative mt-2 flex">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20" class="fill-gray-500">
                                    <path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480v58q0 59-40.5 100.5T740-280q-35 0-66-15t-52-43q-29 29-65.5 43.5T480-280q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480v58q0 26 17 44t43 18q26 0 43-18t17-44v-58q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93h200v80H480Zm0-280q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Z"/>
                                </svg>
                            </div>
                            <input required type="text" name="email" id="email" class="@error('email') ring-red-400 @else ring-gray-300 @enderror block w-full rounded-md border-0 py-1.5 pl-10 pr-2 text-gray-900 ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-current sm:text-sm sm:leading-6" placeholder="Masukkan email Anda" value="{{ old('email', '') }}">
                        </div>
                        @error('email')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-5">
                        <div class="relative mt-2">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20" class="fill-gray-500">
                                    <path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm0-80h480v-400H240v400Zm240-120q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80ZM240-160v-400 400Z"/>
                                </svg>
                            </div>
                            <input required type="password" name="password" id="password" inputmode="text" class="@error('password') ring-red-400 @else ring-gray-300 @enderror block w-full rounded-md border-0 py-1.5 pl-10 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-current sm:text-sm sm:leading-6" placeholder="Masukkan password Anda">
                            <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-2" id="visibility-toggle">
                                {{-- Dynamic content --}}
                            </button>
                        </div>
                        @error('password')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="bg-current px-3 py-1 w-full text-white mt-10">
                        Masuk
                    </button>
                </form>
            </div>
        </div>
        <img src="{{ asset('images/Frame-98699.png') }}" class="max-w-full max-h-full hidden md:block">
    </div>

    <script type="text/javascript">
        let isVisible = false;
        const visibilityToggle = document.querySelector('#visibility-toggle');
        const passwordInput = document.querySelector("#password");

        const iconInvisible =   `<svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20" class="fill-gray-500">
                                    <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/>
                                </svg>`;

        const iconVisible =     `<svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20" class="fill-gray-500">
                                    <path d="m644-428-58-58q9-47-27-88t-93-32l-58-58q17-8 34.5-12t37.5-4q75 0 127.5 52.5T660-500q0 20-4 37.5T644-428Zm128 126-58-56q38-29 67.5-63.5T832-500q-50-101-143.5-160.5T480-720q-29 0-57 4t-55 12l-62-62q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302Zm20 246L624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM222-624q-29 26-53 57t-41 67q50 101 143.5 160.5T480-280q20 0 39-2.5t39-5.5l-36-38q-11 3-21 4.5t-21 1.5q-75 0-127.5-52.5T300-500q0-11 1.5-21t4.5-21l-84-82Zm319 93Zm-151 75Z"/>
                                </svg>`;

        function changeVisibility(state) {
            isVisible = state;
            visibilityToggle.innerHTML = isVisible ? iconVisible : iconInvisible;
            passwordInput.type = isVisible ? 'text' : 'password';
        }

        document.addEventListener('DOMContentLoaded', function() {
            changeVisibility(false);
        });

        visibilityToggle.addEventListener('click', function() {
            changeVisibility(!isVisible);
        });
    </script>
</body>
</html>