@extends('base')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->has('date_payement') || $errors->has('montant'))
        <script>
            $(document).ready(function (){
                $('#basicModal').modal('show');
            });
        </script>
    @endif
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
                        <th>Reste</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    @foreach($devis as $d)
                        <tr>
                            <td>{{ $d->ref_devis }}</td>
                            <td>{{ $d->date_devis }}</td>
                            <td>{{ $d->type_maison }}</td>
                            <td>{{ number_format($d->prix_total, 0, ',', ' ') }} MGA</td>
                            <td>{{ number_format($d->paye, 0, ',', ' ') }} MGA</td>
                            <td>{{ number_format($d->reste, 0, ',', ' ') }} MGA</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                           href="{{ url('devis/goToPaiement/'.$d->ref_devis) }}"
                                        ><i class="bx bx-edit-alt me-1"></i> Paiement</a
                                        >
                                        <a class="dropdown-item" href="{{ url('devis/'.$d->ref_devis) }}"
                                        ><i class="bx bx-trash me-1"></i>Exporter pdf</a
                                        >
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
