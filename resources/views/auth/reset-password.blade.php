<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Redefinir senha</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex items-center justify-center h-screen">
        <div class="bg-white rounded shadow-md p-8">
            <h1 class="text-2xl font-bold mb-6">Redefinir senha</h1>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">E-mail:</label>
                    <input id="email" type="text" name="email" required value="{{ old('email') }}"
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-bold mb-2">Nova senha:</label>
                    <input id="password" type="password" name="password" required
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" 
                        class="block text-gray-700 font-bold mb-2">Confirmação de senha:</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                @if ($errors->any())
                    <div class="mb-4">
                        <ul class="text-red-500">
                            @foreach ($errors->all() as $error)
                                <li>* {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                        Redefinir senha
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
