@extends('baseAdmin')

@section('content')
    <div class="row">
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow text-white border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col pr-0">
                        <p class="small text-muted mb-0">Montant total devis</p>
                        <span style="text-align:right" class="h3 mb-0 text-black">{{ number_format($montant, 2, ',', ' ') }} MGA</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow text-white border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col pr-0">
                        <p class="small text-muted mb-0">Montant total paiement effectué</p>
                        <span style="text-align:right" class="h3 mb-0 text-black">{{ number_format($paiement, 2, ',', ' ') }} MGA</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="col-12 col-lg-12 order-2 order-md-3 order-lg-2 mb-4">
        <div class="card">
            <div class="row row-bordered g-0">
                <div class="col-md-10">
                    <div id="uu" class="px-2"></div>
                </div>
                <div class="col-md-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="exampleFormControlSelect1" class="form-label">Annee</label>
                            <select class="form-select" id="exampleFormControlSelect1"  name="annee" aria-label="Default select example">
                                @foreach($annees as $annee)
                                    <option value="{{ $annee->year }}">{{ $annee->year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var titres = ['Jan','Fev','Mar','Avr','Mai','Juin','Juil','Aout','Sept','Oct','Nov','Dec',];
        var options = {
            title: {
                text: "Montant devis par mois",
                align: "center",
                style: {
                    fontSize: "24px" // taille de police du titre
                }
            },
            series: [{
                name: 'Montant devis',
                data: @json($donnees)
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: titres,
            },
            yaxis: {
                labels: {
                    show: false
                },
                title: {
                    text: ''
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val.toLocaleString('fr-FR', {maximumFractionDigits: 0}) + " Ar";
                    }
                }
            },
        };
        var chart = new ApexCharts(document.querySelector("#uu"), options);
        chart.render();
    </script>
    <script>
        function updateChart(donnee,annee) {
            chart.updateSeries([{ data: donnee }]);
        }

        // Code pour déclencher l'appel AJAX lors du changement de valeur dans le select
        $(document).ready(function() {
            $('#exampleFormControlSelect1').change(function() {
                var selectedValue = $(this).val(); // Obtenir la valeur sélectionnée du select
                var csrf_token = $('meta[name="csrf-token"]').attr('content'); // Obtenir le jeton CSRF
                $.ajax({
                    url: 'http://127.0.0.1:8000/bar/change', // URL de votre endpoint Laravel
                    type: 'POST', // Méthode HTTP utilisée
                    headers: {
                        'X-CSRF-TOKEN': csrf_token, // Inclure le jeton CSRF dans les en-têtes de la requête
                    },
                    data: {
                        choix : selectedValue // Envoyer la valeur sélectionnée
                    },
                    success: function(response) {
                        // Mettre à jour les données du graphique avec les nouvelles données reçues
                        updateChart(response.donnees,response.annee);
                    },
                    error: function(xhr, status, error) {
                        // Gestion des erreurs
                    }
                });
            });
        });
    </script>
@endsection
