@extends('baseAdmin')

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    @if(isset($validation))
        @foreach($validation as $v)
            <p>{{ $v }}</p>
        @endforeach
    @endif
    @if(isset($erreur))
        @foreach($erreur as $e)
            <p>insertion : {{ $e }}</p>
        @endforeach
    @endif
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <h5 class="card-header">IMPORT</h5>
                <div class="card-body">
                    <form id="" class="mb-3" action="{{ url('import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Csv travaux et maison</label>
                            <input class="form-control" type="file" id="formFile" name="file" />
                            @error('file')
                                {{ $message }}
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formFile" class="form-label">Csv devis et client</label>
                            <input class="form-control" type="file" id="formFile" name="devis" />
                            @error('devis')
                            {{ $message }}
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Importer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

