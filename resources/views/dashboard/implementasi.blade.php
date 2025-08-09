@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Form Implementasi</h1>
        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6 p-4 bg-gray-50 rounded-md">
            <h3 class="font-medium text-gray-700">Data Perencanaan</h3>
            <p class="text-sm text-gray-600 mt-1"><span class="font-medium">Nama Perusahaan:</span> {{ $perencanaan->nama_perusahaan }}</p>
            <p class="text-sm text-gray-600"><span class="font-medium">Jenis Kegiatan:</span> {{ $perencanaan->jenis_kegiatan }}</p>
            <p class="text-sm text-gray-600"><span class="font-medium">Lokasi:</span> {{ $perencanaan->lokasi }}</p>
        </div>
        
        <form method="POST" action="{{ route('implementasi.store', $perencanaan->id) }}">
            @csrf
            
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-700 mb-3">Checklist Kesesuaian Perencanaan</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="checkbox" name="nama_perusahaan_sesuai" id="nama_perusahaan_sesuai" 
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                               {{ old('nama_perusahaan_sesuai') ? 'checked' : '' }}>
                        <label for="nama_perusahaan_sesuai" class="ml-2 block text-sm text-gray-700">Nama Perusahaan sesuai</label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="lokasi_sesuai" id="lokasi_sesuai" 
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                               {{ old('lokasi_sesuai') ? 'checked' : '' }}>
                        <label for="lokasi_sesuai" class="ml-2 block text-sm text-gray-700">Lokasi sesuai</label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="jenis_kegiatan_sesuai" id="jenis_kegiatan_sesuai" 
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                               {{ old('jenis_kegiatan_sesuai') ? 'checked' : '' }}>
                        <label for="jenis_kegiatan_sesuai" class="ml-2 block text-sm text-gray-700">Jenis kegiatan sesuai</label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="jumlah_bibit_sesuai" id="jumlah_bibit_sesuai" 
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                               {{ old('jumlah_bibit_sesuai') ? 'checked' : '' }}>
                        <label for="jumlah_bibit_sesuai" class="ml-2 block text-sm text-gray-700">Jumlah bibit sesuai</label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="jenis_bibit_sesuai" id="jenis_bibit_sesuai" 
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                               {{ old('jenis_bibit_sesuai') ? 'checked' : '' }}>
                        <label for="jenis_bibit_sesuai" class="ml-2 block text-sm text-gray-700">Jenis bibit sesuai</label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="tanggal_sesuai" id="tanggal_sesuai" 
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                               {{ old('tanggal_sesuai') ? 'checked' : '' }}>
                        <label for="tanggal_sesuai" class="ml-2 block text-sm text-gray-700">Tanggal sesuai</label>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="pic_koorlap" class="block text-sm font-medium text-gray-700 mb-1">PIC Koorlap</label>
                    <input type="text" name="pic_koorlap" id="pic_koorlap" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                           value="{{ old('pic_koorlap') }}">
                    @error('pic_koorlap')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="dokumentasi_kegiatan" class="block text-sm font-medium text-gray-700 mb-1">Dokumentasi Kegiatan (foto)</label>
                    <input type="file" name="dokumentasi_kegiatan" id="dokumentasi_kegiatan"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                    @error('dokumentasi_kegiatan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="geotagging" class="block text-sm font-medium text-gray-700 mb-1">Geotagging (maps/pin lokasi penanaman)</label>
                    <div class="h-64 bg-gray-100 rounded-md flex items-center justify-center">
                        <p class="text-gray-500">Map integration will be here</p>
                    </div>
                    <input type="hidden" name="geotagging" id="geotagging" value="{{ old('geotagging') }}">
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Simpan Implementasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection