@extends('layout.app', ["current" => "produtos" ])

@section('body')
<div class="card border">
    <div class="card-body">
        <h5 class="card-title">Cadastro de Produtos</h5>


        <table class="table table-ordered table-hover" id="tabelaProdutos">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Preço</th>
                    <th>Departamento</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
             
            </tbody>
        </table>
       
    </div>
    <div class="card-footer">
        <button class="btn btn-sm btn-primary" role="button" onclick="novoProduto()">Novo Produto</a>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="dlgProdutos">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formProduto" class="form-horizontal">
                <div class="modal-header">
                    <h5 class="modal-title">Novo Produto</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id">
                    <div class="form-group">
                        <label for="nomeProduto" class="control-label">Nome do Produto</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="nomeProduto" placeholder="Nome do Produto">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="precoProduto" class="control-label">Preço</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="precoProduto" placeholder="Preço do Produto">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="quantidadeProduto" class="control-label">Quantidade</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="quantidadeProduto" placeholder="Quantidade do Produto">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="departamentoProduto" class="control-label">Departamento do Produto</label>
                        <div class="input-group">
                            <select class="form-control" id="categoriaProduto">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                   <button class="btn btn-primary" type="submit">Salvar</button> 
                   <button class="btn btn-secondary" type="cancel" data-dismiss="modal">Cancelar</button> 
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script type="text/javascript">
   
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });
 
    function novoProduto(){
        $('#nomeProduto').val('');
        $('#precoProduto').val('');
        $('#quantidadeProduto').val('');
        $('#dlgProdutos').modal('show');
    }

    function carregarCategorias(){
        $.getJSON('/api/categorias', function(data){
            
            for( i=0;i<data.length;i++){
              opcao = '<option value ="'+ data[i].id +'">' +data[i].nome + '</option>';
                $('#categoriaProduto').append(opcao);
            }
        });
    }
    function carregarProdutos(){
        $.getJSON('/api/produtos' , function(data){
            for(i=0;i<data.length;i++){
                linha = montarlinha(data[i]);
                $('#tabelaProdutos>tbody').append(linha);
            }
        });
    }
   
    function montarlinha(p) {
        var linha = "<tr>"+
        "<td>" + p.id  + "</td>" +
        "<td>" + p.nome  + "</td>" +
        "<td>" + p.estoque  + "</td>" +
        "<td>" + p.preco  + "</td>" +
        "<td>" + p.categoria_id  + "</td>" +
        "<td>"+
            '<button class="btn btn-primary btn-sm" onclick="editar('+ p.id +')"> Editar </button> '+
            '<button class="btn btn-danger btn-sm"  onclick="apagar('+ p.id +')"> Apagar </button> '+
        "</td>"+
        "</tr>";
        return linha;
    }

    function criarProduto(){
        prod = {
            nome: $('#nomeProduto').val(),
            preco: $('#precoProduto').val(),
            estoque: $('#quantidadeProduto').val(),
            categoria_id: $('#categoriaProduto').val()
        };
        $.post("/api/produtos", prod, function(data){
            produto =JSON.parse(data);
            linha = montarlinha(produto);
                $('#tabelaProdutos>tbody').append(linha);
        })
    }

    function apagar(id){
        $.ajax({
            type: "DELETE",
            url: "/api/produtos/" + id,
            context: this,
            success: function (){
                console.log('apagou ok');
                linhas = $('#tabelaProdutos>tbody>tr');
                e = linhas.filter( function (i, elemento){ return elemento.cells[0].textContent == id;
                });
                if(e)
                    e.remove();
            },
            error: function(error){
                console.log(error);
            }
        });
    }
    function editar(id){
        $.getJSON('/api/produtos/'+ id , function(data){
           $('#id').val(data.id);
           $('#nomeProduto').val(data.nome);
           $('#precoProduto').val(data.preco);
           $('#quantidadeProduto').val(data.estoque);
           $('#categoriaProduto').val(data.categoria_id);
           $('#dlgProdutos').modal('show');
        });
    }
    
   function salvarProduto(){
    prod = {
            id: $('#id').val(),
            nome: $('#nomeProduto').val(),
            preco: $('#precoProduto').val(),
            estoque: $('#quantidadeProduto').val(),
            categoria_id: $('#categoriaProduto').val()
        };
    $.ajax({
            type: "PUT",
            url: "/api/produtos/" + prod.id,
            context: this,
            data: prod,
            success: function (data){
                prod = JSON.parse(data);
                linhas = $('#tabelaProdutos>tbody>tr');
                e = linhas.filter(function (i, e){
                    return(e.cells[0].textContent == prod.id);
                });
                if (e){
                    e[0].cells[0].textContent = prod.id;
                    e[0].cells[1].textContent = prod.nome;
                    e[0].cells[2].textContent = prod.estoque;
                    e[0].cells[3].textContent = prod.preco;
                    e[0].cells[4].textContent = prod.categoria_id;
                }
                console.log('salvou ok');
                
            },
            error: function(error){
                console.log(error);
            }
        });
   }

    $("#formProduto").submit( function(event){
        event.preventDefault();
        if($('#id').val() != "")
            salvarProduto();            
        else
            criarProduto();

        $('#dlgProdutos').modal('hide');
    });
    $(function(){
        carregarCategorias();
        carregarProdutos();
    });
</script>
@endsection