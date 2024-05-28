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
    @if($errors->has('$finition') || $errors->has('taux'))
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
                        <th>Finition</th>
                        <th>Taux</th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    @foreach($finition as $d)
                        <tr>
                            <td>{{ $d->finition }}</td>
                            <td>{{ $d->taux_finition }} %</td>
                            <td>
                                <a class="dropdown-item"
                                   onclick="openModal('{{ $d->id }}','{{ $d->finition }}','{{ $d->taux_finition }}')"
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
                    <h5 class="modal-title" id="exampleModalLabel1">Modification</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <form action="{{ url('finition/update') }}" method="GET">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <input type="hidden" name="id" id="idUpdate" value="{{ old('id') }}"/>
                                <label for="idFinition" class="form-label">Finition</label>
                                <input type="text" name="finition" id="idFinition" value="{{ old('finition') }}" class="form-control @error('finition') is-invalid @enderror"/>
                                @error('finition')
                                <div class="fex">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label for="idTaux" class="form-label">Taux</label>
                                <input type="text" name="taux" id="idTaux" value="{{ old('taux') }}" class="form-control @error('taux') is-invalid @enderror"/>
                                @error('taux')
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
        function openModal(id,finition,taux){
            document.getElementById('idUpdate').value=id;
            document.getElementById('idFinition').value=finition;
            document.getElementById('idTaux').value=taux;
            $('#basicModal').modal('show');
        }
    </script>
@endsection
