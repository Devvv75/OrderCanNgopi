@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <style>
        .hero {
            background-image: url('/images/Can-Ngopifoto-92.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }


        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5); /* Overlay gelap */
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
        }

        @media (max-width: 768px) {
            .hero h2 {
                font-size: 1.8rem;
            }
        }
    </style>

    <div class="hero">
        <div class="hero-content">
            <h2 class="text-4xl font-semibold mb-6">Selamat Datang di Can Ngopi</h2>
            <a href="{{ route('halamanutama') }}"
               class="btn btn-danger px-5 py-2 rounded-lg text-white font-medium hover:bg-red-700 transition">
                Pesan Sekarang
            </a>
        </div>
    </div>
@endsection
