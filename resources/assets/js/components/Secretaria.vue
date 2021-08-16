<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Secretarías
                        <button type="button" @click="abrirModal('secretaria', 'registrar')" class="btn btn-secondary" >
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="sigla">Sigla</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarSecretaria(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarSecretaria(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Sigla</th>
                                    <th>Responsable</th>
                                    <th>Cargo</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="secretaria in arraySecretaria" :key="secretaria.id" >
                                    <td>
                                        <button type="button" @click="abrirModal('secretaria','actualizar',secretaria)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil" ></i>
                                        </button> &nbsp;
                                        <template v-if="secretaria.condicion">
                                            <button type="button" class="btn btn-danger  btn-sm" @click="desactivarSecretaria(secretaria.id)">
                                            <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                          <template v-else>
                                            <button type="button" class="btn btn-info  btn-sm" @click="activarSecretaria(secretaria.id)">
                                            <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="secretaria.nombre"></td>
                                    <td v-text="secretaria.sigla"></td>
                                    <td v-text="secretaria.responsable"></td>
                                    <td v-text="secretaria.cargo"></td>
                                     
                                    <td>
                                        <div v-if="secretaria.condicion">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                         <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                       
                                    </td>
                                </tr>                              
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)" >Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de Secretaría">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Sigla</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="sigla" class="form-control" placeholder="Ingrese Sigla">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Responsable</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="responsable" class="form-control" placeholder="Ingrese Responsable">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Cargo</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="cargo" class="form-control" placeholder="Ingrese Cargo">
                                    </div>
                                </div>
                                <div v-show="errorSecretaria" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjSecretaria" :key="error" v-text="error"></div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarSecretaria()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarSecretaria()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            <!-- Inicio del modal Eliminar -->
            
            <!-- Fin del modal Eliminar -->
        </main>
</template>

<script>
    export default {
        data(){
            return{
                secretaria_id: 0,
                nombre : '',
                sigla : '',
                responsable : '',
                cargo : '',
                arraySecretaria : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorSecretaria : 0,
                errorMostrarMsjSecretaria : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : ''
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //calcula los elementos de la paginación
        pagesNumber: function() {
            if(!this.pagination.to){
                return [];
            }
            var from = this.pagination.current_page - this.offset;
            if(from < 1) {
                from = 1;
            }
            var to = from + (this.offset * 2);
            if(to >= this.pagination.last_page){
                to = this.pagination.last_page;
            }
            var pagesArray = [];
            while(from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
        },
    methods : {
        listarSecretaria (page, buscar, criterio){
            let me=this;
            var url='/secretarias?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
            axios.get(url).then(function (response) {
                var respuesta=response.data;
                    me.arraySecretaria = respuesta.secretarias.data;
                    me.pagination=respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);    
            });
            },

            cambiarPagina(page,buscar,criterio){
              let me = this;
              //Actualiza la pagina actual
              me.pagination.current_page = page;  
              //Envia la peticion para vizualizar la data de esa pagina
              me.listarSecretaria(page,buscar,criterio);
            },

        registrarSecretaria (){
            if (this.validarSecretaria()){
                return;
            }

            let me = this;
            axios.post('/secretarias/registrar', {
                'nombre': this.nombre,
                'sigla': this.sigla,
                'responsable': this.responsable,
                'cargo': this.cargo,
            }).then(function (response){
                    me.cerrarModal();
                    me.listarSecretaria(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                
            });
        },
        actualizarSecretaria(){
             if (this.validarSecretaria()){
                return;
            }

            let me = this;
            axios.put('/secretarias/actualizar', {
                'nombre': this.nombre,
                'sigla': this.sigla,
                'responsable': this.responsable,
                'cargo': this.cargo,
                'id':this.secretaria_id
            }).then(function (response){
                    me.cerrarModal();
                    me.listarSecretaria(1, '','nombre');
                }).catch(function (error) {
                    console.log(error);
                
            });
        },
        desactivarSecretaria(id){
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Esta seguro en desactivar esta Secretaría?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar!',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {

                    let me = this;
                    axios.put('/secretarias/desactivar', {
                        'id': id
                    }).then(function (response){           
                            me.listarSecretaria(1, '','nombre');
                             swalWithBootstrapButtons.fire(                   
                            'Desactivado!',
                            'El registro ha sido desactivado con éxito.',
                            'success'
                    )
                        }).catch(function (error) {
                            console.log(error);
                        
                    });
                   
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                   
                }
                })
        },

        activarSecretaria(id){
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Esta seguro de activar esta Secretaría?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar!',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {

                    let me = this;
                    axios.put('/secretarias/activar', {
                        'id': id
                    }).then(function (response){           
                            me.listarSecretaria(1, '','nombre');
                             swalWithBootstrapButtons.fire(                   
                            'Activado!',
                            'El registro ha sido activado con éxito.',
                            'success'
                    )
                        }).catch(function (error) {
                            console.log(error);
                        
                    });
                   
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                   
                }
                })
        },

        validarSecretaria(){
            this.errorSecretaria=0;
            this.errorMostrarMsjSecretaria =[];
            
            if (!this.nombre) this.errorMostrarMsjSecretaria.push("El nombre de la secretaría, no puede estar vacio. ");
            if (!this.sigla) this.errorMostrarMsjSecretaria.push("El campo sigla, no puede estar vacio. ");
            if (this.errorMostrarMsjSecretaria.length) this.errorSecretaria =1;
            return this.errorSecretaria;
        },

        cerrarModal(){
            this.modal=0;
            this.nombre = '';
            this.sigla = '';
            this.responsable = '';
            this.cargo = '';
        },
        abrirModal(modelo, accion, data = []){
            switch (modelo) {
                case "secretaria":
                {
                    switch (accion) {
                        case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Secretaría',
                                this.nombre = '';
                                this.sigla = '';
                                this.responsable = '';
                                this.cargo = '';
                                this.tipoAccion = 1;
                                break;
                            }
                        case 'actualizar':
                    
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Registro';
                                this.tipoAccion=2;
                                this.secretaria_id=data['id'];
                                this.nombre = data['nombre'];
                                this.sigla = data['sigla'];
                                this.responsable = data['responsable'];
                                this.cargo = data['cargo'];

                                break;
                            }                        
                    }
                }
            }
        }
        },
    

        mounted() {
            this.listarSecretaria(1,this.buscar,this.criterio);
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }

    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }

</style>