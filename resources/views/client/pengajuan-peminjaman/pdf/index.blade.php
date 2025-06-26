<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Formulir Peminjaman Alat Laboratorium</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 16px;
            line-height: 1.5;
        }

        h2,
        h3 {
            text-align: center;
            margin: 0;
        }

        .header,
        .footer {
            text-align: center;
        }

        .section {
            margin-top: 20px;
            margin: 0mm 20mm 0mm 20mm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        td,
        th {
            border: 1px solid black;
            padding: 5px;
            vertical-align: top;
        }

        .no-border td {
            border: none;
        }

        .signature-1 {
            text-align: left;
            margin: 40mm 20mm 0mm 20mm;
        }

        .signature-2 {
            text-align: center;
            margin: 0mm 20mm 0mm 20mm;
        }

        .center {
            text-align: center;
        }

        .alat-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .alat-table th,
        .alat-table td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
        }

        .alat-table th {
            background-color: #f9f9f9;
        }

        .footer {
            width: 60%;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
            padding-top: 5px;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('assets/kop-surat.png') }}" alt="" width="100%">
        <hr>
        <h4>FORMULIR PEMINJAMAN ALAT LABORATORIUM</h4>
    </div>

    <div class="section">
        <p>Dengan ini menyatakan bahwa:</p>
        <table class="no-border" style="width: 100%; margin: 0mm 10mm 0mm 10mm;">
            <tr>
                <td style="width: 25%;">Nama</td>
                <td style="width: 75%;">: {{ $user->name ?? '-' }}</td>
            </tr>
            <tr>
                <td style="width: 25%;">NIM</td>
                <td style="width: 75%;">: {{ $user->nim ?? '-' }}</td>
            </tr>
            <tr>
                <td style="width: 25%;">No. HP</td>
                <td style="width: 75%;">: {{ $user->no_hp ?? '-' }}</td>
            </tr>
            <tr>
                <td style="width: 25%;">Program Studi</td>
                <td style="width: 75%;">: {{ $user->prodi ?? '-' }}</td>
            </tr>
        </table>

        <p align="justify">Mengajukan permohonan peminjaman alat laboratorium dengan rincian terlampir.</p>
        <p align="justify">Peminjam bersedia memenuhi persyaratan yang ada di laboratorium dan jika terjadi kerusakan
            atau kehilangan
            barang yang dipinjam, maka peminjam bersedia untuk bertanggung jawab.</p>
    </div>

    <div class="signature-1">
        <table class="no-border" style="width: 100%; margin-top: 20px; margin-left: 330px;">
            <tr>
                <td>{{ $tanggalHariIni }}<br>Peminjam,<br><br><br><br><br>{{ $user->name ?? '-' }}<br>{{ $user->nim ?? '-' }}
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <img width="100%" src="{{ public_path('assets/kop-surat-footer.png') }}" alt="">
    </div>


    <div style="page-break-before: always;"></div>

    <div class="section">
        <h4 align="center">RINCIAN KEGIATAN</h4>
        <table class="no-border">
            <tr>
                <td>Keperluan</td>
                <td>: {{ $keperluan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tempat Kegiatan</td>
                <td>: {{ $tempatKegiatan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Lama Waktu Peminjaman</td>
                <td>: {{ $tanggalPeminjaman ?? '-' }} s.d. {{ $tanggalPengembalian ?? '-' }}</td>
            </tr>
            <tr>
                <td>Judul Penelitian</td>
                <td>: {{ $judulPenelitian ?? '-' }}</td>
            </tr>
            @if (!auth()->user()->hasRole('Dosen'))
                <tr>
                    <td>Dosen Pembimbing</td>
                    <td>: {{ $dosenPembimbing ?? '-' }}</td>
                </tr>
            @endif
        </table>
    </div>

    <div class="section">
        <h4 align="center">DAFTAR ALAT YANG DIPINJAM</h4>
        <table class="alat-table" border="1">
            <thead>
                <tr>
                    <th rowspan="2">No.</th>
                    <th rowspan="2">Nama Alat</th>
                    <th rowspan="2">Jumlah</th>
                    <th colspan="2">Kondisi Alat</th>
                    <th rowspan="2">Tanggal Pengembalian</th>
                    <th rowspan="2">Paraf Laboran</th>
                </tr>
                <tr>
                    <th>Awal</th>
                    <th>Akhir</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($alatDipinjam as $index => $alat)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $alat['nama'] ?? '-' }}</td>
                        <td>{{ $alat['jumlah'] ?? '-' }}</td>
                        <td>{{ $alat['kondisi_sebelum'] ?? '-' }}</td>
                        <td></td>
                        <td>{{ $alat['tgl_pengembalian'] ?? '-' }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="page-break-before: always;"></div>

    <div class="signature-2">
        <table class="no-border" style="width: 100%; margin-top: 20px;">
            <tr>
                <td width="50%"><br><br>Laboran<br><br><br><br><br>Ading Atma Gamilang<br>NRK/NIP</td>
                <td width="50%">{{ $tanggalHariIni }}<br><br>Koordinator Laboratorium<br>Program Studi Teknik
                    Biomedis<br><br><br><br>Doni Bowo Nugroho, S.Pd., M.Sc<br>NRK. 1992092420211411</td>
            </tr>
        </table>
        <br><br>
        <p>Menyetujui,</p>
        <p>Koordinator Laboratorium Fakultas Teknologi Industri</p>
        <br><br><br>
        <p><strong>Dr. Kardo Rajagukguk, S.Pd., M.Eng.</strong><br>NIP 198909272019031014</p>
    </div>

</body>

</html>
