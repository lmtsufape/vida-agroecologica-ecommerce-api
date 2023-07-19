@extends('layouts.app')

@section('content')
    <div class="row my-5">
        <div class="col-md-2"></div>
        <div class="col-sm-8 shadow-lg p-3 bg-white rounded" style="min-height: 28rem">
            <div class="row mb-4 borda-bottom">
                <div class="col-md-9">
                    <h3 class="text-center">Agricultores</h3>
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
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                        @foreach($errors->all() as $erro)
                                <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">Nome</th>
                        <th class="text-center" scope="col">E-Mail</th>
                        <th class="text-center" scope="col">CPF</th>
                        <th class="text-center" scope="col">OCS Vinculada</th>
                        <th class="w-25 text-center" scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($agricultores as $agricultor)
                    <tr>
                        <td class="text-center">{{$agricultor->nome}}</td>
                        <td class="text-center">{{$agricultor->email}}</td>
                        <td class="text-center">{{$agricultor->cpf}}</td>
                        <td class="text-center">{{$agricultor->organizacao->nome ?? "Nenhuma OCS vinculada"}}</td>
                        <td class="text-center">
                            <button class="btn btn-group" type="button" data-toggle="modal" data-target="#vinculaOcsModal_{{$agricultor->id}}"><i class="fa-solid fa-pen-to-square"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            @foreach($agricultores as $agricultor)
                <div class="modal fade" id="vinculaOcsModal_{{$agricultor->id}}" tabindex="-1" role="dialog" aria-labelledby="vinculaOcsLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="cadastroModalLabel">Vincular OCS</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="{{route('vincula.agricultor')}}">
                                @method('PUT')
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" name="agricultor_id" value="{{$agricultor->id}}">
                                    <h6 class="sectionTitle">Informações Gerais</h6>
                                    <div class="row justify-content-left mt-2">
                                        <div class="col-sm-8">
                                            <label for="name">Selecione a OCS:</label>
                                            <select class="form-control" id="organizacao_id" name="organizacao_id">
                                                <option selected disabled style="font-weight: bolder">
                                                    Selecione uma OCS
                                                </option>
                                                @foreach($organizacoes as $organizacao)
                                                    <option value="{{$organizacao->id}}">
                                                        {{$organizacao->nome}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                    <button type="submit" class="btn btn-success">Vincular</button>
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
        $('.table').DataTable({
            searching: true,
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "info": "Exibindo página _PAGE_ de _PAGES_",
                "search": "Pesquisar",
                "infoEmpty": "",
                "zeroRecords": "Nenhuma dado a ser exibido.",
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
