@extends('layouts.app')
@section('page_heading', 'Settings')
@section('title', 'Settings')

@section('content')
<style>



    .settings-section {
        background: white;
        border-radius: 8px;
        margin-bottom: 25px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        height: calc(100vh - 200px);
    }

    /* Tab styles */
    .tab-container {
        display: flex;
        border-bottom: 2px solid #e9ecef;
        background: #f8f9fa;
        padding: 0 25px;
    }

    .settings-page-tabs .tab {
        padding: 15px 25px !important;
        cursor: pointer !important;
        font-size: 16px !important;
        font-weight: 600 !important;
        color: #6c757d !important;
        border-bottom: 3px solid transparent !important;
        transition: all 0.3s ease !important;
    }

    .settings-page-tabs .tab.active {
        color: #3498db !important;
        border-bottom: 3px solid #3498db !important;
    }

    .settings-page-tabs .tab:hover {
        color: #3498db !important;
        background: rgba(52, 152, 219, 0.1) !important;
    }

    .tab-content {
        display: none;
        padding: 25px;
        height: calc(100% - 50px);
        overflow-y: auto;
    }

    .tab-content.active {
        display: block;
    }

    .settings-group {
        margin-bottom: 30px;
    }

    .group-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #f8f9fa;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-size: 16px;
        font-weight: 500;
        color: #2c3e50;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 15px;
        background: white;
        transition: border-color 0.3s ease;
        height: auto;
    }

    .form-control:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    }

    .form-control[readonly] {
        background: #f8f9fa;
        color: #6c757d;
    }

    .compact-checkboxes {
        display: grid;
        grid-template-columns: 1fr;
        gap: 10px;
        margin: 12px 0;
    }

    .compact-checkboxes .checkbox-item {
        padding: 6px 0;
        gap: 10px;
        display: flex;
        align-items: center;
    }

    .compact-checkboxes .checkbox-item input {
        width: 18px;
        height: 18px;
    }

    .compact-checkboxes .checkbox-item label {
        font-size: 16px;
        margin-bottom: 0;
        cursor: pointer;
    }

    .toggle-container {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #ccc;
        transition: 0.4s;
        border-radius: 26px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background: white;
        transition: 0.4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background: #3498db;
    }

    input:checked + .slider:before {
        transform: translateX(24px);
    }

    .toggle-label {
        font-size: 16px;
        color: #2c3e50;
        font-weight: 500;
    }

    .section-header {
        background: #f8f9fa;
        padding: 15px 25px;
        border-bottom: 1px solid #e9ecef;
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .section-description {
        font-size: 16px;
        color: #7f8c8d;
    }

    .section-content {
        padding: 0;
    }

    .actions-bar {
        position: sticky;
        bottom: 0;
        background: white;
        padding: 20px 30px;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        box-shadow: 0 -1px 3px rgba(0,0,0,0.1);
        margin-top: auto;
    }

    .btn {
        padding: 12px 25px;
        border: 1px solid transparent;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-primary {
        background: #3498db;
        color: white;
        border-color: #3498db;
    }

    .btn-primary:hover {
        background: #2980b9;
        border-color: #2980b9;
    }

    .btn-secondary {
        background: white;
        color: #6c757d;
        border-color: #dee2e6;
    }

    .btn-secondary:hover {
        background: #f8f9fa;
    }
</style>

<div class="settings-section">
    <div class="section-header">
        <div class="section-title">⚙️ General Settings</div>
        <div class="section-description">Configure your system preferences and account settings</div>
    </div>
    
    <!-- Tab Navigation -->
    <div class="tab-container settings-page-tabs">
        <div class="tab active" data-tab="password-tab">Password</div>
        <div class="tab" data-tab="datetime-tab">Date & Time</div>
        <div class="tab" data-tab="export-tab">Export</div>
        <div class="tab" data-tab="notifications-tab">Notifications</div>
    </div>
    
    <div class="section-content">
        <!-- Password Tab -->
        <div id="password-tab" class="tab-content active">
            <div class="settings-group">
                <h4 class="group-title">🔐 Change Password</h4>
                <form id="passwordForm">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Enter new password">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm password">
                    </div>
                </form>
            </div>
        </div>

        <!-- Date & Time Tab -->
        <div id="datetime-tab" class="tab-content">
            <div class="settings-group">
                <h4 class="group-title">🕐 Date & Time Settings</h4>
                <form id="dateTimeForm">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Timezone</label>
                        <select name="timezone" class="form-control">
                            <option value="Asia/Manila">Asia/Manila (GMT+8)</option>
                            <option value="UTC">UTC (GMT+0)</option>
                            <option value="Asia/Tokyo">Asia/Tokyo (GMT+9)</option>
                            <option value="America/New_York">America/New_York (GMT-5)</option>
                            <option value="Europe/London">Europe/London (GMT+0)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date Format</label>
                        <select name="date_format" class="form-control">
                            <option value="DD/MM/YYYY">DD/MM/YYYY</option>
                            <option value="MM/DD/YYYY">MM/DD/YYYY</option>
                            <option value="YYYY-MM-DD">YYYY-MM-DD</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time Format</label>
                        <select name="time_format" class="form-control">
                            <option value="24-hour">24-hour</option>
                            <option value="12-hour">12-hour</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Export Tab -->
        <div id="export-tab" class="tab-content">
            <div class="settings-group">
                <h4 class="group-title">📁 Export Settings</h4>
                <form id="exportForm">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Export Format</label>
                        <select name="export_format" class="form-control">
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                            <option value="pdf">PDF Report (.pdf)</option>
                            <option value="json">JSON (.json)</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Notifications Tab -->
        <div id="notifications-tab" class="tab-content">
            <div class="settings-group">
                <h4 class="group-title">🔔 Notification Types</h4>
                <form id="notificationForm">
                    @csrf
                    <div class="form-group">
                        <div class="toggle-container">
                            <label class="toggle-switch">
                                <input type="checkbox" name="enable_notifications" checked>
                                <span class="slider"></span>
                            </label>
                            <span class="toggle-label">Enable Notifications</span>
                        </div>
                    </div>
                    
                    <div class="compact-checkboxes">
                        <div class="checkbox-item">
                            <input type="checkbox" id="heavy-rain" name="heavy_rain" checked>
                            <label for="heavy-rain">Heavy Rain</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="drought" name="drought" checked>
                            <label for="drought">Drought</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="flood" name="flood_risk" checked>
                            <label for="flood">Flood Risk</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="device-offline" name="device_offline">
                            <label for="device-offline">Device Offline</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="data-anomaly" name="data_anomalies" checked>
                            <label for="data-anomaly">Data Anomalies</label>
                        </div>
                    </div>
                </form>
            </div>

            <div class="settings-group">
                <h4 class="group-title">📢 Alert Settings</h4>
                <form id="alertForm">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Alert Frequency</label>
                        <select name="alert_frequency" class="form-control">
                            <option value="immediate">Immediate</option>
                            <option value="5min">Every 5 minutes</option>
                            <option value="15min">Every 15 minutes</option>
                            <option value="30min">Every 30 minutes</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rain Threshold (mm/h)</label>
                        <input type="number" name="rain_threshold" class="form-control" value="25">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Action buttons -->
<div class="actions-bar">
    <button type="button" class="btn btn-secondary">Cancel</button>
    <button type="button" class="btn btn-primary" onclick="saveAllSettings()">Save Changes</button>
</div>

<script>
// Fixed tab switching functionality with unique naming to avoid conflicts
function switchSettingsTab(tabId) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.remove('active');
    });
    
    // Remove active class from all tabs
    const tabs = document.querySelectorAll('.settings-page-tabs .tab');
    tabs.forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected tab content
    const targetContent = document.getElementById(tabId);
    if (targetContent) {
        targetContent.classList.add('active');
    }
    
    // Add active class to the tab that corresponds to this tabId
    const activeTab = document.querySelector(`.settings-page-tabs [data-tab="${tabId}"]`);
    if (activeTab) {
        activeTab.classList.add('active');
    }
}

