<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        /* Style pour la table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        /* Style pour l'en-tête de la table */
        th {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        /* Style pour les cellules de la table */
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        /* Style pour chaque ligne impaire de la table */
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Style pour la ligne du total */
        .total-row td:first-child {
            font-weight: bold;
            text-align: right;
        }

        .total-row td:last-child {
            font-weight: bold;
            text-align: right;
        }

        /* Style pour la section contenant les informations sur le devis */
        .devis-info {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            align-content: center;
            align-items: center;
        }

        .devis-info h2 {
            margin: 0;
            color: #333;
        }

        .devis-info ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .devis-info ul li {
            margin-bottom: 5px;
            color: #666;
        }

        .devis-info strong {
            font-weight: bold;
            color: #000;
        }
    </style>
</head>
<body>
<div>
    <div class="devis-info">
        <div>
            <div>
                <ul>
                    <li><strong>Ref devis :</strong> {{$devis->ref_devis}}</li>
                    <li><strong>Date devis :</strong> {{$devis->date_devis}}</li>
                    <li><strong>Date debut :</strong> {{$devis->date_debut}}</li>
                    <li><strong>Date fin :</strong> {{$devis->date_fin}}</li>
                    <li><strong>Type maison :</strong> {{$devis->type_maison}}</li>
                    <li><strong>Finition :</strong> {{$devis->finition}}</li>
                    <li><strong>Taux finition :</strong> {{$devis->taux_finition}} %</li>
                </ul>
            </div>
        </div>
    </div>
    <h2>Liste travaux</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Code</th>
            <th>Designation</th>
            <th>Unite</th>
            <th>Qte</th>
            <th>PU</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($travaux as $t)
            <tr>
                <td>{{ $t->code }}</td>
                <td>{{ $t->travaux }}</td>
                <td>{{ $t->unite }}</td>
                <td>{{ $t->qte }}</td>
                <td style="text-align: right">{{ number_format($t->pu, 2, ',', ' ') }} MGA</td>
                <td style="text-align: right">{{ number_format(($t->pu)*($t->qte), 2, ',', ' ') }} MGA</td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="3">Total sans finiton</td>
            <td colspan="3" style="text-align: right">{{ number_format($devis->prix, 2, ',', ' ') }} MGA</td>
        </tr>
        <tr class="total-row">
            <td colspan="3">Total avec finition</td>
            <td colspan="3" style="text-align: right">{{ number_format($devis->prix_total, 2, ',', ' ') }} MGA</td>
        </tr>
        </tbody>
    </table>
    <h2>Liste paiement</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Ref paiement</th>
            <th>Date paiement</th>
            <th>Montant</th>
        </tr>
        </thead>
        <tbody>
        @foreach($paiement as $p)
            <tr>
                <td>{{ $p->ref_paiement }}</td>
                <td>{{ $p->date_paiement }}</td>
                <td style="text-align: right">{{ number_format($p->montant, 2, ',', ' ') }} MGA</td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="2">Total paiement</td>
            <td colspan="1" style="text-align: right">{{ number_format($total, 2, ',', ' ') }} MGA</td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
