<!-- ============================================================== -->
<!-- Category Management View — Redesigned (Single Page)           -->
<!-- ============================================================== -->

<style>
    /* ================= Design Tokens ================= */
    :root {
        --cat-gold: #d4af37;
        --cat-gold-dark: #b8942a;
        --cat-gold-light: #f3e2ab;
        --cat-pink: #ec407a;
        --cat-pink-dark: #d81b60;
        --cat-ink: #14181f;
        --cat-ink-soft: #2a3040;
        --cat-muted: #6b7280;
        --cat-border: #e8e9ee;
        --cat-surface: #ffffff;
        --cat-bg: #f7f7fb;
        --cat-success: #10b981;
        --cat-success-bg: #ecfdf5;
        --cat-success-brd: #a7f3d0;
        --cat-danger: #ef4444;
        --cat-danger-bg: #fef2f2;
        --cat-danger-brd: #fecaca;
        --cat-radius-lg: 18px;
        --cat-radius-md: 14px;
        --cat-radius-sm: 10px;
        --cat-shadow-sm: 0 1px 2px rgba(20, 24, 31, 0.04), 0 1px 1px rgba(20, 24, 31, 0.03);
        --cat-shadow-md: 0 8px 24px -8px rgba(20, 24, 31, 0.12);
        --cat-shadow-lg: 0 24px 48px -12px rgba(20, 24, 31, 0.22);
    }

    #categoriesPage {
        background: var(--cat-bg);
        border-radius: 20px;
        padding: 4px;
    }

    #categoriesPage * {
        box-sizing: border-box;
    }

    /* ================= Header ================= */
    .cat-page-head {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: flex-start;
        justify-content: space-between;
        padding: 4px 4px 24px 4px;
    }

    .cat-eyebrow {
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--cat-gold-dark);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cat-eyebrow::before {
        content: "";
        width: 18px;
        height: 2px;
        background: var(--cat-gold);
        display: inline-block;
        border-radius: 2px;
    }

    .cat-header-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        color: var(--cat-ink);
        letter-spacing: -0.5px;
        font-size: 1.9rem;
        margin: 0;
    }

    .cat-header-sub {
        color: var(--cat-muted);
        font-size: 0.92rem;
        margin-top: 6px;
        max-width: 480px;
    }

    .btn-cat-gradient {
        background: linear-gradient(135deg, var(--cat-pink) 0%, var(--cat-gold) 100%);
        color: #fff !important;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.92rem;
        padding: 12px 22px;
        box-shadow: 0 8px 20px -6px rgba(236, 64, 122, 0.45);
        transition: transform 0.18s ease, box-shadow 0.18s ease, opacity 0.18s ease;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-cat-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px -6px rgba(236, 64, 122, 0.5);
        color: #fff !important;
    }

    .btn-cat-gradient:active {
        transform: translateY(0);
    }

    /* ================= Stat Cards ================= */
    .cat-stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 22px;
    }

    @media (max-width: 767px) {
        .cat-stats-row {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
    }

    .cat-stat-card {
        border-radius: var(--cat-radius-md);
        border: 1px solid var(--cat-border);
        background: var(--cat-surface);
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        box-shadow: var(--cat-shadow-sm);
        border-left: 3px solid var(--stat-accent, var(--cat-border));
    }

    .cat-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--cat-shadow-md);
    }

    .cat-stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .cat-stat-label {
        color: var(--cat-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.68rem;
    }

    .cat-stat-value {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--cat-ink);
        margin-top: 1px;
        letter-spacing: -0.5px;
        line-height: 1.2;
    }

    /* ================= Toolbar ================= */
    .cat-toolbar {
        border-radius: var(--cat-radius-md);
        border: 1px solid var(--cat-border);
        background: var(--cat-surface);
        padding: 14px 16px;
        margin-bottom: 18px;
        box-shadow: var(--cat-shadow-sm);
    }

    .cat-toolbar .input-group-text {
        border-right: none;
        background: var(--cat-surface);
        color: var(--cat-muted);
    }

    .cat-toolbar .form-control,
    .cat-toolbar .form-select {
        border-color: var(--cat-border);
        font-size: 0.9rem;
        padding: 10px 12px;
    }

    .cat-toolbar .form-control:focus,
    .cat-toolbar .form-select:focus {
        border-color: var(--cat-gold);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
    }

    .cat-toolbar input.form-control {
        border-left: none;
        padding-left: 4px;
    }

    .btn-cat-reset {
        border: 1px solid var(--cat-border);
        background: var(--cat-surface);
        color: var(--cat-ink-soft);
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.86rem;
        transition: all 0.15s ease;
    }

    .btn-cat-reset:hover {
        background: #f4f4f7;
        border-color: #d8d9e0;
        color: var(--cat-ink);
    }

    .cat-found-badge {
        font-size: 0.82rem;
        color: var(--cat-muted);
        font-weight: 500;
    }

    /* ================= Table Card ================= */
    .cat-table-wrapper {
        border-radius: var(--cat-radius-lg);
        border: 1px solid var(--cat-border);
        background: var(--cat-surface);
        overflow: hidden;
        box-shadow: var(--cat-shadow-sm);
    }

    .cat-table {
        margin-bottom: 0;
    }

    .cat-table thead th {
        background: var(--cat-ink);
        color: #fff;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        font-weight: 700;
        border: none;
        padding: 14px 16px;
        vertical-align: middle;
    }

    .cat-table thead tr {
        box-shadow: inset 0 -3px 0 var(--cat-gold);
    }

    .cat-table tbody tr {
        border-bottom: 1px solid var(--cat-border);
        transition: background 0.15s ease;
    }

    .cat-table tbody tr:last-child {
        border-bottom: none;
    }

    .cat-table tbody tr:hover {
        background: #fbfaf6;
    }

    .cat-table td {
        padding: 14px 16px;
        vertical-align: middle;
        border: none;
    }

    .cat-img-thumb {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid var(--cat-gold-light);
        transition: transform 0.2s ease, border-color 0.2s ease;
        background-color: #f8fafc;
    }

    .cat-img-thumb:hover {
        transform: scale(1.12);
        border-color: var(--cat-gold);
    }

    .cat-name {
        font-weight: 700;
        color: var(--cat-ink);
        font-size: 0.96rem;
        margin-bottom: 3px;
    }

    .cat-slug-badge {
        font-family: 'SFMono-Regular', Consolas, monospace;
        font-size: 0.72rem;
        background: #f1f2f6;
        color: #5b5f6d;
        padding: 3px 9px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .cat-count-pill {
        background: #f4f4f8;
        color: var(--cat-ink-soft);
        border: 1px solid var(--cat-border);
        padding: 5px 11px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.78rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .cat-date-primary {
        color: var(--cat-ink-soft);
        font-size: 0.84rem;
        font-weight: 500;
    }

    .cat-date-secondary {
        color: var(--cat-muted);
        font-size: 0.72rem;
    }

    /* Status badge (toggle) */
    .cat-status-badge {
        cursor: pointer;
        padding: 7px 14px;
        border-radius: 30px;
        font-size: 0.76rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.18s ease;
        border-width: 1px;
        border-style: solid;
        text-decoration: none;
    }

    .cat-status-badge.active {
        background: var(--cat-success-bg);
        color: #047857;
        border-color: var(--cat-success-brd);
    }

    .cat-status-badge.active:hover {
        background: #d1fae5;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
    }

    .cat-status-badge.inactive {
        background: var(--cat-danger-bg);
        color: #b91c1c;
        border-color: var(--cat-danger-brd);
    }

    .cat-status-badge.inactive:hover {
        background: #fee2e2;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12);
    }

    /* Row action buttons */
    .cat-row-actions {
        display: flex;
        gap: 6px;
        justify-content: flex-end;
    }

    .btn-cat-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--cat-border);
        background: var(--cat-surface);
        color: var(--cat-muted);
        transition: all 0.15s ease;
    }

    .btn-cat-icon.edit:hover {
        border-color: var(--cat-gold);
        color: var(--cat-gold-dark);
        background: #fffaf0;
    }

    .btn-cat-icon.delete:hover {
        border-color: var(--cat-danger);
        color: var(--cat-danger);
        background: var(--cat-danger-bg);
    }

    /* Empty state */
    .cat-empty-state {
        padding: 56px 20px;
        text-align: center;
    }

    .cat-empty-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #f4f4f8;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px auto;
        font-size: 1.5rem;
        color: var(--cat-muted);
    }

    /* ================= Mobile Cards ================= */
    .cat-mobile-card {
        border-radius: var(--cat-radius-md);
        border: 1px solid var(--cat-border);
        background: var(--cat-surface);
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: var(--cat-shadow-sm);
        transition: box-shadow 0.15s ease;
    }

    .cat-mobile-card:active {
        box-shadow: var(--cat-shadow-md);
    }

    .cat-mobile-top {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .cat-mobile-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px dashed var(--cat-border);
        padding-top: 10px;
        margin-top: 12px;
        color: var(--cat-muted);
        font-size: 0.8rem;
    }

    .cat-mobile-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid var(--cat-border);
    }

    .btn-cat-mobile-edit {
        flex: 1;
        border-radius: 10px;
        border: 1px solid var(--cat-border);
        background: #fbfaf6;
        color: var(--cat-ink-soft);
        font-weight: 600;
        font-size: 0.86rem;
        padding: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-cat-mobile-delete {
        width: 44px;
        border-radius: 10px;
        border: 1px solid var(--cat-danger-brd);
        background: var(--cat-danger-bg);
        color: var(--cat-danger);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* ================= Pagination Footer ================= */
    .cat-footer {
        background: var(--cat-surface);
        border-top: 1px solid var(--cat-border);
        padding: 16px 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
    }

    .cat-footer-info {
        color: var(--cat-muted);
        font-size: 0.85rem;
    }

    .cat-pagination {
        display: flex;
        gap: 4px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .cat-pagination .page-link {
        border: 1px solid var(--cat-border);
        border-radius: 8px;
        color: var(--cat-ink-soft);
        font-weight: 600;
        font-size: 0.84rem;
        padding: 7px 12px;
        transition: all 0.15s ease;
    }

    .cat-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--cat-pink) 0%, var(--cat-gold) 100%);
        border-color: transparent;
        color: #fff;
    }

    .cat-pagination .page-item.disabled .page-link {
        opacity: 0.45;
    }

    .cat-pagination .page-link:hover:not(.disabled) {
        border-color: var(--cat-gold);
        background: #fffaf0;
    }

    /* ================= Modals ================= */
    .cat-modal .modal-dialog {
        max-width: 440px;
    }

    .cat-modal .modal-content {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: var(--cat-shadow-lg);
    }

    .cat-modal-header {
        background: linear-gradient(135deg, var(--cat-ink) 0%, var(--cat-ink-soft) 100%);
        position: relative;
        color: #fff;
        padding: 16px 20px;
        border: none;
    }

    .cat-modal-header::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--cat-pink), var(--cat-gold));
    }

    .cat-modal-icon-badge {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.12);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--cat-gold-light);
        font-size: 0.92rem;
        margin-right: 10px;
        flex-shrink: 0;
    }

    .cat-modal-title {
        font-weight: 700;
        font-size: 1rem;
        margin: 0;
    }

    .cat-modal-subtitle {
        font-size: 0.74rem;
        color: rgba(255, 255, 255, 0.6);
        margin-top: 1px;
    }

    .cat-modal-header .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
        opacity: 0.7;
        font-size: 0.78rem;
    }

    .cat-modal-header .btn-close:hover {
        opacity: 1;
    }

    .cat-id-chip {
        background: rgba(212, 175, 55, 0.18);
        color: var(--cat-gold-light);
        border: 1px solid rgba(212, 175, 55, 0.35);
        font-size: 0.66rem;
        font-weight: 700;
        padding: 2px 9px;
        border-radius: 20px;
        margin-left: 8px;
    }

    .cat-modal-body {
        padding: 20px;
        background: #fdfdfd;
    }

    .cat-field-label {
        font-weight: 700;
        color: var(--cat-ink);
        font-size: 0.82rem;
        margin-bottom: 7px;
        display: block;
    }

    /* Upload zone — compact horizontal layout */
    .cat-upload-zone {
        border: 1.5px dashed #d7d9e2;
        border-radius: 12px;
        background: #fafafc;
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: border-color 0.2s, background-color 0.2s;
    }

    .cat-upload-zone:hover,
    .cat-upload-zone.dragover {
        border-color: var(--cat-gold);
        background: #fffdf6;
    }

    .cat-preview-box {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid var(--cat-gold);
        background: #ffffff;
        box-shadow: 0 3px 8px rgba(212, 175, 55, 0.18);
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        flex-shrink: 0;
    }

    .cat-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cat-upload-body {
        flex: 1;
        min-width: 0;
        text-align: left;
    }

    .btn-cat-upload {
        border: 1px solid var(--cat-ink);
        background: var(--cat-ink);
        color: #fff;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.78rem;
        padding: 6px 12px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }

    .btn-cat-upload:hover {
        background: var(--cat-ink-soft);
    }

    .cat-upload-hint {
        color: var(--cat-muted);
        font-size: 0.68rem;
        display: block;
        margin-top: 6px;
        line-height: 1.35;
    }

    .cat-clear-link {
        font-size: 0.76rem;
        color: var(--cat-danger);
        text-decoration: none;
        font-weight: 600;
        background: none;
        border: none;
        padding: 8px 4px;
    }

    .cat-clear-link:hover {
        text-decoration: underline;
    }

    /* Name field */
    .cat-modal-body .input-group-text {
        background: #fff;
        border-color: var(--cat-border);
        color: var(--cat-gold-dark);
    }

    .cat-modal-body .form-control {
        border-color: var(--cat-border);
        padding: 9px 12px;
        font-size: 0.9rem;
    }

    .cat-modal-body .form-control:focus {
        border-color: var(--cat-gold);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
    }

    .cat-modal-body .mb-4 {
        margin-bottom: 16px !important;
    }

    .cat-slug-preview {
        font-size: 0.74rem;
        color: var(--cat-muted);
        margin-top: 7px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .cat-slug-preview code {
        background: #f1f2f6;
        color: var(--cat-ink-soft);
        padding: 1px 7px;
        border-radius: 6px;
        font-size: 0.72rem;
    }

    /* Segmented Status Toggle */
    .cat-seg-toggle {
        display: flex;
        border-radius: 10px;
        background: #f1f2f6;
        padding: 4px;
        gap: 4px;
    }

    .cat-seg-toggle input[type="radio"] {
        display: none;
    }

    .cat-seg-toggle label {
        flex: 1;
        text-align: center;
        padding: 8px 10px;
        font-size: 0.82rem;
        font-weight: 700;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
        margin: 0;
        color: #7a7e8c;
    }

    .cat-seg-toggle input[type="radio"]:checked+label.seg-active {
        background: var(--cat-success);
        color: #ffffff;
        box-shadow: 0 3px 8px rgba(16, 185, 129, 0.3);
    }

    .cat-seg-toggle input[type="radio"]:checked+label.seg-inactive {
        background: var(--cat-danger);
        color: #ffffff;
        box-shadow: 0 3px 8px rgba(239, 68, 68, 0.3);
    }

    .cat-modal-footer {
        background: #fdfdfd;
        border-top: 1px solid var(--cat-border);
        padding: 14px 20px;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .btn-cat-cancel {
        border: 1px solid var(--cat-border);
        background: #fff;
        color: var(--cat-ink-soft);
        border-radius: 10px;
        font-weight: 600;
        padding: 9px 16px;
        font-size: 0.86rem;
    }

    .btn-cat-cancel:hover {
        background: #f4f4f7;
    }

    .cat-modal .btn-cat-gradient {
        padding: 9px 18px;
        font-size: 0.86rem;
    }

    @media (max-width: 575px) {
        .cat-modal-body {
            padding: 16px;
        }

        .cat-modal-header {
            padding: 14px 16px;
        }

        .cat-modal-footer {
            padding: 12px 16px;
        }

        .cat-header-title {
            font-size: 1.5rem;
        }
    }
</style>

<div id="categoriesPage">

    <!-- ============================================================== -->
    <!-- 1. Header Bar -->
    <!-- ============================================================== -->
    <div class="cat-page-head">
        <div>
            <div class="cat-eyebrow">Catalog Management</div>
            <h3 class="cat-header-title">Categories</h3>
            <p class="cat-header-sub">Manage catalog categories, imagery, and product associations.</p>
        </div>
        <button type="button" class="btn-cat-gradient" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fa-solid fa-plus"></i> Add New Category
        </button>
    </div>

    <!-- ============================================================== -->
    <!-- 2. Metric KPI Cards -->
    <!-- ============================================================== -->
    <div class="cat-stats-row">
        <div class="cat-stat-card" style="--stat-accent: #d4af37;">
            <div class="cat-stat-icon" style="background: rgba(212, 175, 55, 0.14); color: #b8942a;">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div>
                <div class="cat-stat-label">Total Categories</div>
                <div class="cat-stat-value"><?php echo number_format($stats_total ?? 0); ?></div>
            </div>
        </div>

        <div class="cat-stat-card" style="--stat-accent: #10b981;">
            <div class="cat-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <div class="cat-stat-label">Active Categories</div>
                <div class="cat-stat-value" style="color:#059669;"><?php echo number_format($stats_active ?? 0); ?></div>
            </div>
        </div>

        <div class="cat-stat-card" style="--stat-accent: #ef4444;">
            <div class="cat-stat-icon" style="background: rgba(239, 68, 68, 0.12); color: #dc2626;">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <div>
                <div class="cat-stat-label">Inactive Categories</div>
                <div class="cat-stat-value" style="color:#dc2626;"><?php echo number_format($stats_inactive ?? 0); ?></div>
            </div>
        </div>

        <div class="cat-stat-card" style="--stat-accent: #2563eb;">
            <div class="cat-stat-icon" style="background: rgba(37, 99, 235, 0.12); color: #2563EB;">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div>
                <div class="cat-stat-label">Total Products</div>
                <div class="cat-stat-value" style="color:#2563EB;"><?php echo number_format($stats_products ?? 0); ?></div>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 3. Search & Filter Bar -->
    <!-- ============================================================== -->
    <div class="cat-toolbar">
        <form id="filterForm" class="row g-2 align-items-center" onsubmit="return false;">
            <div class="col-12 col-md-6 col-lg-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search by name or slug..." value="<?php echo htmlspecialchars($search ?? ''); ?>" autocomplete="off">
                    <?php if (!empty($search)): ?>
                        <button class="btn btn-outline-secondary border-start-0" type="button" id="clearSearchBtn" style="border-color: var(--cat-border);"><i class="fa-solid fa-xmark text-muted"></i></button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-3">
                <select name="status" id="statusFilter" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="1" <?php echo ($status === '1') ? 'selected' : ''; ?>>Active</option>
                    <option value="0" <?php echo ($status === '0') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <button type="button" id="resetBtn" class="btn btn-cat-reset w-100">
                    <i class="fa-solid fa-rotate-left me-1"></i> Reset
                </button>
            </div>
            <div class="col-12 col-lg-2 text-lg-end cat-found-badge d-none d-lg-block">
                <span id="filterCountBadge"><?php echo number_format($total_rows ?? 0); ?> found</span>
            </div>
        </form>
    </div>

    <!-- ============================================================== -->
    <!-- 4. Dynamic Categories Container (Desktop Table + Mobile Cards) -->
    <!-- ============================================================== -->
    <div class="cat-table-wrapper" id="table-container">

        <!-- Desktop Table View (≥ 768px) -->
        <div class="d-none d-md-block table-responsive">
            <table class="table cat-table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th style="width: 76px;">Image</th>
                        <th>Category Details</th>
                        <th style="width: 140px;">Products</th>
                        <th style="width: 140px;">Status</th>
                        <th style="width: 170px;">Created Date</th>
                        <th class="text-end" style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="7">
                                <div class="cat-empty-state">
                                    <div class="cat-empty-icon"><i class="fa-regular fa-folder-open"></i></div>
                                    <h6 class="fw-bold text-dark mb-1">No Categories Found</h6>
                                    <p class="text-muted small mb-3">Try adjusting your search criteria or create a new category.</p>
                                    <button type="button" class="btn-cat-gradient" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                        <i class="fa-solid fa-plus"></i> Create First Category
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php
                        $index_num = ($current_page - 1) * 10 + 1;
                        foreach ($categories as $cat):
                            $cat_img = $cat->image ? base_url($cat->image) : 'https://placehold.co/100x100/111827/d4af37?text=' . urlencode(substr($cat->name, 0, 1));
                        ?>
                            <tr>
                                <td class="fw-semibold text-muted small"><?php echo $index_num++; ?></td>
                                <td>
                                    <img src="<?php echo $cat_img; ?>"
                                        alt="<?php echo htmlspecialchars($cat->name); ?>"
                                        class="cat-img-thumb"
                                        loading="lazy">
                                </td>
                                <td>
                                    <div class="cat-name"><?php echo htmlspecialchars($cat->name); ?></div>
                                    <span class="cat-slug-badge">
                                        <i class="fa-solid fa-link opacity-75"></i><?php echo htmlspecialchars($cat->slug ?: url_title($cat->name, 'dash', TRUE)); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="cat-count-pill">
                                        <i class="fa-solid fa-box"></i> <?php echo number_format($cat->product_count ?? 0); ?> items
                                    </span>
                                </td>
                                <td>
                                    <?php if ((int)$cat->status === 1): ?>
                                        <a href="javascript:void(0);"
                                            class="cat-status-badge active btn-toggle-status"
                                            data-id="<?php echo $cat->id; ?>"
                                            title="Click to Deactivate">
                                            <i class="fa-solid fa-circle-check"></i> Active
                                        </a>
                                    <?php else: ?>
                                        <a href="javascript:void(0);"
                                            class="cat-status-badge inactive btn-toggle-status"
                                            data-id="<?php echo $cat->id; ?>"
                                            title="Click to Activate">
                                            <i class="fa-solid fa-circle-xmark"></i> Inactive
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="cat-date-primary"><i class="fa-regular fa-calendar me-1"></i><?php echo date('M d, Y', strtotime($cat->created_at)); ?></div>
                                    <div class="cat-date-secondary"><?php echo date('h:i A', strtotime($cat->created_at)); ?></div>
                                </td>
                                <td class="text-end">
                                    <div class="cat-row-actions">
                                        <button type="button"
                                            class="btn-cat-icon edit btn-edit-category"
                                            title="Edit Category"
                                            data-id="<?php echo $cat->id; ?>"
                                            data-name="<?php echo htmlspecialchars($cat->name, ENT_QUOTES); ?>"
                                            data-slug="<?php echo htmlspecialchars($cat->slug ?: '', ENT_QUOTES); ?>"
                                            data-status="<?php echo (int)$cat->status; ?>"
                                            data-image="<?php echo $cat->image ? base_url($cat->image) : ''; ?>"
                                            data-created="<?php echo date('M d, Y h:i A', strtotime($cat->created_at)); ?>"
                                            data-action="<?php echo base_url('admin/categories/edit/' . $cat->id); ?>">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <a href="<?php echo base_url('admin/categories/delete/' . $cat->id); ?>"
                                            class="btn-cat-icon delete btn-delete-category"
                                            title="Delete Category"
                                            data-name="<?php echo htmlspecialchars($cat->name, ENT_QUOTES); ?>">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards View (< 768px) -->
        <div class="d-md-none p-3" style="background: var(--cat-bg);">
            <?php if (empty($categories)): ?>
                <div class="cat-empty-state" style="background:#fff; border-radius:16px; border:1px solid var(--cat-border);">
                    <div class="cat-empty-icon"><i class="fa-regular fa-folder-open"></i></div>
                    <h6 class="fw-bold text-dark mb-1">No Categories Found</h6>
                    <p class="text-muted small mb-3">Adjust your search or add a category.</p>
                    <button type="button" class="btn-cat-gradient" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="fa-solid fa-plus"></i> Add Category
                    </button>
                </div>
            <?php else: ?>
                <?php
                $index_num_mobile = ($current_page - 1) * 10 + 1;
                foreach ($categories as $cat):
                    $cat_img = $cat->image ? base_url($cat->image) : 'https://placehold.co/100x100/111827/d4af37?text=' . urlencode(substr($cat->name, 0, 1));
                ?>
                    <div class="cat-mobile-card">
                        <div class="cat-mobile-top">
                            <img src="<?php echo $cat_img; ?>"
                                alt="<?php echo htmlspecialchars($cat->name); ?>"
                                class="cat-img-thumb"
                                style="width: 56px; height: 56px; flex-shrink:0;"
                                loading="lazy">
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <h6 class="cat-name mb-0 text-truncate"><?php echo htmlspecialchars($cat->name); ?></h6>
                                    <?php if ((int)$cat->status === 1): ?>
                                        <a href="javascript:void(0);" class="cat-status-badge active btn-toggle-status" data-id="<?php echo $cat->id; ?>">
                                            <i class="fa-solid fa-circle-check"></i> Active
                                        </a>
                                    <?php else: ?>
                                        <a href="javascript:void(0);" class="cat-status-badge inactive btn-toggle-status" data-id="<?php echo $cat->id; ?>">
                                            <i class="fa-solid fa-circle-xmark"></i> Inactive
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <span class="cat-slug-badge mt-1">
                                    <i class="fa-solid fa-link opacity-75"></i><?php echo htmlspecialchars($cat->slug ?: url_title($cat->name, 'dash', TRUE)); ?>
                                </span>
                            </div>
                        </div>

                        <div class="cat-mobile-meta">
                            <span><i class="fa-solid fa-box me-1"></i><?php echo number_format($cat->product_count ?? 0); ?> items</span>
                            <span><i class="fa-regular fa-calendar me-1"></i><?php echo date('M d, Y', strtotime($cat->created_at)); ?></span>
                        </div>

                        <div class="cat-mobile-actions">
                            <button type="button"
                                class="btn-cat-mobile-edit btn-edit-category"
                                data-id="<?php echo $cat->id; ?>"
                                data-name="<?php echo htmlspecialchars($cat->name, ENT_QUOTES); ?>"
                                data-slug="<?php echo htmlspecialchars($cat->slug ?: '', ENT_QUOTES); ?>"
                                data-status="<?php echo (int)$cat->status; ?>"
                                data-image="<?php echo $cat->image ? base_url($cat->image) : ''; ?>"
                                data-created="<?php echo date('M d, Y h:i A', strtotime($cat->created_at)); ?>"
                                data-action="<?php echo base_url('admin/categories/edit/' . $cat->id); ?>">
                                <i class="fa-solid fa-pen-to-square"></i> Edit Category
                            </button>
                            <a href="<?php echo base_url('admin/categories/delete/' . $cat->id); ?>"
                                class="btn-cat-mobile-delete btn-delete-category"
                                data-name="<?php echo htmlspecialchars($cat->name, ENT_QUOTES); ?>">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Dynamic Pagination Footer -->
        <?php if (!empty($total_rows) && $total_rows > 0):
            $start_record = ($current_page - 1) * 10 + 1;
            $end_record = min($current_page * 10, $total_rows);
        ?>
            <div class="cat-footer">
                <div class="cat-footer-info">
                    Showing <strong><?php echo $start_record; ?></strong> to <strong><?php echo $end_record; ?></strong> of <strong><?php echo number_format($total_rows); ?></strong> categories
                </div>
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Category Page Navigation">
                        <ul class="cat-pagination pagination-sm">
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

</div><!-- /#categoriesPage -->

<!-- ============================================================== -->
<!-- 5. ADD CATEGORY MODAL                                          -->
<!-- ============================================================== -->
<div class="modal fade cat-modal" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="cat-modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="cat-modal-icon-badge"><i class="fa-solid fa-folder-plus"></i></span>
                    <div>
                        <h5 class="cat-modal-title" id="addCategoryModalLabel">Add New Category</h5>
                        <div class="cat-modal-subtitle">Create a category with custom imagery &amp; status</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="<?php echo base_url('admin/categories/add'); ?>" method="POST" enctype="multipart/form-data" id="addCategoryForm">
                <div class="cat-modal-body">
                    <!-- Image Upload Zone -->
                    <div class="mb-4">
                        <label class="cat-field-label">Category Image</label>
                        <div class="cat-upload-zone" id="addUploadZone">
                            <div class="cat-preview-box">
                                <img id="addImgPreview" src="https://placehold.co/150x150/f1f5f9/94a3b8?text=Preview" alt="Category Preview">
                            </div>
                            <div class="cat-upload-body">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="addImageInput" class="btn-cat-upload mb-0">
                                        <i class="fa-solid fa-cloud-arrow-up"></i> Choose Image
                                    </label>
                                    <button type="button" class="cat-clear-link d-none" id="addImgClearBtn">Clear</button>
                                </div>
                                <input type="file" name="image" id="addImageInput" class="d-none" accept="image/png, image/jpeg, image/jpg, image/gif, image/webp">
                                <span class="cat-upload-hint">PNG, JPG, WebP &middot; up to 2MB</span>
                            </div>
                        </div>
                    </div>

                    <!-- Category Name -->
                    <div class="mb-4">
                        <label for="addCategoryName" class="cat-field-label">Category Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-tag"></i></span>
                            <input type="text" name="name" id="addCategoryName" class="form-control" placeholder="e.g. Skin Care, Ayurvedic, Health" required autocomplete="off">
                        </div>
                        <div class="cat-slug-preview" id="addSlugPreview">
                            <i class="fa-solid fa-link"></i> Slug preview: <code>-</code>
                        </div>
                    </div>

                    <!-- Status Segmented Control -->
                    <div>
                        <label class="cat-field-label">Status</label>
                        <div class="cat-seg-toggle">
                            <input type="radio" name="status" id="addStatusActive" value="1" checked>
                            <label for="addStatusActive" class="seg-active"><i class="fa-solid fa-circle-check me-1"></i> Active</label>

                            <input type="radio" name="status" id="addStatusInactive" value="0">
                            <label for="addStatusInactive" class="seg-inactive"><i class="fa-solid fa-circle-xmark me-1"></i> Inactive</label>
                        </div>
                    </div>
                </div>

                <div class="cat-modal-footer">
                    <button type="button" class="btn-cat-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-cat-gradient" id="addSubmitBtn">
                        <i class="fa-solid fa-floppy-disk"></i> Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 6. EDIT CATEGORY MODAL                                         -->
<!-- ============================================================== -->
<div class="modal fade cat-modal" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="cat-modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="cat-modal-icon-badge"><i class="fa-solid fa-pen-to-square"></i></span>
                    <div>
                        <div class="d-flex align-items-center">
                            <h5 class="cat-modal-title" id="editCategoryModalLabel">Edit Category</h5>
                            <span class="cat-id-chip" id="editCategoryIdBadge">ID #</span>
                        </div>
                        <div class="cat-modal-subtitle" id="editCategorySubMeta">Modify category properties</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="" method="POST" enctype="multipart/form-data" id="editCategoryForm">
                <input type="hidden" name="id" id="editCategoryId">

                <div class="cat-modal-body">
                    <!-- Image Upload Zone -->
                    <div class="mb-4">
                        <label class="cat-field-label">Category Image</label>
                        <div class="cat-upload-zone" id="editUploadZone">
                            <div class="cat-preview-box">
                                <img id="editImgPreview" src="https://placehold.co/150x150/f1f5f9/94a3b8?text=No+Image" alt="Current Image">
                            </div>
                            <div class="cat-upload-body">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="editImageInput" class="btn-cat-upload mb-0">
                                        <i class="fa-solid fa-arrow-up-from-bracket"></i> Change Image
                                    </label>
                                    <button type="button" class="cat-clear-link d-none" id="editImgResetBtn">Revert</button>
                                </div>
                                <input type="file" name="image" id="editImageInput" class="d-none" accept="image/png, image/jpeg, image/jpg, image/gif, image/webp">
                                <span class="cat-upload-hint">Leave empty to keep existing &middot; Max 2MB</span>
                            </div>
                        </div>
                    </div>

                    <!-- Category Name -->
                    <div class="mb-4">
                        <label for="editCategoryName" class="cat-field-label">Category Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-tag"></i></span>
                            <input type="text" name="name" id="editCategoryName" class="form-control" placeholder="Category name" required autocomplete="off">
                        </div>
                        <div class="cat-slug-preview" id="editSlugPreview">
                            <i class="fa-solid fa-link"></i> Slug: <code>-</code>
                        </div>
                    </div>

                    <!-- Status Segmented Control -->
                    <div>
                        <label class="cat-field-label">Status</label>
                        <div class="cat-seg-toggle">
                            <input type="radio" name="status" id="editStatusActive" value="1">
                            <label for="editStatusActive" class="seg-active"><i class="fa-solid fa-circle-check me-1"></i> Active</label>

                            <input type="radio" name="status" id="editStatusInactive" value="0">
                            <label for="editStatusInactive" class="seg-inactive"><i class="fa-solid fa-circle-xmark me-1"></i> Inactive</label>
                        </div>
                    </div>
                </div>

                <div class="cat-modal-footer">
                    <button type="button" class="btn-cat-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-cat-gradient" id="editSubmitBtn">
                        <i class="fa-solid fa-floppy-disk"></i> Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 7. Interactive JavaScript Logic (unchanged behaviour)          -->
<!-- ============================================================== -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const resetBtn = document.getElementById('resetBtn');
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        let debounceTimer;

        const placeholderImg = 'https://placehold.co/150x150/f1f5f9/94a3b8?text=Preview';

        // -------------------------------------------------------------
        // Slug Helper
        // -------------------------------------------------------------
        function slugify(text) {
            return text.toString().toLowerCase().trim()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-');
        }

        // -------------------------------------------------------------
        // Add Modal: Slug generation & Live Image Preview
        // -------------------------------------------------------------
        const addNameInput = document.getElementById('addCategoryName');
        const addSlugPreview = document.getElementById('addSlugPreview');
        const addImageInput = document.getElementById('addImageInput');
        const addImgPreview = document.getElementById('addImgPreview');
        const addImgClearBtn = document.getElementById('addImgClearBtn');
        const addForm = document.getElementById('addCategoryForm');
        const addSubmitBtn = document.getElementById('addSubmitBtn');
        const addUploadZone = document.getElementById('addUploadZone');

        if (addNameInput && addSlugPreview) {
            addNameInput.addEventListener('input', function() {
                const slug = slugify(this.value);
                addSlugPreview.innerHTML = '<i class="fa-solid fa-link"></i> Slug preview: <code>' + (slug || '-') + '</code>';
            });
        }

        function handleImageFile(file, input, previewEl, clearBtn) {
            if (!file) return;
            if (file.size > 2 * 1024 * 1024) {
                if (typeof dsAlert === 'function') {
                    dsAlert({
                        icon: 'warning',
                        title: 'File Too Large',
                        text: 'Please select an image smaller than 2MB.'
                    });
                } else {
                    alert('Image file must be under 2MB.');
                }
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(evt) {
                previewEl.src = evt.target.result;
                if (clearBtn) clearBtn.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }

        if (addImageInput && addImgPreview) {
            addImageInput.addEventListener('change', function(e) {
                handleImageFile(e.target.files[0], addImageInput, addImgPreview, addImgClearBtn);
            });
        }

        if (addImgClearBtn && addImageInput && addImgPreview) {
            addImgClearBtn.addEventListener('click', function() {
                addImageInput.value = '';
                addImgPreview.src = placeholderImg;
                addImgClearBtn.classList.add('d-none');
            });
        }

        // Drag & drop support
        if (addUploadZone) {
            ['dragenter', 'dragover'].forEach(evt => {
                addUploadZone.addEventListener(evt, e => {
                    e.preventDefault();
                    addUploadZone.classList.add('dragover');
                });
            });
            ['dragleave', 'drop'].forEach(evt => {
                addUploadZone.addEventListener(evt, e => {
                    e.preventDefault();
                    addUploadZone.classList.remove('dragover');
                });
            });
            addUploadZone.addEventListener('drop', function(e) {
                const file = e.dataTransfer.files[0];
                if (file && addImageInput) {
                    addImageInput.files = e.dataTransfer.files;
                    handleImageFile(file, addImageInput, addImgPreview, addImgClearBtn);
                }
            });
        }

        if (addForm && addSubmitBtn) {
            addForm.addEventListener('submit', function() {
                addSubmitBtn.disabled = true;
                addSubmitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
            });
        }

        // Reset Add Form on modal close
        const addModalEl = document.getElementById('addCategoryModal');
        if (addModalEl) {
            addModalEl.addEventListener('hidden.bs.modal', function() {
                if (addForm) addForm.reset();
                if (addImgPreview) addImgPreview.src = placeholderImg;
                if (addImgClearBtn) addImgClearBtn.classList.add('d-none');
                if (addSlugPreview) addSlugPreview.innerHTML = '<i class="fa-solid fa-link"></i> Slug preview: <code>-</code>';
                if (addSubmitBtn) {
                    addSubmitBtn.disabled = false;
                    addSubmitBtn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save Category';
                }
            });
        }

        // -------------------------------------------------------------
        // Edit Modal: Populate from data-attributes & Live Preview
        // -------------------------------------------------------------
        const editModalEl = document.getElementById('editCategoryModal');
        const editForm = document.getElementById('editCategoryForm');
        const editIdInput = document.getElementById('editCategoryId');
        const editNameInput = document.getElementById('editCategoryName');
        const editSlugPreview = document.getElementById('editSlugPreview');
        const editIdBadge = document.getElementById('editCategoryIdBadge');
        const editSubMeta = document.getElementById('editCategorySubMeta');
        const editImgPreview = document.getElementById('editImgPreview');
        const editImageInput = document.getElementById('editImageInput');
        const editImgResetBtn = document.getElementById('editImgResetBtn');
        const editSubmitBtn = document.getElementById('editSubmitBtn');
        const editUploadZone = document.getElementById('editUploadZone');
        let originalEditImgSrc = '';

        if (editNameInput && editSlugPreview) {
            editNameInput.addEventListener('input', function() {
                const slug = slugify(this.value);
                editSlugPreview.innerHTML = '<i class="fa-solid fa-link"></i> Slug: <code>' + (slug || '-') + '</code>';
            });
        }

        if (editImageInput && editImgPreview) {
            editImageInput.addEventListener('change', function(e) {
                handleImageFile(e.target.files[0], editImageInput, editImgPreview, editImgResetBtn);
            });
        }

        if (editImgResetBtn && editImageInput && editImgPreview) {
            editImgResetBtn.addEventListener('click', function() {
                editImageInput.value = '';
                editImgPreview.src = originalEditImgSrc;
                editImgResetBtn.classList.add('d-none');
            });
        }

        if (editUploadZone) {
            ['dragenter', 'dragover'].forEach(evt => {
                editUploadZone.addEventListener(evt, e => {
                    e.preventDefault();
                    editUploadZone.classList.add('dragover');
                });
            });
            ['dragleave', 'drop'].forEach(evt => {
                editUploadZone.addEventListener(evt, e => {
                    e.preventDefault();
                    editUploadZone.classList.remove('dragover');
                });
            });
            editUploadZone.addEventListener('drop', function(e) {
                const file = e.dataTransfer.files[0];
                if (file && editImageInput) {
                    editImageInput.files = e.dataTransfer.files;
                    handleImageFile(file, editImageInput, editImgPreview, editImgResetBtn);
                }
            });
        }

        if (editForm && editSubmitBtn) {
            editForm.addEventListener('submit', function() {
                editSubmitBtn.disabled = true;
                editSubmitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Updating...';
            });
        }

        // Bind Edit Button click (Event Delegation)
        document.addEventListener('click', function(e) {
            const editBtn = e.target.closest('.btn-edit-category');
            if (editBtn) {
                e.preventDefault();
                const id = editBtn.getAttribute('data-id');
                const name = editBtn.getAttribute('data-name') || '';
                const slug = editBtn.getAttribute('data-slug') || slugify(name);
                const status = editBtn.getAttribute('data-status') || '1';
                const image = editBtn.getAttribute('data-image') || '';
                const created = editBtn.getAttribute('data-created') || '';
                const action = editBtn.getAttribute('data-action') || ('<?php echo base_url('admin/categories/edit/'); ?>' + id);

                if (editIdInput) editIdInput.value = id;
                if (editForm) editForm.action = action;
                if (editNameInput) editNameInput.value = name;
                if (editSlugPreview) editSlugPreview.innerHTML = '<i class="fa-solid fa-link"></i> Slug: <code>' + (slug || '-') + '</code>';
                if (editIdBadge) editIdBadge.textContent = 'ID #' + id;
                if (editSubMeta) editSubMeta.textContent = created ? ('Created ' + created) : 'Modify category properties';

                originalEditImgSrc = image || 'https://placehold.co/150x150/111827/d4af37?text=' + encodeURIComponent(name.charAt(0) || 'C');
                if (editImgPreview) editImgPreview.src = originalEditImgSrc;
                if (editImageInput) editImageInput.value = '';
                if (editImgResetBtn) editImgResetBtn.classList.add('d-none');

                if (status === '1') {
                    const activeRadio = document.getElementById('editStatusActive');
                    if (activeRadio) activeRadio.checked = true;
                } else {
                    const inactiveRadio = document.getElementById('editStatusInactive');
                    if (inactiveRadio) inactiveRadio.checked = true;
                }

                if (editSubmitBtn) {
                    editSubmitBtn.disabled = false;
                    editSubmitBtn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Update Category';
                }

                if (editModalEl) {
                    const modal = bootstrap.Modal.getOrCreateInstance(editModalEl);
                    modal.show();
                }
            }
        });

        // -------------------------------------------------------------
        // Quick Status Toggle via AJAX
        // -------------------------------------------------------------
        document.addEventListener('click', function(e) {
            const toggleBtn = e.target.closest('.btn-toggle-status');
            if (toggleBtn) {
                e.preventDefault();
                const id = toggleBtn.getAttribute('data-id');
                if (!id) return;

                const originalHtml = toggleBtn.innerHTML;
                toggleBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

                fetch('<?php echo base_url('admin/categories/toggle_status/'); ?>' + id, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (data.new_status === 1) {
                                toggleBtn.className = 'cat-status-badge active btn-toggle-status';
                                toggleBtn.innerHTML = '<i class="fa-solid fa-circle-check"></i> Active';
                                toggleBtn.title = 'Click to Deactivate';
                            } else {
                                toggleBtn.className = 'cat-status-badge inactive btn-toggle-status';
                                toggleBtn.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> Inactive';
                                toggleBtn.title = 'Click to Activate';
                            }
                            if (typeof dsToast === 'function') {
                                dsToast({
                                    icon: 'success',
                                    title: data.message
                                });
                            }
                        } else {
                            toggleBtn.innerHTML = originalHtml;
                            if (typeof dsAlert === 'function') {
                                dsAlert({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message || 'Could not update status.'
                                });
                            }
                        }
                    })
                    .catch(() => {
                        toggleBtn.innerHTML = originalHtml;
                    });
            }
        });

        // -------------------------------------------------------------
        // Dynamic Filter & Search Table Loader (AJAX)
        // -------------------------------------------------------------
        function loadTable(page = 1) {
            const search = searchInput ? searchInput.value.trim() : '';
            const status = statusFilter ? statusFilter.value : '';
            const url = new URL(window.location.href);

            url.searchParams.set('search', search);
            url.searchParams.set('status', status);
            url.searchParams.set('page', page);

            window.history.pushState({}, '', url.toString());

            fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTable = doc.getElementById('table-container');
                    if (newTable) {
                        document.getElementById('table-container').innerHTML = newTable.innerHTML;
                        bindActionListeners();
                    }
                })
                .catch(err => console.error('Error fetching categories:', err));
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    loadTable(1);
                }, 300);
            });
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                loadTable(1);
            });
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                if (searchInput) searchInput.value = '';
                if (statusFilter) statusFilter.value = '';
                loadTable(1);
            });
        }

        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                if (searchInput) {
                    searchInput.value = '';
                    loadTable(1);
                    clearSearchBtn.remove();
                }
            });
        }

        // Pagination links click
        document.addEventListener('click', function(e) {
            const pageLink = e.target.closest('#table-container .pagination .page-link, #table-container .cat-pagination .page-link');
            if (pageLink) {
                e.preventDefault();
                const page = pageLink.getAttribute('data-page');
                if (page) {
                    loadTable(page);
                }
            }
        });

        // -------------------------------------------------------------
        // SweetAlert Delete Confirmation
        // -------------------------------------------------------------
        function bindActionListeners() {
            const deleteButtons = document.querySelectorAll('.btn-delete-category');
            deleteButtons.forEach(button => {
                const newBtn = button.cloneNode(true);
                button.parentNode.replaceChild(newBtn, button);

                newBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const deleteUrl = this.getAttribute('href');
                    const catName = this.getAttribute('data-name') || 'this category';

                    if (typeof dsConfirm === 'function') {
                        dsConfirm({
                            title: 'Delete Category?',
                            text: 'Are you sure you want to delete "' + catName + '"? Any products linked to it will be orphaned. This action cannot be undone!',
                            icon: 'warning',
                            confirmText: 'Yes, Delete Category',
                            cancelText: 'Cancel',
                            isDangerous: true,
                            onConfirm: function() {
                                window.location.href = deleteUrl;
                            }
                        });
                    } else {
                        if (confirm('Delete category "' + catName + '"?')) {
                            window.location.href = deleteUrl;
                        }
                    }
                });
            });
        }

        bindActionListeners();
    });
</script>