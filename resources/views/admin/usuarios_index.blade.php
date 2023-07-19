@extends('layouts.app')

@section('content')
    <div class="row my-5">
        <div class="col-md-2"></div>
        <div class="col-sm-8 shadow-lg p-3 bg-white rounded" style="min-height: 28rem">
            <div class="row mb-4 borda-bottom">
                <div class="col-md-9">
                    <h3 class="text-center">Usuários</h3>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary w-100 pb-1" data-toggle="modal" data-target="#cadastroModal">
                        Cadastrar Usuário
                    </button>
                </div>
            </div>
            @if(session('sucesso'))
                <div class="row">
                    <div class="col-md-12" style="margin-top: 5px;">
                        <div class="alert alert-success" role="alert">
                            <p>{{session('sucesso')}}</p>
                        </div>
                    </div>
                </div>
            @endif
            <table class="table table-hover">
                <thead>
                <tr>
                    <th class="text-center" scope="col">Nome</th>
                    <th class="text-center" scope="col">E-Mail</th>
                    <th class="text-center" scope="col">CPF</th>
                    <th class="text-center" scope="col">Tipo</th>
                    <th class="w-25 text-center" scope="col">Ações</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($users as $usuario)
                        <tr>
                            <td class="text-center">{{$usuario->name}}</td>
                            <td class="text-center">{{$usuario->email}}</td>
                            <td class="text-center">{{$usuario->cpf}}</td>
                            <td class="text-center">{{$usuario->tipoUsuario->name}}</td>
                            <td class="text-center">
                                <button class="btn btn-group" type="button" data-toggle="modal" data-target="#editModal_{{$usuario->id}}"><i class="fa-solid fa-pen-to-square"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="modal fade" id="cadastroModal" tabindex="-1" role="dialog" aria-labelledby="cadastroModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cadastroModalLabel">Cadastrar Usuário</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="{{route('usuario.store')}}">
                            @csrf
                            <div class="modal-body">
                                @csrf
                                <h6 class="sectionTitle">Informações Gerais</h6>
                                <div class="row justify-content-center mt-2">
                                    <div class="col-sm-4">
                                        <label for="name">Nome:</label>
                                        <input class="form-control @error('name') is-invalid @enderror name" id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name"
                                               autofocus>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="cpf">CPF:</label>
                                        <input class="form-control @error('cpf') is-invalid @enderror cpf" id="cpf" type="text" name="cpf" value="{{ old('cpf') }}" required autocomplete="cpf"
                                               autofocus>
                                        @error('cpf')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="tipo_usuario_id">Tipo do Usuário:</label>
                                        <select class="form-control" name="tipo_usuario_id">
                                            <option value="1">
                                                Administrador
                                            </option>
                                            <option value="2">
                                                Presidente
                                            </option>
                                            <option value="3">
                                                Agricultor
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <h6 class="sectionTitle">Contato</h6>

                                <div class="row justify-content-center mt-2">
                                    <div class="col-sm-6">
                                        <label for="email">E-mail:</label>
                                        <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                                               autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="telefone">Telefone:</label>
                                        <input class="form-control @error('telefone') is-invalid @enderror telefone" id="telefone" type="text" name="telefone" value="{{ old('telefone') }}" required
                                               autocomplete="telefone"
                                               autofocus>
                                        @error('telefone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <h6 class="sectionTitle">Endereço</h6>

                                <div class="row justify-content-center mt-2">
                                    <div class="col-sm-6">
                                        <label for="cep">CEP:</label>
                                        <input class="form-control @error('cep') is-invalid @enderror cep" id="cep" type="text" name="cep" value="{{ old('cep') }}" required autocomplete="cep" autofocus>
                                        @error('cep')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="bairro">Bairro:</label>
                                        <input class="form-control @error('bairro') is-invalid @enderror" id="bairro" type="text" name="bairro" value="{{ old('bairro') }}" required
                                               autocomplete="bairro"
                                               autofocus>
                                        @error('bairro')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row justify-content-center mt-2">
                                    <div class="col-sm-6">
                                        <label for="rua">Rua:</label>
                                        <input class="form-control @error('rua') is-invalid @enderror" id="rua" type="text" name="rua" value="{{ old('rua') }}" required autocomplete="rua"
                                               autofocus>
                                        @error('rua')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="numero">Numero:</label>
                                        <input class="form-control @error('numero') is-invalid @enderror" id="numero" type="number" name="numero" value="{{ old('numero') }}" required
                                               autocomplete="numero"
                                               autofocus>
                                        @error('numero')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <input id="password" type="hidden" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" value="password">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-success">Cadastrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @foreach($users as $usuario)
                <div class="modal fade" id="editModal_{{$usuario->id}}" tabindex="-1" role="dialog" aria-labelledby="cadastroModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="cadastroModalLabel">Cadastrar Usuário</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="{{route('usuario.update')}}">
                                @csrf
                                <div class="modal-body">
                                    @csrf
                                    <input type="hidden" name="usuario_id" value="{{$usuario->id}}">
                                    <h6 class="sectionTitle">Informações Gerais</h6>
                                    <div class="row justify-content-center mt-2">
                                        <div class="col-sm-4">
                                            <label for="name">Nome:</label>
                                            <input class="form-control @error('nome') is-invalid @enderror name" id="nome" type="text" name="name" value="{{ $usuario->name }}" required autocomplete="nome" autofocus>
                                            @error('nome')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-sm-4">
                                            <label for="cpf">CPF:</label>
                                            <input class="form-control @error('cpf') is-invalid @enderror cpf" id="cpf" type="text" name="cpf" value="{{ $usuario->cpf }}" required autocomplete="cpf"
                                                   autofocus>
                                            @error('cpf')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="tipo_usuario_id">Tipo do Usuário:</label>
                                            <select class="form-control" name="tipo_usuario_id">
                                                <option @if($usuario->tipo_usuario_id == 1) selected @endif value="1">
                                                    Administrador
                                                </option>
                                                <option @if($usuario->tipo_usuario_id == 2) selected @endif value="2">
                                                    Presidente
                                                </option>
                                                <option @if($usuario->tipo_usuario_id == 3) selected @endif value="3">
                                                    Agricultor
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <h6 class="sectionTitle">Contato</h6>

                                    <div class="row justify-content-center mt-2">
                                        <div class="col-sm-6">
                                            <label for="email">E-mail:</label>
                                            <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ $usuario->contato->email }}" required autocomplete="email"
                                                   autofocus>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-sm-6">
                                            <label for="telefone">Telefone:</label>
                                            <input class="form-control @error('telefone') is-invalid @enderror telefone" id="telefone" type="text" name="telefone" value="{{ $usuario->contato->telefone }}" required
                                                   autocomplete="telefone"
                                                   autofocus>
                                            @error('telefone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <h6 class="sectionTitle">Endereço</h6>

                                    <div class="row justify-content-center mt-2">
                                        <div class="col-sm-6">
                                            <label for="cep">CEP:</label>
                                            <input class="form-control @error('cep') is-invalid @enderror cep" id="cep" type="text" name="cep" value="{{ $usuario->endereco->cep }}" required autocomplete="cep"
                                                   autofocus>
                                            @error('cep')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-sm-6">
                                            <label for="bairro">Bairro:</label>
                                            <input class="form-control @error('bairro') is-invalid @enderror" id="bairro" type="text" name="bairro" value="{{ $usuario->endereco->bairro }}" required
                                                   autocomplete="bairro"
                                                   autofocus>
                                            @error('bairro')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row justify-content-center mt-2">
                                        <div class="col-sm-6">
                                            <label for="rua">Rua:</label>
                                            <input class="form-control @error('rua') is-invalid @enderror" id="rua" type="text" name="rua" value="{{ $usuario->endereco->rua }}" required autocomplete="rua"
                                                   autofocus>
                                            @error('rua')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-sm-6">
                                            <label for="numero">Numero:</label>
                                            <input class="form-control @error('numero') is-invalid @enderror" id="numero" type="number" name="numero" value="{{ $usuario->endereco->numero }}" required
                                                   autocomplete="numero"
                                                   autofocus>
                                            @error('numero')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                    <button type="submit" class="btn btn-success">Editar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-md-2"></div>
    </div>

    <script>
        $(document).ready(function($) {
            $('.cpf').mask('000.000.000-00');
            $('.cep').mask('00000-000');
            let SPMaskBehavior = function(val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                spOptions = {
                    onKeyPress: function(val, e, field, options) {
                        field.mask(SPMaskBehavior.apply({}, arguments), options);
                    }
                };
            $('.telefone').mask(SPMaskBehavior, spOptions);
            $(".name").mask("#", {
                maxlength: true,
                translation: {
                    '#': { pattern: /^[A-Za-záâãéêíóôõúçÁÂÃÉÊÍÓÔÕÚÇ\s]+$/, recursive: true }
                }
            });
        });
    </script>

    <script>
        $('.table').DataTable({
            searching: true,
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "info": "Exibindo página _PAGE_ de _PAGES_",
                "search": "Pesquisar",
                "infoEmpty": "",
                "zeroRecords": "Nenhuma Solicitacao Criada.",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Próximo"
                }
            },
            "order": [0, 1, 2, 3],
            "columnDefs": [{
                "targets": [4],
                "orderable": false
            }]
        });
    </script>
@endsection
