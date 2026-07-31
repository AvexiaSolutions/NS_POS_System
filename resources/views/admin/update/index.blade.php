@extends('layouts.admin')

@section('title', 'System Update')
@section('header', 'System Update Manager')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        
        @if(session('success'))
            <div class="alert alert-light-success color-success alert-dismissible fade show shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-light-danger color-danger alert-dismissible fade show shadow-sm">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-white"><i class="bi bi-cloud-arrow-down-fill me-2"></i> Software Update</h6>
                <span class="badge bg-light text-primary fw-bold">Current Version: v{{ $currentVersion ?? '1.0' }}</span>
            </div>
            <div class="card-body text-center py-5">
                
                @if(isset($error))
                    <div class="alert alert-light-warning color-warning text-start mb-0">
                        <i class="bi bi-wifi-off me-2"></i> {{ $error }}
                    </div>
                    <a href="{{ route('admin.update.index') }}" class="btn btn-outline-primary px-4 mt-4">
                        <i class="bi bi-arrow-clockwise me-2"></i> Try Again
                    </a>
                @elseif($hasUpdate && isset($updateData))
                    <div class="mb-4">
                        <div class="spinner-grow text-success mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h4 class="text-success fw-bold">New Update Available!</h4>
                        <p class="text-muted mb-1">Version: <strong>v{{ $updateData['version'] ?? 'Unknown' }}</strong></p>
                        <p class="text-muted">Release Date: {{ $updateData['release_date'] ?? 'N/A' }}</p>
                    </div>

                    <div class="alert alert-light-info color-info text-start shadow-sm mb-4">
                        <h6 class="fw-bold"><i class="bi bi-info-circle-fill me-2"></i>Release Notes:</h6>
                        <p class="mb-0 ms-4 text-dark">{{ $updateData['release_notes'] ?? 'No release notes provided.' }}</p>
                    </div>

                    <form action="{{ route('admin.update.install') }}" method="POST" id="updateForm">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg px-5 shadow" id="updateBtn">
                            <i class="bi bi-download me-2"></i> Download & Install Update
                        </button>
                    </form>
                    
                    <div class="alert alert-light-warning mt-4 text-start shadow-sm border-warning border-start border-4" id="loadingText" style="display: none;">
                        <div class="d-flex align-items-center">
                            <div class="spinner-border text-warning me-3" role="status"></div>
                            <div>
                                <h6 class="mb-1 fw-bold text-dark">Downloading & Installing Update...</h6>
                                <p class="mb-0 small text-muted">Please do not close this window or refresh the page. This process may take a few minutes depending on your internet connection.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 fw-bold">Your system is up to date</h4>
                        <p class="text-muted">You are running the latest version of the POS System.</p>
                    </div>
                    <a href="{{ route('admin.update.index') }}" class="btn btn-outline-primary px-4">
                        <i class="bi bi-arrow-clockwise me-2"></i> Check for Updates
                    </a>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('updateForm')?.addEventListener('submit', function() {
        let btn = document.getElementById('updateBtn');
        let text = document.getElementById('loadingText');
        
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Installing...';
        btn.classList.add('disabled');
        btn.style.pointerEvents = 'none'; 
        text.style.display = 'block';
    });
</script>
@endsection
