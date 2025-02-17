@section('title')
<h1>PAGOS</h1>

@endsection
@section('content')
    <style>



        video {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;
            outline: none;
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        html {
            overflow-y: scroll;
        }



        ::selection {
            background: #5f74a0;
            color: #fff;
        }

        ::-moz-selection {
            background: #5f74a0;
            color: #fff;
        }

        ::-webkit-selection {
            background: #5f74a0;
            color: #fff;
        }

        br {
            display: block;
            line-height: 1.6em;
        }

        article,
        aside,
        details,
        figcaption,
        figure,
        footer,
        header,
        hgroup,
        menu,
        nav,
        section {
            display: block;
        }

        ol,
        ul {
            list-style: none;
        }

        input,
        textarea {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            outline: none;
        }

        blockquote,
        q {
            quotes: none;
        }

        blockquote:before,
        blockquote:after,
        q:before,
        q:after {
            content: '';
            content: none;
        }

        strong,
        b {
            font-weight: bold;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        img {
            border: 0;
            max-width: 100%;
        }

        h1 {
            font-weight: bold;
            font-size: 3.6em;
            line-height: 1.7em;
            margin-bottom: 10px;
            text-align: center;
        }


        /** page structure **/
        #wrapper {
            display: block;
            width: 850px;
            background: #fff;
            margin: 0 auto;
            padding: 10px 17px;
            -webkit-box-shadow: 2px 2px 3px -1px rgba(0, 0, 0, 0.35);
        }

        #keywords {
            margin: 0 auto;
            font-size: 1.2em;
            margin-bottom: 15px;
        }


        #keywords thead {
            cursor: pointer;
            background: #c9dff0;
        }

        #keywords thead tr th {
            font-weight: bold;
            padding: 12px 30px;
            padding-left: 42px;
        }

        #keywords thead tr th span {
            padding-right: 20px;
            background-repeat: no-repeat;
            background-position: 100% 100%;
        }

        #keywords thead tr th.headerSortUp,
        #keywords thead tr th.headerSortDown {
            background: #acc8dd;
        }

        #keywords thead tr th.headerSortUp span {
            background-image: url('https://i.imgur.com/SP99ZPJ.png');
        }

        #keywords thead tr th.headerSortDown span {
            background-image: url('https://i.imgur.com/RkA9MBo.png');
        }


        #keywords tbody tr {
            color: #555;
        }

        #keywords tbody tr td {
            text-align: center;
            padding: 15px 10px;
        }

        #keywords tbody tr td.lalign {
            text-align: left;
        }
    </style>


<div id="wrapper">

    <table id="keywords" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th></th>
                    <th>Monto</th>
                    <th>Descripción</th>
                    <th>Comprobante</th>
            </tr>
        </thead>
        <tbody>

            @forelse ($aportes as $aportes)
            @if ($aportes->estudiante_id == auth()->user()->id)
                <tr>
                    <td>{{ $aportes->datosEstudiante }}</td>
                    <td>{{ $aportes->monto }}</td>
                    <td>{{ $aportes->DescripcionDelPago }}</td>
                    <td> <a href="{{route('factura', $aportes->id)}}">Ver Factura</a></td>
                </tr>
            @endif
        @empty
            <tr>
                <td></td>
                <td>Aún no se ha realizado ningún pago </td>
                <td></td>
                <td></td>
            </tr>
        @endforelse


        </tbody>
    </table>
    <div class="card-footer py-4">
        <ul class="pagination justify-content-end mb-0">
            {{ auth()->user()->notifications()->paginate(4)}}
        </ul>
    </div>
</div>




    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
@endsection


@include('FundacionPlantillaUsu.index')
