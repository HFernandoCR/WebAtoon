<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Diploma</title>
    <style>
        /* Importar fuentes elegantes de Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Open+Sans:wght@400;600&family=Pinyon+Script&display=swap');

        @page {
            margin: 0px;
        }

        body {
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            font-family: 'Open Sans', 'Arial', sans-serif;
            text-transform: uppercase;
        }

        .certificate-container {
            width: 100%;
            height: 100%;
            /* Ocupar toda la página definida por setPaper */
            position: absolute;
            /* Fixed helps with full page background/border in dompdf */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 0;
            box-sizing: border-box;
            color: #333;
        }

        /* Marco Decorativo - Centrado Absoluto */
        .border-pattern {
            position: absolute;
            top: 40px;
            left: 40px;
            right: 40px;
            bottom: 40px;
            border: 5px solid #003366;
            outline: 2px solid #D4AF37;
            outline-offset: -12px;
            box-sizing: border-box;
            padding: 40px 20px 60px 20px;
            text-align: center;
        }

        /* Cabecera */
        .header-logos {
            margin-bottom: 10px;
            height: 90px;
            text-align: center;
        }

        .organizer-text {
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 2px;
            color: #555;
            margin-bottom: 15px;
            text-align: center;
            font-weight: 600;
        }

        /* Título Principal */
        h1.title {
            font-family: 'Cinzel', serif;
            font-size: 54px;
            color: #003366;
            margin: 10px 0;
            letter-spacing: 8px;
            text-transform: uppercase;
            font-weight: 700;
        }

        .grant-text {
            font-family: 'Cinzel', serif;
            font-size: 14px;
            color: #D4AF37;
            /* Dorado */
            letter-spacing: 3px;
            margin: 10px 0;
            text-transform: uppercase;
        }

        /* Nombre del Estudiante */
        .student-name {
            font-family: 'Cinzel', serif;
            font-size: 38px;
            color: #222;
            margin: 10px auto;
            border-bottom: 1px solid #D4AF37;
            padding-bottom: 5px;
            display: block;
            width: 70%;
            text-align: center;
            font-weight: bold;
        }

        /* Cuerpo del Texto */
        .body-text {
            font-size: 14px;
            line-height: 1.6;
            width: 85%;
            margin: 0 auto 10px auto;
            text-align: center;
            color: #444;
        }

        .highlight {
            font-weight: 800;
            color: #003366;
        }

        .date-text {
            font-size: 12px;
            color: #666;
            margin-top: 15px;
            font-style: italic;
        }

        /* Firmas - Table Layout for DomPDF */
        .signatures-section {
            width: 100%;
            margin-top: 50px;
        }

        .sig-table {
            width: 100%;
            border-collapse: collapse;
        }

        .sig-td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 40px;
        }

        .sig-line {
            border-top: 1px solid #888;
            margin-bottom: 10px;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .sig-name {
            font-weight: 700;
            font-size: 12px;
            color: #003366;
            display: block;
        }

        .sig-title {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            display: block;
        }

        /* Footer / Folio */
        .footer-info {
            width: 100%;
            text-align: center;
            margin-top: 40px;
            /* Space from signatures */
        }
    </style>
</head>

<body>

    <div class="certificate-container">
        <div class="border-pattern">

            <!-- Cabecera con Tabla para distribución Izquierda - Centro - Derecha -->
            <table style="width: 100%; margin-bottom: 20px;">
                <tr>
                    <td style="width: 30%; text-align: left; vertical-align: middle;">
                        <img src="{{ public_path('images/TECNM.png') }}" style="height: 100px;" alt="TecNM">
                    </td>
                    <td style="width: 40%; text-align: center; vertical-align: middle;">
                        <img src="{{ public_path('images/logo.png') }}" style="height: 100px;" alt="WebAtoon">
                    </td>
                    <td style="width: 30%; text-align: right; vertical-align: middle;">
                        <img src="{{ public_path('images/ITO.svg') }}" style="height: 100px;" alt="ITO">
                    </td>
                </tr>
            </table>

            <div class="organizer-text">
                El evento académico WebAtoon<br>
                A través de la plataforma de gestión educativa
            </div>

            <div class="grant-text">Otorga {{ $role == 'ASESOR' ? 'LA PRESENTE' : 'EL PRESENTE' }}</div>
            <h1 class="title">{{ $role == 'ASESOR' ? 'CONSTANCIA' : 'DIPLOMA' }}</h1>
            <div class="grant-text">A</div>

            <div class="student-name">
                {{ $studentName }}
            </div>

            <div class="body-text">
                @if($isAdvisor)
                    POR SU VALIOSA COLABORACIÓN COMO <span class="highlight">ASESOR</span> DEL PROYECTO <span
                        class="highlight">{{ $teamName }}</span>,
                    EN EL EVENTO <span class="highlight">{{ $eventName }}</span>, IMPULSANDO EL DESARROLLO DE SOLUCIONES
                    TECNOLÓGICAS
                    EN LA CATEGORÍA DE <span class="highlight">{{ $category }}</span>.
                @else
                    POR HABER <span class="highlight">{{ $participationType }}</span> EN EL EVENTO <span
                        class="highlight">{{ $eventName }}</span>,
                    COMO INTEGRANTE DEL EQUIPO <span class="highlight">{{ $teamName }}</span>,
                    PARTICIPANDO EN EL RETO <span class="highlight">{{ $category }}</span>.
                @endif
            </div>

            <div class="date-text">
                @if($eventStart == $eventEnd)
                    Celebrado el {{ $eventStart }}.
                @else
                    Celebrado del {{ $eventStart }} al {{ $eventEnd }}.
                @endif
                <br>
                {{ $location }}, a {{ $issueDate }}.
            </div>

            <div class="signatures-section">
                <table class="sig-table">
                    <tr>
                        <td class="sig-td">
                            <div style="height: 30px;"></div>
                            <div class="sig-line"></div>
                            <span class="sig-name">ING. FERNANDO CRUZ</span>
                            <span class="sig-title">Director del Evento</span>
                        </td>
                        <td class="sig-td">
                            <div style="height: 30px;"></div>
                            <div class="sig-line"></div>
                            <span class="sig-name">COMITÉ ACADÉMICO</span>
                            <span class="sig-title">WebAtoon Hackathon</span>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Footer (QR Only) --}}
            <div class="footer-info" style="text-align: center; width: 100%; margin-top: 30px;">
                <img src="data:image/svg+xml;base64, {{ $qrCode }}" alt="QR Code" style="width: 70px; height: 70px;">
            </div>

        </div>
    </div>

</body>

</html>