// Save all settings function
function saveAllSettings() {
    const button = document.querySelector('.btn-primary');
    const originalText = button ? button.textContent : 'Save Changes';
    
    if (button) {
        button.textContent = '✓ Saving...';
        button.style.background = '#27ae60';
    }
    
    // Collect all form data
    const passwordForm = document.getElementById('passwordForm');
    const dateTimeForm = document.getElementById('dateTimeForm');
    const exportForm = document.getElementById('exportForm');
    const notificationForm = document.getElementById('notificationForm');
    const alertForm = document.getElementById('alertForm');
    
    const passwordData = passwordForm ? new FormData(passwordForm) : new FormData();
    const dateTimeData = dateTimeForm ? new FormData(dateTimeForm) : new FormData();
    const exportData = exportForm ? new FormData(exportForm) : new FormData();
    const notificationData = notificationForm ? new FormData(notificationForm) : new FormData();
    const alertData = alertForm ? new FormData(alertForm) : new FormData();
    
    // Validate password if provided
    const newPassword = passwordData.get('new_password');
    const confirmPassword = passwordData.get('confirm_password');
    
    if (newPassword && newPassword !== confirmPassword) {
        alert('Passwords do not match!');
        if (button) {
            button.textContent = originalText;
            button.style.background = '#3498db';
        }
        return;
    }
    
    if (newPassword && newPassword.length < 8) {
        alert('Password must be at least 8 characters long!');
        if (button) {
            button.textContent = originalText;
            button.style.background = '#3498db';
        }
        return;
    }
    
    setTimeout(() => {
        if (button) {
            button.textContent = '✓ Saved Successfully!';
            setTimeout(() => {
                button.textContent = originalText;
                button.style.background = '#3498db';
            }, 1500);
        }
    }, 1000);
    
    // Settings data (for backend submission)
    console.log('Settings saved:', {
        password: newPassword ? 'Updated' : 'No change',
        timezone: dateTimeData.get('timezone'),
        timeFormat: dateTimeData.get('time_format'),
        exportFormat: exportData.get('export_format'),
        notifications: Object.fromEntries(notificationData),
        alertFrequency: alertData.get('alert_frequency'),
        rainThreshold: alertData.get('rain_threshold')
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Prevent conflicts with layout scripts
    setTimeout(function() {
        // Set up tab click event listeners with specific targeting
        document.querySelectorAll('.settings-page-tabs .tab').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const tabId = this.getAttribute('data-tab');
                if (tabId) {
                    switchSettingsTab(tabId);
                }
            });
        });
    }, 100); // Small delay to avoid conflicts with layout initialization
    
    // Set up form event listeners
    const passwordForm = document.getElementById('passwordForm');
    const dateTimeForm = document.getElementById('dateTimeForm');
    const exportForm = document.getElementById('exportForm');
    const notificationForm = document.getElementById('notificationForm');
    const alertForm = document.getElementById('alertForm');
    
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            saveAllSettings();
        });
    }
    
    if (dateTimeForm) {
        dateTimeForm.addEventListener('change', function() {
            console.log('Date/time settings changed');
        });
    }
    
    if (exportForm) {
        exportForm.addEventListener('change', function() {
            console.log('Export format changed to:', this.export_format ? this.export_format.value : 'unknown');
        });
    }
    
    if (notificationForm) {
        notificationForm.addEventListener('change', function() {
            console.log('Notification settings changed');
        });
    }
    
    if (alertForm) {
        alertForm.addEventListener('change', function() {
            console.log('Alert settings changed');
        });
    }
    
    // Toggle switch functionality
    document.querySelectorAll('.toggle-switch input').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.compact-checkboxes input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.disabled = !this.checked;
                if (!this.checked) {
                    checkbox.checked = false;
                }
            });
        });
    });
});
</script>
@endsection