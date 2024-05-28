@extends('base')

@section('content')
    <div id="affichage"></div>
    <div class="row">
        <div class="card col-md-6">
        <div class="card-body">
    <form id="monform" class="mb-3">
        @csrf
            <div class="row">
                <div class="col mb-3">
                    <label for="ref_d" class="form-label">Reference devis</label>
                    <input type="text" name="ref_d" id="refDevis" value="{{ $ref }}" class="form-control @error('ref_d') is-invalid @enderror " readonly/>
                    <label for="ref_p" class="form-label">Reference paiement</label>
                    <input type="text" name="ref_p" id="nameBasic" value="{{ old('ref_p') }}" class="form-control @error('ref_p') is-invalid @enderror"/>
                    @error('ref_p')
                    <div class="fex">
                        {{ $message }}
                    </div>
                    @enderror
                    <label for="nameBasic" class="form-label">Date</label>
                    <input type="date" name="date_paiement" id="nameBasic" value="{{ old('date_paiement') }}" class="form-control @error('date_paiement') is-invalid @enderror"/>
                    @error('date_paiement')
                    <div class="fex">
                        {{ $message }}
                    </div>
                    @enderror
                    <label for="montant" class="form-label">Montant</label>
                    <input type="text" name="montant" id="montant" value="{{ old('montant') }}" class="form-control @error('montant') is-invalid @enderror"/>
                    @error('montant')
                    <div class="fex">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Confirmer payement</button>
        </div>
    </form>
        </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#monform').on('submit', function(event) {
                event.preventDefault(); // Empêche l'envoi traditionnel du formulaire
                var csrf_token = $('meta[name="csrf-token"]').attr('content');

                // Récupère les données du formulaire
                var formData = $(this).serialize();

                // Fait l'appel AJAX
                $.ajax({
                    type: 'POST',
                    url: 'http://127.0.0.1:8000/devis/paiement',
                    headers: {
                        'X-CSRF-TOKEN': csrf_token, // Inclure le jeton CSRF dans les en-têtes de la requête
                    },
                    data: formData,
                    success: function(response) {
                        $('#affichage').removeClass().addClass(response.classs).text(response.messagee);
                    },
                    error: function(xhr, status, error) {
                        // Affiche un message d'erreur
                        $('#affichage').html('Une erreur est survenue : ' + error);
                    }
                });
            });
        });

    </script>
@endsection
