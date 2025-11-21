@extends('FundacionPlantillaUsu.index')

@section('content')
    <div id="wrapper">
        <h1>NOTIFICACIONES</h1>

        <table id="keywords" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th>Nro</th>
                    <th scope="col">Descripcion</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>



            </tbody>
        </table>
        <div class="card-footer py-4">
            <ul class="pagination justify-content-end mb-0">

                {{ auth()->user()->notifications()->paginate(4)}}
            </ul>
        </div>
    </div>
@endsection
