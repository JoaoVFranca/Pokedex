
$(document).ready(function () {

    $('#PokeTable').ready(function(){
        $('#PokeTable').DataTable({
            'processing':true,
            'serverSide':true,
            'ajax': {
                'url': 'ProcessDataTable.php',
                'type': 'POST'
            },
            'columns': [
                {title:'@', 'orderable':false},
                {title:'Id'},
                {title: 'Name'},
                {title:'Types'}
            ],
            'order': [[1, 'asc']],
            "language": {
                "lengthMenu": "Showing _MENU_ pokemons",
                "info": "Showing pokemon _START_ to _END_. You have catch _TOTAL_ pokemons!",
                "infoEmpty": "No records available",
                "infoFiltered": "",
                "search":"Look for:",
                "zeroRecords":"No pokemons around ;(",
                "processing":"Processing...",
                "loadingRecords": "Carregando...",
                "paginate": {
                    "next":"Next",
                    "previous":"Prev"
                },
            }
        })
    })
    
    // Função de clique para o botão de visualização de cada pokemon
    $(document).on('click','.pokeInfoButton',function(){
        var pokeId = $(this).attr('value')
        $.ajax({
            url: 'database_methods/DBRequest.php',
            type: 'POST',
            data: {
                'id': pokeId
            },
            success: function(res) {
                res = $.parseJSON(res)
                var pokemonData = res[0]
                var pokemonImage = atob(pokemonData['imagePokemon'])
                
                Swal.fire({
                    imageUrl: pokemonImage,
                    imageHeight: 150,
                    position: 'center',
                    title: pokemonData['namePokemon'],
                    html: 'Pokemon ID ' + pokemonData['idRefAPIPokemon'] + setButtonsStyle(pokemonData['typesPokemon'],false),
                    showConfirmButton: true
                  })
            }
        })
    })

    // Função para setar o conteúdo do modal dos tipos de pokemon
    function setModalContent() {
        var AllTypes = 'water,fire,ice,ground,dark,ghost,fire,bug,fairy,flying,fighting,steel,rock,dark,psychic,normal,poison,dragon,electric,grass'
        $('#modal-content-label').html(setButtonsStyle(AllTypes,true))
    }
    setModalContent()

    // Função para teste se o pokemon ja está registrado no banco de dados
    function isPokemonRegistered(id, data) {
        var result = false
        data.forEach(pokemon => {
            if (pokemon["idRefAPIPokemon"] == id) {
                result = true
            }
        })
        return result
    }

    // Recebe todas as informações do pokemon na API e retorna apenas os parâmetros necessários
    function FilterPokemonDataFromAPI(data) {
        var CorrectParams = []
        CorrectParams.push(data["name"])
        CorrectParams.push(data["id"])
        CorrectParams.push(data["sprites"]["front_default"])

        var PokeTypeArray = []
        data["types"].map(function (type, i) {
            PokeTypeArray.push(type["type"]["name"])
        })

        CorrectParams.push(PokeTypeArray)
        return CorrectParams
    }

    // Requisição ajax enviando os dados já separados
    function AjaxInsertRequest(data, id) {
        $.ajax({
            url: 'database_methods/DBInsert.php',
            type: 'POST',
            data: {
                'name': data[0],
                'id': data[1],
                'image': data[2],
                'types': data[3]
            },
            success: function (res) {
                var lastPokemon = $.parseJSON(res)
                Swal.fire({
                    imageUrl: lastPokemon['image'],
                    imageHeight: 200,
                    position: 'center',
                    title: 'A wild ' + lastPokemon['name'] + ' appeared!',
                    showConfirmButton: false,
                    timer: 2000
                  })
                $('#PokeTable').DataTable().search(id).draw()
            }
        })
    }

    // Essa função adiciona o pokemón ao banco de dados caso ele não tenha sido brevemente registrado
    function addNotRegisteredPokemon(id, data) {
        if (data != null && isPokemonRegistered(id, data) == false) {
            $.ajax({
                url: 'APIRequest.php',
                type: 'POST',
                dataType: "json",
                data: {
                    'id': id
                },
                success: function (res) {
                    var PokemonData = JSON.parse(res)
                    PokemonData = FilterPokemonDataFromAPI(PokemonData)
                    AjaxInsertRequest(PokemonData, id)
                }
            })
        } else {
            $('#PokeTable').DataTable().search(id).draw()
        }
    }

    // Função ao clicar no botão aleatório
    $('#rand-button').click(function () {
        var PokeDataBase = null
        var new_id = Math.floor(Math.random() * 898) + 1;
        $('#visor').val(new_id)
        $.ajax({
            url: 'database_methods/DBRequest.php',
            type: "POST",
            success: function (res) {
                PokeDataBase = JSON.parse(res)
                addNotRegisteredPokemon(new_id, PokeDataBase)
            }
        })
    })

    // Função ao clicar no botão de check
    $('#add-pokemon').on('click', function () {
        var idValue = $('#visor').val()
        if (idValue >= 1 && idValue <= 898) {
            var PokeDataBase = null
            $.ajax({
                url: 'database_methods/DBRequest.php',
                type: "POST",
                success: function (res) {
                    PokeDataBase = JSON.parse(res)
                    addNotRegisteredPokemon(idValue, PokeDataBase)
                }
            })
        }
    })

    // Função de correção de dados do input
    $('#visor').on('change', function(){
        if ($(this).val() > 898)
            $(this).val(898)
        else if ($(this).val() < 1)
            $(this).val(1)
    }) 

    // Função para mostrar o modal
    $('#ShowTypesButton').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) 
        var modal = $(this)
    })

    // Função para setar os botões dos tipos de pokemon, apenas pra fins decorativos
    function setButtonsStyle(types, toFillModal) {
        types = types.split(',')

        var sameLineCounter = 0
        var tags = ""

        if (toFillModal == false) 
            tags += '<div class="d-flex justify-content-center">'
        types.map(function(type, index){
            if (toFillModal == true && sameLineCounter == 0) 
                tags += '<div class="d-flex justify-content-center">'
            sameLineCounter += 1
            switch (type) {
                case 'grass': tags += '<div style="border-radius: 3px; background-color: #00FF00;" class="mx-1 mt-1 w-25 text-white p-1 border border-dark">' + type + '</div>'
                    break
                case 'poison': tags += '<div style="border-radius: 3px; background-color: purple;" class="mx-1 mt-1 w-25 text-white p-1 border border-dark">' + type + '</div>'
                    break
                case 'water': tags += '<div style="border-radius: 3px; background-color: #1E90FF;" class="mx-1 mt-1 w-25 text-white p-1 border border-dark">' + type + '</div>'
                    break
                case 'fire': tags += '<div style="border-radius: 3px; background-color: #FF4500;" class="mx-1 mt-1 w-25 text-white p-1 border border-dark">' + type + '</div>'
                    break
                case 'flying': tags += '<div style="border-radius: 3px; background-color: #E0FFFF;" class="mx-1 mt-1 w-25 text-dark p-1 border border-dark">' + type + '</div>'
                    break
                case 'ground': tags += '<div style="border-radius: 3px; background-color: #D2691E;" class="mx-1 mt-1 w-25 text-white p-1 border border-dark">' + type + '</div>'
                    break
                case 'psychic': tags += '<div style="border-radius: 3px; background-color: #DA70D6;" class="mx-1 mt-1 w-25 text-white p-1 border border-dark">' + type + '</div>'
                    break
                case 'dark': tags += '<div style="border-radius: 3px; background-color: #000000;" class="mx-1 mt-1 w-25 text-white p-1 border border-dark">' + type + '</div>'
                    break
                case 'rock': tags += '<div style="border-radius: 3px; background-color: #8B4513;" class="mx-1 mt-1 w-25 text-white p-1 border border-dark">' + type + '</div>'
                    break
                case 'normal': tags += '<div style="border-radius: 3px; background-color: #DCDCDC;" class="mx-1 mt-1 w-25 text-white p-1 border border-dark">' + type + '</div>'
                    break
                case 'bug': tags += '<div style="border-radius: 3px; background-color: #228B22;" class="mx-1 w-25 mt-1 text-white p-1 border border-dark">' + type + '</div>'
                    break
                case 'fairy': tags += '<div style="border-radius: 3px; background-color: #FF00FF;" class="mx-1 w-25 mt-1 text-white p-1 border border-dark">' + type + '</div>'
                    break
                case 'steel': tags += '<div style="border-radius: 3px; background-color: #696969;" class="mx-1 w-25 mt-1 text-white p-1 border border-dark">' + type + '</div>'
                    break
                case 'ghost': tags += '<div style="border-radius: 3px; background-color: #4B0082;" class="mx-1 w-25 mt-1 text-white p-1 border border-dark">' + type + '</div>'
                    break
                case 'fighting': tags += '<div style="border-radius: 3px; background-color: #800000;" class="mx-1 mt-1 w-25 text-white p-1 border border-dark">' + type + '</div>'
                    break
                case 'ice': tags += '<div style="border-radius: 3px; background-color: #00FFFF;" class="mx-1 w-25 mt-1 text-white p-1 border border-dark">' + type + '</div>'
                    break
                case 'electric': tags += '<div style="border-radius: 3px; background-color: #FFFF00;" class="mx-1 mt-1 w-25 text-dark p-1 border border-dark">' + type + '</div>'
                    break
                case 'dragon': tags += '<div style="border-radius: 3px; background-color: #2F4F4F;" class="mx-1 mt-1 w-25 text-white p-1 border border-dark">' + type + '</div>'
                    break
            }
            if (toFillModal == true && sameLineCounter == 4) {
                sameLineCounter = 0
                tags += '</div>'
            }            
        })
        if (toFillModal == false)
            tags += '</div>'

        return tags
    }
})
