<x-layouts.admin title="Admin Dashboard | LapkingHub" page-title="Dashboard">
    <div class="row g-4 mb-4">
        @foreach ([
            ['label' => 'Revenue', 'value' => '$128.4K', 'icon' => 'bi-currency-dollar', 'tone' => 'primary'],
            ['label' => 'Orders', 'value' => '1,284', 'icon' => 'bi-bag-check', 'tone' => 'success'],
            ['label' => 'Customers', 'value' => '24.8K', 'icon' => 'bi-people', 'tone' => 'info'],
            ['label' => 'Inventory Alerts', 'value' => '18', 'icon' => 'bi-exclamation-triangle', 'tone' => 'warning'],
        ] as $metric)
            <div class="col-12 col-sm-6 col-xl-3">
                <x-admin.card class="h-100">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <div class="text-secondary small text-uppercase fw-semibold">{{ $metric['label'] }}</div>
                            <div class="display-6 fw-bold mb-0">{{ $metric['value'] }}</div>
                            <small class="text-success"><i class="bi bi-arrow-up-right"></i> UI placeholder</small>
                        </div>
                        <div class="metric-icon text-bg-{{ $metric['tone'] }}-subtle text-{{ $metric['tone'] }}">
                            <i class="bi {{ $metric['icon'] }}"></i>
                        </div>
                    </div>
                </x-admin.card>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <x-admin.card title="Recent Activity" subtitle="Reusable table component with static dashboard placeholders.">
                <x-admin.table :headers="['Reference', 'Module', 'Status', 'Owner']">
                    @foreach ([
                        ['#ORD-1042', 'Orders', 'Pending Review', 'Operations'],
                        ['#PRD-8301', 'Products', 'Draft Updated', 'Catalog'],
                        ['#SEO-2150', 'SEO', 'Audit Queued', 'Marketing'],
                        ['#LOG-7788', 'Logs', 'Notice', 'System'],
                    ] as $row)
                        <tr>
                            <td class="fw-semibold">{{ $row[0] }}</td>
                            <td>{{ $row[1] }}</td>
                            <td><span class="badge text-bg-primary-subtle text-primary border border-primary-subtle">{{ $row[2] }}</span></td>
                            <td class="text-secondary">{{ $row[3] }}</td>
                        </tr>
                    @endforeach
                </x-admin.table>
                <x-admin.pagination class="mt-3" label="Static pagination component preview" />
            </x-admin.card>
        </div>

        <div class="col-12 col-xl-4">
            <x-admin.card title="Quick Form" subtitle="Reusable form component preview only.">
                <x-admin.form submit="Preview Save">
                    <div class="col-12">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" placeholder="Placeholder input">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Status</label>
                        <select class="form-select">
                            <option>Draft</option>
                            <option>Active</option>
                            <option>Archived</option>
                        </select>
                    </div>
                </x-admin.form>
            </x-admin.card>

            <x-admin.card title="Modal Component" subtitle="Bootstrap modal shell for future workflows." class="mt-4">
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#adminPreviewModal">Open modal</button>
            </x-admin.card>
        </div>
    </div>

    <x-admin.modal id="adminPreviewModal" title="Reusable Modal Preview">
        <p class="text-secondary mb-0">This modal is UI-only and ready to wrap future admin workflows.</p>
        <x-slot:footer>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Confirm</button>
        </x-slot:footer>
    </x-admin.modal>
</x-layouts.admin>
