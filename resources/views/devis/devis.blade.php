@extends('baseAdmin')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Designation</th>
                        <th>Unite</th>
                        <th>Qte</th>
                        <th>PU</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($travaux as $t)
                        <tr>
                            <td>{{ $t->code }}</td>
                            <td>{{ $t->travaux }}</td>
                            <td>{{ $t->unite }}</td>
                            <td>{{ $t->qte }}</td>
                            <td>{{ number_format($t->pu, 0, ',', ' ') }} MGA</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

