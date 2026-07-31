@extends('layouts.admin')

@section('title', 'System Monitoring')
@section('header', 'Active Devices & Sessions')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="card shadow-sm border-0">
    <div class="card-header py-3">
        <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-laptop me-2"></i> Active System Sessions</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role & Branch</th>
                        <th>IP & Device Info</th>
                        <th>Last Activity</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeSessions as $session)
                        @php
                            $lastActivity = \Carbon\Carbon::createFromTimestamp($session->last_activity);
                            $isOnline = $lastActivity->diffInMinutes(now()) < 5;
                            
                            $agent = $session->user_agent;
                            $device = 'Unknown Device';
                            $browser = 'Unknown Browser';
                            
                            if (strpos($agent, 'Windows') !== false) $device = 'Windows PC';
                            elseif (strpos($agent, 'Mac') !== false) $device = 'Mac/Apple';
                            elseif (strpos($agent, 'Android') !== false) $device = 'Android Mobile';
                            elseif (strpos($agent, 'iPhone') !== false || strpos($agent, 'iPad') !== false) $device = 'iOS Mobile';
                            
                            if (strpos($agent, 'Chrome') !== false) $browser = 'Chrome';
                            elseif (strpos($agent, 'Firefox') !== false) $browser = 'Firefox';
                            elseif (strpos($agent, 'Safari') !== false) $browser = 'Safari';
                            elseif (strpos($agent, 'Edge') !== false) $browser = 'Edge';
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $session->name }}</div>
                                <small class="text-muted">{{ $session->email }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($session->role) }}</span>
                                <br>
                                <small class="text-muted">{{ $session->branch_name ?? 'Main Branch' }}</small>
                            </td>
                            <td>
                                <code>{{ $session->ip_address }}</code>
                                <br>
                                <small class="text-muted"><i class="bi bi-info-circle me-1"></i>{{ $device }} ({{ $browser }})</small>
                            </td>
                            <td>
                                <div>{{ $lastActivity->format('Y-m-d h:i A') }}</div>
                                <small class="text-muted">{{ $lastActivity->diffForHumans() }}</small>
                            </td>
                            <td>
                                @if($session->is_banned)
                                    <span class="badge bg-danger">Blocked</span>
                                @elseif($isOnline)
                                    <span class="badge bg-success"><i class="bi bi-circle-fill small me-1"></i>Online</span>
                                @else
                                    <span class="badge bg-warning text-dark">Idle</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-info text-white shadow-sm" 
                                    onclick="showMap({{ $session->latitude ?? 'null' }}, {{ $session->longitude ?? 'null' }}, '{{ $session->name }}')" title="View Location">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </button>

                                <button type="button" class="btn btn-sm btn-secondary shadow-sm mx-1" 
                                    onclick="showHistory('{{ $session->user_id }}', '{{ $session->name }}')" title="View Activity History">
                                    <i class="bi bi-clock-history"></i>
                                </button>

                                @if(auth()->id() != $session->user_id)
                                    <form action="{{ route('admin.monitoring.toggle_ban', $session->user_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @if($session->is_banned)
                                            <button type="submit" class="btn btn-sm btn-success shadow-sm" onclick="return confirm('මෙම ගිණුම නැවත සක්‍රීය කරනවාද?')" title="Unblock User">
                                                <i class="bi bi-unlock-fill"></i>
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('මෙම ගිණුම Block කරනවාද?')" title="Block User">
                                                <i class="bi bi-lock-fill"></i>
                                            </button>
                                        @endif
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mapModalLabel">User Location: <span id="mapUserName" class="text-primary"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mapContainer" style="height: 400px; width: 100%; border-radius: 8px;"></div>
                <div id="noLocationMsg" class="alert alert-warning d-none text-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> User location is not available (Access denied).
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel">Activity History: <span id="historyUserName" class="text-primary fw-bold"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 20%;">Date & Time</th>
                                <th style="width: 25%;">Action</th>
                                <th style="width: 55%;">Description</th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody">
                            </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map = null;
    let marker = null;

    function showMap(lat, lng, userName) {
        document.getElementById('mapUserName').innerText = userName;
        const modal = new bootstrap.Modal(document.getElementById('mapModal'));
        modal.show();

        const mapContainer = document.getElementById('mapContainer');
        const noLocationMsg = document.getElementById('noLocationMsg');

        if (!lat || !lng) {
            mapContainer.classList.add('d-none');
            noLocationMsg.classList.remove('d-none');
            return;
        }

        mapContainer.classList.remove('d-none');
        noLocationMsg.classList.add('d-none');

        document.getElementById('mapModal').addEventListener('shown.bs.modal', function () {
            if (map !== null) {
                map.remove();
            }

            map = L.map('mapContainer').setView([lat, lng], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            marker = L.marker([lat, lng]).addTo(map)
                .bindPopup('<b>' + userName + '</b><br>Active Here.').openPopup();
                
            map.invalidateSize();
        }, { once: true });
    }

    function showHistory(userId, userName) {
        if (!userId) {
            alert('User ID not found for this session.');
            return;
        }

        document.getElementById('historyUserName').innerText = userName;
        const tableBody = document.getElementById('historyTableBody');
        
        const modal = new bootstrap.Modal(document.getElementById('historyModal'));
        modal.show();
        
        tableBody.innerHTML = `
            <tr>
                <td colspan="3" class="text-center py-5 text-muted">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                    Fetching user history...
                </td>
            </tr>
        `;

        fetch(`/monitoring/history/${userId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if(data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                <i class="bi bi-info-circle me-2"></i> No activity records found.
                            </td>
                        </tr>
                    `;
                    return;
                }

                let rows = '';
                data.forEach(log => {
                    let dateObj = new Date(log.created_at);
                    let formattedDate = dateObj.toLocaleDateString() + ' <br><small class="text-muted">' + dateObj.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) + '</small>';
                    
                    let badgeClass = 'bg-secondary';
                    if(log.action.includes('Delete') || log.action.includes('Return')) badgeClass = 'bg-danger';
                    else if(log.action.includes('Create') || log.action.includes('Sale')) badgeClass = 'bg-success';
                    else if(log.action.includes('Update')) badgeClass = 'bg-warning text-dark';
                    else if(log.action.includes('Print')) badgeClass = 'bg-info text-dark';

                    rows += `
                        <tr>
                            <td>${formattedDate}</td>
                            <td><span class="badge ${badgeClass}">${log.action}</span></td>
                            <td>${log.description}</td>
                        </tr>
                    `;
                });
                tableBody.innerHTML = rows;
            })
            .catch(error => {
                console.error("Error fetching logs:", error);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center py-4 text-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> Error loading data. Please try again.
                        </td>
                    </tr>
                `;
            });
    }
</script>
@endsection
