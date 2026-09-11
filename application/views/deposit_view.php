<style>
.proof-thumbnail {
    transition: all 0.2s ease-in-out;
}
.proof-thumbnail:hover {
    transform: scale(1.08);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}
.proof-thumbnail:hover .thumbnail-overlay {
    opacity: 1 !important;
}
.cursor-zoom-in {
    cursor: zoom-in;
}
.deposit-row-clickable {
    cursor: pointer;
    transition: background-color 0.15s ease-in-out;
}
.deposit-row-clickable:hover {
    background-color: rgba(212, 175, 55, 0.08) !important;
}
</style>

<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h3 class="fw-bold" style="color: var(--dark-sidebar);">Wallet Deposit Requests</h3>
        <p class="text-muted">Moderate deposit requests and verify payment proof receipts.</p>
    </div>
</div>

<!-- Search and Filter Form -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 8px;">
            <div class="card-body p-3">
                <form id="filterForm" class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" name="search" id="searchInput" class="form-control border-start-0" placeholder="Search by member name or email..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="status" id="statusFilter" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" <?php echo ($status === 'pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="approved" <?php echo ($status === 'approved') ? 'selected' : ''; ?>>Approved</option>
                            <option value="rejected" <?php echo ($status === 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="resetBtn" class="btn btn-secondary w-100">
                            Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Deposit Requests Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div id="table-container">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="text-white" style="background-color: var(--dark-sidebar); border-bottom: 3px solid var(--primary-gold);">
                            <tr>
                                <th class="ps-4" style="width: 100px;">#</th>
                                <th>Member</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Payment Proof</th>
                                <th>User Remark</th>
                                <th>Status</th>
                                <th>Requested At</th>
                                <th class="text-end pe-4" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($requests)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-receipt d-block fs-1 mb-3 text-secondary"></i>
                                        No deposit requests found matching the filters.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php 
                                $index_num = ($current_page - 1) * 10 + 1;
                                foreach ($requests as $req): 
                                ?>
                                    <tr class="deposit-row-clickable" data-id="<?php echo $req->id; ?>">
                                        <td class="ps-4 fw-semibold text-muted"><?php echo $index_num++; ?></td>
                                        <td>
                                            <div class="fw-semibold text-dark"><?php echo htmlspecialchars($req->user_name ?? 'Member'); ?></div>
                                            <?php if (!empty($req->user_email)): ?>
                                                <span class="text-muted small"><?php echo htmlspecialchars($req->user_email); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-muted border px-1.5 py-0.5" style="font-size: 0.68rem; font-weight: 500;"><i class="fa-regular fa-envelope-open me-1 opacity-50"></i>Not provided</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success fs-6">₹<?php echo number_format($req->amount, 2); ?></span>
                                        </td>
                                        <td>
                                            <?php if ($req->payment_method === 'online'): ?>
                                                <span class="badge bg-info-subtle text-info px-2.5 py-1 border border-info-subtle" style="font-size: 0.75rem;">Online Transfer</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1 border border-secondary-subtle" style="font-size: 0.75rem;">Cash</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($req->payment_method === 'online' && $req->proof_file): ?>
                                                <?php 
                                                $ext = strtolower(pathinfo($req->proof_file, PATHINFO_EXTENSION));
                                                if ($ext === 'pdf'): 
                                                ?>
                                                    <a href="<?php echo base_url($req->proof_file); ?>" target="_blank" class="btn btn-xs btn-outline-danger px-2.5 py-1.5 d-inline-flex align-items-center gap-1.5 shadow-sm" style="font-size: 0.75rem; border-radius: 6px; font-weight: 500;">
                                                        <i class="fa-solid fa-file-pdf fs-6"></i> View PDF
                                                    </a>
                                                <?php else: ?>
                                                    <div class="proof-thumbnail position-relative d-inline-block rounded border shadow-sm cursor-zoom-in" style="width: 50px; height: 50px; overflow: hidden; border: 2px solid var(--primary-gold) !important;" onclick="viewReceipt('<?php echo base_url($req->proof_file); ?>')">
                                                        <img src="<?php echo base_url($req->proof_file); ?>" alt="Proof Thumbnail" class="w-100 h-100" style="object-fit: cover;">
                                                        <div class="thumbnail-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0" style="transition: opacity 0.2s;">
                                                            <i class="fa-solid fa-magnifying-glass-plus text-white" style="font-size: 0.85rem;"></i>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge bg-light text-dark px-3 py-1.5 border" style="border-radius: 50px; font-size: 0.75rem; border-color: #dee2e6 !important; font-weight: 500;">
                                                    <i class="fa-solid fa-money-bill-transfer text-muted me-1"></i> N/A (Cash)
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="text-dark small"><?php echo htmlspecialchars($req->remark ?: '-'); ?></span>
                                        </td>
                                        <td>
                                            <?php if ($req->status === 'approved'): ?>
                                                <span class="badge bg-success-subtle text-success px-2.5 py-1 border border-success-subtle" style="border-radius: 4px; font-size: 0.75rem;">Approved</span>
                                            <?php elseif ($req->status === 'rejected'): ?>
                                                <span class="badge bg-danger-subtle text-danger px-2.5 py-1 border border-danger-subtle" style="border-radius: 4px; font-size: 0.75rem;">Rejected</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning-subtle text-warning px-2.5 py-1 border border-warning-subtle" style="border-radius: 4px; font-size: 0.75rem;">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted small"><?php echo date('M d, Y H:i', strtotime($req->created_at)); ?></td>
                                        <td class="text-end pe-4">
                                            <div class="d-inline-flex gap-2">
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-primary view-details-btn px-2.5 py-1.5" 
                                                        style="border-radius: 6px;" 
                                                        title="View Details"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#depositDetailsModal"
                                                        data-id="<?php echo $req->id; ?>"
                                                        data-member-name="<?php echo htmlspecialchars($req->user_name ?? 'Member'); ?>"
                                                        data-member-email="<?php echo htmlspecialchars($req->user_email ?? 'Not provided'); ?>"
                                                        data-member-phone="<?php echo htmlspecialchars($req->user_phone ?? ''); ?>"
                                                        data-amount="<?php echo number_format($req->amount, 2); ?>"
                                                        data-method="<?php echo htmlspecialchars($req->payment_method ?? ''); ?>"
                                                        data-remark="<?php echo htmlspecialchars($req->remark ?: 'No remark provided'); ?>"
                                                        data-status="<?php echo htmlspecialchars($req->status ?? 'pending'); ?>"
                                                        data-proof-file="<?php echo $req->proof_file ? base_url($req->proof_file) : ''; ?>"
                                                        data-requested-at="<?php echo date('M d, Y H:i', strtotime($req->created_at)); ?>">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <?php if ($req->status === 'pending'): ?>
                                                    <form action="<?php echo base_url('admin/deposits/approve/' . $req->id); ?>" method="POST" class="approve-form d-inline-block m-0">
                                                        <button type="button" class="btn btn-sm btn-outline-success action-btn px-2.5 py-1.5" data-action="approve" style="border-radius: 6px;" title="Approve Request">
                                                            <i class="fa-solid fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="<?php echo base_url('admin/deposits/reject/' . $req->id); ?>" method="POST" class="reject-form d-inline-block m-0">
                                                        <button type="button" class="btn btn-sm btn-outline-danger action-btn px-2.5 py-1.5" data-action="reject" style="border-radius: 6px;" title="Reject Request">
                                                            <i class="fa-solid fa-xmark"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination footer inside container -->
                <?php if (!empty($total_rows) && $total_rows > 0): 
                    $start_record = ($current_page - 1) * 10 + 1;
                    $end_record = min($current_page * 10, $total_rows);
                ?>
                    <div class="card-footer bg-white border-top p-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                        <div class="text-muted small">
                            Showing <strong><?php echo $start_record; ?></strong> to <strong><?php echo $end_record; ?></strong> of <strong><?php echo number_format($total_rows); ?></strong> available deposit requests
                        </div>
                        <?php if ($total_pages > 1): ?>
                        <nav aria-label="Deposit Request Page Navigation">
                            <ul class="pagination pagination-sm justify-content-center mb-0">
                                <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="#" data-page="<?php echo $current_page - 1; ?>">&laquo; Prev</a>
                                </li>
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?php echo ($current_page == $i) ? 'active' : ''; ?>">
                                        <a class="page-link" href="#" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="#" data-page="<?php echo $current_page + 1; ?>">Next &raquo;</a>
                                </li>
                            </ul>
                        </nav>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Deposit Details Modal -->
<div class="modal fade" id="depositDetailsModal" tabindex="-1" aria-labelledby="depositDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header text-white p-4" style="background: linear-gradient(135deg, var(--dark-sidebar) 0%, #1f2937 100%); border-bottom: 3px solid var(--primary-gold); justify-content: space-between;">
                <h5 class="modal-title fw-bold" id="depositDetailsModalLabel">
                    <i class="fa-solid fa-receipt text-warning me-2"></i> Deposit Request Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0" style="font-size: 0.95rem;">
                        <tbody>
                            <tr class="border-bottom">
                                <td class="fw-semibold text-muted py-2.5" style="width: 150px;">Member</td>
                                <td class="py-2.5">
                                    <div class="fw-bold text-dark" id="modalMemberName"></div>
                                    <div class="text-muted small" id="modalMemberEmail"></div>
                                    <div class="text-muted small" id="modalMemberPhone"></div>
                                </td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="fw-semibold text-muted py-2.5">Amount</td>
                                <td class="py-2.5">
                                    <span class="fw-bold text-success fs-5" id="modalAmount"></span>
                                </td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="fw-semibold text-muted py-2.5">Payment Method</td>
                                <td class="py-2.5" id="modalMethod"></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="fw-semibold text-muted py-2.5">Status</td>
                                <td class="py-2.5" id="modalStatus"></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="fw-semibold text-muted py-2.5">Requested At</td>
                                <td class="text-muted py-2.5" id="modalRequestedAt"></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="fw-semibold text-muted py-2.5" valign="top">User Remark</td>
                                <td class="py-2.5">
                                    <div class="text-dark bg-light p-3 rounded border-start" style="font-size: 0.9rem; line-height: 1.5; border-left: 3px solid var(--primary-pink) !important;" id="modalRemark"></div>
                                </td>
                            </tr>
                            <tr id="modalProofRow">
                                <td class="fw-semibold text-muted py-2.5" valign="top">Payment Proof</td>
                                <td class="py-2.5" id="modalProofContainer"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light p-3 border-0 justify-content-end gap-2" id="modalFooterActions">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- AJAX Swapper and Event Delegation Binds -->
<script>
function viewReceipt(url) {
    Swal.fire({
        title: 'Payment Proof Receipt',
        imageUrl: url,
        imageAlt: 'Receipt Payment Proof',
        showCloseButton: true,
        showConfirmButton: false,
        width: '560px',
        padding: '1.5rem',
        customClass: {
            image: 'rounded-3 border border-secondary shadow-lg img-fluid my-2'
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const resetBtn = document.getElementById('resetBtn');
    let debounceTimer;

    function loadTable(page = 1) {
        const search = searchInput.value;
        const status = statusFilter.value;
        const url = new URL(window.location.href);

        url.searchParams.set('search', search);
        url.searchParams.set('status', status);
        url.searchParams.set('page', page);

        window.history.pushState({}, '', url.toString());

        fetch(url.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTable = doc.getElementById('table-container');
            if (newTable) {
                document.getElementById('table-container').innerHTML = newTable.innerHTML;
            }
        })
        .catch(err => console.error('Error fetching table data: ', err));
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            loadTable(1);
        }, 300);
    });

    statusFilter.addEventListener('change', function() {
        loadTable(1);
    });

    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        statusFilter.value = '';
        loadTable(1);
    });

    document.addEventListener('click', function(e) {
        // Handle pagination link clicks
        const pageLink = e.target.closest('#table-container .pagination .page-link');
        if (pageLink) {
            e.preventDefault();
            const page = pageLink.getAttribute('data-page');
            if (page) {
                loadTable(page);
            }
        }

        // Handle table row click to open details modal
        const depositRow = e.target.closest('.deposit-row-clickable');
        if (depositRow && !e.target.closest('.action-btn') && !e.target.closest('a') && !e.target.closest('button') && !e.target.closest('.proof-thumbnail')) {
            const rowBtn = depositRow.querySelector('.view-details-btn');
            if (rowBtn) {
                rowBtn.click();
                return;
            }
        }

        // Handle view details button clicks (Event Delegation)
        const viewBtn = e.target.closest('.view-details-btn');
        if (viewBtn) {
            e.preventDefault();
            const id = viewBtn.getAttribute('data-id');
            const name = viewBtn.getAttribute('data-member-name');
            const email = viewBtn.getAttribute('data-member-email');
            const phone = viewBtn.getAttribute('data-member-phone');
            const amount = viewBtn.getAttribute('data-amount');
            const method = viewBtn.getAttribute('data-method');
            const remark = viewBtn.getAttribute('data-remark');
            const status = viewBtn.getAttribute('data-status');
            const proof = viewBtn.getAttribute('data-proof-file');
            const requestedAt = viewBtn.getAttribute('data-requested-at');

            document.getElementById('modalMemberName').textContent = name;
            document.getElementById('modalMemberEmail').textContent = email;
            const phoneEl = document.getElementById('modalMemberPhone');
            if (phoneEl) {
                phoneEl.textContent = phone ? ('📞 ' + phone) : '';
            }
            document.getElementById('modalAmount').textContent = '₹' + amount;
            
            // Method badge
            let methodHtml = '';
            if (method === 'online') {
                methodHtml = `<span class="badge bg-info-subtle text-info px-2.5 py-1 border border-info-subtle" style="font-size: 0.75rem;">Online Transfer</span>`;
            } else {
                methodHtml = `<span class="badge bg-light text-dark px-3 py-1.5 border" style="border-radius: 50px; font-size: 0.75rem; border-color: #dee2e6 !important; font-weight: 500;"><i class="fa-solid fa-money-bill-transfer text-muted me-1"></i> N/A (Cash)</span>`;
            }
            document.getElementById('modalMethod').innerHTML = methodHtml;

            // Status badge
            let statusHtml = '';
            if (status === 'approved') {
                statusHtml = `<span class="badge bg-success-subtle text-success px-2.5 py-1 border border-success-subtle" style="border-radius: 4px; font-size: 0.75rem;">Approved</span>`;
            } else if (status === 'rejected') {
                statusHtml = `<span class="badge bg-danger-subtle text-danger px-2.5 py-1 border border-danger-subtle" style="border-radius: 4px; font-size: 0.75rem;">Rejected</span>`;
            } else {
                statusHtml = `<span class="badge bg-warning-subtle text-warning px-2.5 py-1 border border-warning-subtle" style="border-radius: 4px; font-size: 0.75rem;">Pending</span>`;
            }
            document.getElementById('modalStatus').innerHTML = statusHtml;

            document.getElementById('modalRequestedAt').textContent = requestedAt;
            document.getElementById('modalRemark').textContent = remark;

            // Proof handling
            const proofContainer = document.getElementById('modalProofContainer');
            const proofRow = document.getElementById('modalProofRow');
            if (proof) {
                proofRow.style.display = 'table-row';
                const ext = proof.split('.').pop().toLowerCase();
                if (ext === 'pdf') {
                    proofContainer.innerHTML = `
                        <a href="${proof}" target="_blank" class="btn btn-sm btn-outline-danger px-3 py-2 d-inline-flex align-items-center gap-1.5 shadow-sm" style="border-radius: 6px; font-weight: 500;">
                            <i class="fa-solid fa-file-pdf fs-5"></i> View PDF Receipt
                        </a>
                    `;
                } else {
                    proofContainer.innerHTML = `
                        <div class="proof-thumbnail position-relative d-inline-block rounded border shadow-sm cursor-zoom-in" style="width: 100px; height: 100px; overflow: hidden; border: 2px solid var(--primary-gold) !important; transition: all 0.2s ease-in-out;" onclick="viewReceipt('${proof}')">
                            <img src="${proof}" alt="Proof Receipt" class="w-100 h-100" style="object-fit: cover;">
                            <div class="thumbnail-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0" style="transition: opacity 0.2s;">
                                <i class="fa-solid fa-magnifying-glass-plus text-white fs-5"></i>
                            </div>
                        </div>
                    `;
                }
            } else {
                proofContainer.innerHTML = `<span class="badge bg-light text-dark px-3 py-1.5 border" style="border-radius: 50px; font-size: 0.75rem; border-color: #dee2e6 !important; font-weight: 500;"><i class="fa-solid fa-money-bill-transfer text-muted me-1"></i> N/A (Cash)</span>`;
            }

            // Footer Actions (Approve / Reject buttons if pending)
            const footerActions = document.getElementById('modalFooterActions');
            let footerHtml = `<button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius: 6px;">Close</button>`;
            if (status === 'pending') {
                footerHtml = `
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius: 6px;">Close</button>
                    <form action="<?php echo base_url('admin/deposits/reject/'); ?>${id}" method="POST" class="reject-form d-inline-block m-0">
                        <button type="button" class="btn btn-danger action-btn px-4" data-action="reject" style="border-radius: 6px;">
                            <i class="fa-solid fa-xmark me-1"></i> Reject
                        </button>
                    </form>
                    <form action="<?php echo base_url('admin/deposits/approve/'); ?>${id}" method="POST" class="approve-form d-inline-block m-0">
                        <button type="button" class="btn btn-success action-btn px-4" data-action="approve" style="border-radius: 6px;">
                            <i class="fa-solid fa-check me-1"></i> Approve
                        </button>
                    </form>
                `;
            }
            footerActions.innerHTML = footerHtml;
        }

        // Handle approve/reject form submissions (Event Delegation)
        const actionBtn = e.target.closest('.action-btn');
        if (actionBtn) {
            e.preventDefault();
            const form = actionBtn.closest('form');
            const action = actionBtn.getAttribute('data-action');

            let titleStr = "Approve Deposit Request?";
            let textStr = "This will credit the requested amount to the member wallet and cannot be undone.";
            let confirmBtnColor = "#198754";

            if (action === 'reject') {
                titleStr = "Reject Deposit Request?";
                textStr = "This request will be marked as rejected. No money will be loaded.";
                confirmBtnColor = "#dc3545";
            }

            // If we are currently showing details modal, hide it before sweetalert
            const modalEl = document.getElementById('depositDetailsModal');
            const bootstrapModal = bootstrap.Modal.getInstance(modalEl);
            
            dsConfirm({
                title: titleStr,
                text: textStr,
                icon: (action === 'reject') ? 'warning' : 'question',
                confirmText: (action === 'reject') ? 'Yes, Reject' : 'Yes, Approve',
                isDangerous: (action === 'reject'),
                onConfirm: function() {
                    form.submit();
                },
                onCancel: function() {
                    if (bootstrapModal) {
                        bootstrapModal.show();
                    }
                }
            });
        }
    });
});
</script>
