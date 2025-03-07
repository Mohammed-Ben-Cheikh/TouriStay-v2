<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de Réservation #{{ $booking->id }}</title>
    <style>
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #2d3748;
            padding: 40px;
            background: #ffffff;
            max-width: 900px;
            margin: 0 auto;
        }
        /* Header Section */
        .header {
            background-color: #1e3a8a; /* Remplace le gradient par une couleur unie pour compatibilité */
            color: #ffffff;
            padding: 20px;
            border-bottom: 4px solid #3b82f6;
        }
        .header h1 {
            font-size: 24pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 10pt;
        }
        .logo {
            font-size: 14pt;
            font-weight: bold;
            text-align: right;
            margin-bottom: 10px;
        }
        /* Grid Layout */
        .grid-container {
            display: table;
            width: 100%;
            margin: 20px 0;
        }
        .section {
            display: table-cell;
            width: 50%;
            padding: 15px;
            background: #f9fafb;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .section:first-child {
            border-right: none;
        }
        .section h2 {
            font-size: 16pt;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 5px;
        }
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 0;
            text-align: left;
        }
        th {
            font-weight: bold;
            color: #4b5563;
            width: 100px;
        }
        td {
            font-weight: normal;
        }
        /* Property Card */
        .property-card {
            background-color: #1e3a8a; /* Remplace le gradient par une couleur unie */
            color: #ffffff;
            padding: 20px;
            border: 1px solid #3b82f6;
        }
        .property-card h2 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .property-card p {
            font-size: 12pt;
            margin-bottom: 10px;
        }
        .price-details {
            border-top: 1px solid #ffffff;
            margin-top: 15px;
            padding-top: 15px;
        }
        .price-details table td {
            padding: 5px 0;
        }
        .price-details td:first-child {
            font-weight: normal;
        }
        .price-details td:last-child {
            text-align: right;
        }
        .total-price {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ffffff;
            font-size: 14pt;
            font-weight: bold;
            text-align: right;
        }
        /* Footer */
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            color: #6b7280;
            font-size: 10pt;
            line-height: 1.6;
        }
        .footer a {
            color: #3b82f6;
            text-decoration: none;
        }
        /* Print Optimization */
        @media print {
            body {
                padding: 0;
                max-width: none;
            }
            .header, .property-card {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .section {
                border: 1px solid #e2e8f0 !important;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">TouriStay</div>
        <h1>Confirmation de Réservation #{{ $booking->id }}</h1>
        <p>Date d'émission : {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
        <p>Statut : {{ ucfirst($booking->status) }}</p>
    </div>

    <!-- Guest and Stay Details -->
    <div class="grid-container">
        <div class="section">
            <h2>Détails du séjour</h2>
            <table>
                <tr>
                    <th>Check-in :</th>
                    <td>{{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Check-out :</th>
                    <td>{{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Voyageurs :</th>
                    <td>{{ $booking->guests }}</td>
                </tr>
                <tr>
                    <th>Durée :</th>
                    <td>{{ \Carbon\Carbon::parse($booking->check_in)->diffInDays($booking->check_out) }} nuits</td>
                </tr>
            </table>
        </div>
        <div class="section">
            <h2>Informations du voyageur</h2>
            <table>
                <tr>
                    <th>Nom :</th>
                    <td>{{ $booking->guest_name }}</td>
                </tr>
                <tr>
                    <th>Email :</th>
                    <td>{{ $booking->guest_email }}</td>
                </tr>
                <tr>
                    <th>Téléphone :</th>
                    <td>{{ $booking->guest_phone }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Property and Pricing -->
    <div class="property-card">
        <h2>{{ $property->title }}</h2>
        <p>{{ $property->location }}</p>
        <div class="price-details">
            <table>
                <tr>
                    <td>Prix par nuit :</td>
                    <td>{{ number_format($property->price, 2, ',', ' ') }} €</td>
                </tr>
                <tr>
                    <td>Nombre de nuits :</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_in)->diffInDays($booking->check_out) }}</td>
                </tr>
                <tr>
                    <td>Nombre de voyageurs :</td>
                    <td>{{ $booking->guests }}</td>
                </tr>
            </table>
            <div class="total-price">
                Prix total : {{ number_format($booking->total_price, 2, ',', ' ') }} €
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Pour toute question, contactez notre équipe à <a href="mailto:support@touristay.com">support@touristay.com</a></p>
        <p>TouriStay - Votre partenaire de voyage de confiance</p>
        <p>Document officiel de confirmation - Généré le {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>