
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
    <div class="row">
        <form id="" class="mb-3" action="{{ url('devis/create') }}" method="GET">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <label for="exampleFormControlSelect1" class="form-label"><h6>Choisir maison</h6></label>
                        <div class="row">
                            @foreach($maisons as $maison)
                                <div class="col-lg-3 col-md-6">
                                    <div class="card mb-2" style="height: 15rem">
                                        <div class="card-body">
                                            <h4 class="card-text">{{ $maison->type_maison }}</h4>
                                            <h3 class="card-title" style="color: #007bff" >{{ number_format($maison->prix, 0, ',', ' ') }} Ar</h3>
                                            <p class="card-text">{{ $maison->descri }}</p>
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="maison"
                                                id="inlineRadio1"
                                                value="{{ $maison->id_maison }}"
                                            />
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row p-4">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Ref devis</label>
                                <input
                                    type="text"
                                    class="form-control @error('ref_d') is-invalid @enderror"
                                    id="exampleFormControlInput1"
                                    name="ref_d"
                                    value="{{ old('ref_d') }}"
                                />
                                @error('ref_d')
                                <div class="fex">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Lieu</label>
                                <input
                                    type="text"
                                    class="form-control @error('lieu') is-invalid @enderror"
                                    id="exampleFormControlInput1"
                                    name="lieu"
                                    value="{{ old('lieu') }}"
                                />
                                @error('lieu')
                                <div class="fex">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlSelect1" class="form-label">Finition</label>
                                <select class="form-select" id="exampleFormControlSelect1"  name="finition" aria-label="Default select example">
                                    @foreach($finitions as $finition)
                                        <option value="{{ $finition->id }}">{{ $finition->finition }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Date debut travaux</label>
                                <input
                                    type="date"
                                    class="form-control @error('date_debut') is-invalid @enderror"
                                    id="exampleFormControlInput1"
                                    name="date_debut"
                                    value="{{ old('date_debut') }}"
                                />
                                @error('date_debut')
                                    <div class="fex">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div><button type="submit" class="btn btn-primary">Ajouter</button></div>
            </div>
        </form>
    </div>

@endsection
