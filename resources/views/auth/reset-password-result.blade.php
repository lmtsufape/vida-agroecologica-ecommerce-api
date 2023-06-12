<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Troca de senha</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f7fafc;
        }
    </style>
</head>
<body>
    <div class="flex justify-center items-center h-screen">
        <div class="max-w-md w-full p-6 bg-white rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4">Troca de senha</h2>
            @if($sucesso)
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Sua senha foi redefinida com sucesso!</strong>
                </div>
            @else
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Falha ao trocar a senha!</strong>
                    <p class="text-sm">Por favor, solicite novamente a recuperação de senha no aplicativo.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
