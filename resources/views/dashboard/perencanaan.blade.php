{{-- filepath: resources/views/dashboard/perencanaan.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Form Perencanaan</h1>
    @component('components.alert')@endcomponent

    <form method="POST" action="{{ route('perencanaan.create') }}">
        @csrf

        <div class="mb-4">
            <label for="nama_perusahaan" class="block text-gray-700 font-medium mb-1">Nama Perusahaan</label>
            <input type="text" name="nama_perusahaan" id="nama_perusahaan" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                   value="{{ old('nama_perusahaan') }}">
            @error('nama_perusahaan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="nama_pic" class="block text-gray-700 font-medium mb-1">Nama PIC</label>
            <input type="text" name="nama_pic" id="nama_pic" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                   value="{{ old('nama_pic') }}">
            @error('nama_pic')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="narahubung" class="block text-gray-700 font-medium mb-1">Narahubung</label>
            <input type="text" name="narahubung" id="narahubung" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                   value="{{ old('narahubung') }}">
            @error('narahubung')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Jenis Kegiatan</label>
            <div class="flex space-x-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="jenis_kegiatan" value="Planting Mangrove" class="form-radio text-green-600" {{ old('jenis_kegiatan') == 'Planting Mangrove' ? 'checked' : '' }}>
                    <span class="ml-2">Planting Mangrove</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="jenis_kegiatan" value="Coral Transplanting" class="form-radio text-green-600" {{ old('jenis_kegiatan') == 'Coral Transplanting' ? 'checked' : '' }}>
                    <span class="ml-2">Coral Transplanting</span>
                </label>
            </div>
            @error('jenis_kegiatan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="lokasi" class="block text-gray-700 font-medium mb-1">Lokasi</label>
            <input type="text" name="lokasi" id="lokasi" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                   value="{{ old('lokasi') }}">
            @error('lokasi')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="jumlah_bibit" class="block text-gray-700 font-medium mb-1">Jumlah Bibit yang Ditanam</label>
            <input type="number" name="jumlah_bibit" id="jumlah_bibit" required min="1"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                   value="{{ old('jumlah_bibit') }}">
            @error('jumlah_bibit')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="jenis_bibit" class="block text-gray-700 font-medium mb-1">Jenis Bibit Tanaman</label>
            <input type="text" name="jenis_bibit" id="jenis_bibit" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                   value="{{ old('jenis_bibit') }}">
            @error('jenis_bibit')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="tanggal_pelaksanaan" class="block text-gray-700 font-medium mb-1">Tanggal Pelaksanaan</label>
            <input type="date" name="tanggal_pelaksanaan" id="tanggal_pelaksanaan" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                   value="{{ old('tanggal_pelaksanaan') }}">
            @error('tanggal_pelaksanaan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition">
                Simpan Perencanaan
            </button>
        </div>
    </form>
</div>
@endsection