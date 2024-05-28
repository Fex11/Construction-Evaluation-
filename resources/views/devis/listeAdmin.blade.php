@extends('baseAdmin')

@section('content')
    <div class="row">
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <!-- Search -->
            <div class="navbar-nav align-items-center mt-2">
                <div class="nav-item d-flex align-items-center">
                    <i class="bx bx-search fs-4 lh-0"></i>
                    <input
                        type="text"
                        class="form-control border-0 shadow-none"
                        placeholder="Search..."
                        aria-label="Search..."
                    />
                </div>
            </div>
            <!-- /Search -->
            <h5 class="card-header">Liste devis</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Ref devis</th>
                        <th>Date devis</th>
                        <th>Maison</th>
                        <th>Prix total</th>
                        <th>Payé</th>
                        <th>Pourcentage</th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    @foreach($devis as $d)
                        <tr bgcolor= @if((($d->paye*100)/$d->prix_total)<50) "#cd5c5c" @endif @if((($d->paye*100)/$d->prix_total)>50) "#8fbc8f" @endif>
                            <td>{{ $d->ref_devis }}</td>
                            <td>{{ $d->date_devis }}</td>
                            <td>{{ $d->type_maison }}</td>
                            <td>{{ number_format($d->prix_total, 0, ',', ' ') }} MGA</td>
                            <td>{{ number_format($d->paye, 0, ',', ' ') }} MGA</td>
                            <td>{{ number_format(($d->paye*100)/$d->prix_total, 2, ',', ' ') }} %</td>
                            <td><a href="{{ url('devis/detail/'.$d->ref_devis) }}"><button class="btn btn-primary">Voir travaux</button></a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
