@extends('baseAdmin')

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
    @if($errors->has('code') || $errors->has('travaux') || $errors->has('unite') || $errors->has('pu'))
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
            <h5 class="card-header">Liste travaux</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Travaux</th>
                        <th>Unite</th>
                        <th>PU</th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    @foreach($travaux as $d)
                        <tr>
                            <td>{{ $d->code }}</td>
                            <td>{{ $d->travaux }}</td>
                            <td>{{ $d->unite }}</td>
                            <td>{{ number_format($d->pu, 0, ',', ' ') }} Ar</td>
                            <td>
                                <a class="dropdown-item"
                                   onclick="openModal('{{ $d->id }}','{{ $d->code }}','{{ $d->travaux }}','{{ $d->unite }}','{{ $d->pu }}')"
                                   href="#"
                                ><i class="bx bx-edit"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Payement</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <form action="{{ url('travaux/update') }}" method="GET">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <input type="hidden" name="id" id="idUpdate" value="{{ old('id') }}"/>
                                <label for="idCode" class="form-label">Code</label>
                                <input type="text" name="code" id="idCode" value="{{ old('code') }}" class="form-control @error('code') is-invalid @enderror"/>
                                @error('code')
                                    <div class="fex">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <label for="idTravaux" class="form-label">Travaux</label>
                                <input type="text" name="travaux" id="idTravaux" value="{{ old('travaux') }}" class="form-control @error('travaux') is-invalid @enderror"/>
                                @error('travaux')
                                    <div class="fex">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <label for="idUnite" class="form-label">Unite</label>
                                <input type="text" name="unite" id="idUnite" value="{{ old('unite') }}" class="form-control @error('unite') is-invalid @enderror"/>
                                @error('unite')
                                    <div class="fex">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <label for="idPu" class="form-label">PU</label>
                                <input type="text" name="pu" id="idPu" value="{{ old('pu') }}" class="form-control @error('pu') is-invalid @enderror"/>
                                @error('pu')
                                    <div class="fex">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Confirmer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function openModal(id,code,travaux,unite,pu){
            document.getElementById('idUpdate').value=id;
            document.getElementById('idCode').value=code;
            document.getElementById('idTravaux').value=travaux;
            document.getElementById('idUnite').value=unite;
            document.getElementById('idPu').value=pu;
            $('#basicModal').modal('show');
        }
    </script>
@endsection

