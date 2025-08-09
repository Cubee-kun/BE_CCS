{{-- filepath: resources/views/dashboard/monitoring.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Form Monitoring</h1>
    @component('components.alert')@endcomponent

    <form method="POST" action="{{ route('monitoring.create') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label for="jumlah_bibit_ditanam" class="block text-gray-700 font-medium mb-1">Jumlah Bibit Ditanam</label>
            <input type="number" name="jumlah_bibit_ditanam" id="jumlah_bibit_ditanam" required min="1"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                   value="{{ old('jumlah_bibit_ditanam') }}">
            @error('jumlah_bibit_ditanam')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="jumlah_bibit_mati" class="block text-gray-700 font-medium mb-1">Jumlah Bibit Mati</label>
            <input type="number" name="jumlah_bibit_mati" id="jumlah_bibit_mati" required min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                   value="{{ old('jumlah_bibit_mati') }}">
            @error('jumlah_bibit_mati')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="diameter_batang" class="block text-gray-700 font-medium mb-1">Diameter Batang (cm)</label>
            <input type="number" step="0.01" name="diameter_batang" id="diameter_batang" required min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                   value="{{ old('diameter_batang') }}">
            @error('diameter_batang')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="jumlah_daun" class="block text-gray-700 font-medium mb-1">Jumlah Daun</label>
            <input type="number" name="jumlah_daun" id="jumlah_daun" required min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                   value="{{ old('jumlah_daun') }}">
            @error('jumlah_daun')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-2">Survival Rate (%) – Kondisi Kesehatan Bibit</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                    $options = ['<25%', '25–45%', '50–74%', '>75%'];
                    $fields = [
                        'daun_mengering' => 'Daun mengering',
                        'daun_layu' => 'Daun layu',
                        'daun_menguning' => 'Daun menguning',
                        'bercak_daun' => 'Bercak daun',
                        'daun_serangga' => 'Daun terserang hama/hewan/serangga'
                    ];
                @endphp
                @foreach($fields as $field => $label)
                <div>
                    <label class="block text-gray-700 text-sm mb-1">{{ $label }}</label>
                    <select name="{{ $field }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        <option value="">Pilih</option>
                        @foreach($options as $option)
                            <option value="{{ $option }}" {{ old($field) == $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error($field)
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endforeach
            </div>
        </div>

        <div class="mb-4">
            <label for="survival_rate" class="block text-gray-700 font-medium mb-1">Survival Rate (%)</label>
            <input type="number" step="0.01" name="survival_rate" id="survival_rate" required min="0" max="100"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                   value="{{ old('survival_rate') }}">
            @error('survival_rate')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="dokumentasi_monitoring" class="block text-gray-700 font-medium mb-1">Dokumentasi Monitoring (foto)</label>
            <input type="file" name="dokumentasi_monitoring" id="dokumentasi_monitoring"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
            @error('dokumentasi_monitoring')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition">
                Simpan Monitoring
            </button>
        </div>
    </form>
</div>
@endsection