<style>
    /* =======================================================
       Order Management Page — scoped styles (.ord- prefix)
       ======================================================= */
    .ord-wrap {
        --ord-gold: #c89738;
        --ord-gold-dark: #a97c26;
        --ord-dark: #111827;
        --ord-dark-2: #1f2937;
        --ord-border: #e5e7eb;
        --ord-muted: #6b7280;
        --ord-text: #111827;
        --ord-radius: 16px;
        --ord-radius-sm: 12px;
        --ord-shadow: 0 2px 8px rgba(17, 24, 39, .05);
        --ord-shadow-hover: 0 12px 26px -6px rgba(17, 24, 39, .10);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        color: var(--ord-text);
    }

    .ord-wrap * {
        box-sizing: border-box;
    }

    /* ---------- Header ---------- */
    .ord-header {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 22px;
    }

    .ord-header h3 {
        font-size: clamp(1.35rem, 2vw, 1.75rem);
        font-weight: 800;
        color: var(--ord-dark);
        margin: 0 0 5px 0;
        letter-spacing: -.02em;
    }

    .ord-header p {
        color: var(--ord-muted);
        font-size: .88rem;
        margin: 0;
    }

    .ord-refresh-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 12px;
        background: #fff;
        border: 1.5px solid var(--ord-border);
        color: var(--ord-dark-2);
        font-weight: 600;
        font-size: .85rem;
        cursor: pointer;
        transition: all .18s ease;
        box-shadow: var(--ord-shadow);
    }

    .ord-refresh-btn:hover {
        border-color: var(--ord-dark-2);
        background: var(--ord-dark-2);
        color: #fff;
        transform: translateY(-1px);
    }

    /* ---------- KPI cards ---------- */
    .ord-kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 22px;
    }

    .ord-kpi-card {
        background: #fff;
        border: 1px solid var(--ord-border);
        border-radius: var(--ord-radius);
        padding: 20px 22px;
        box-shadow: var(--ord-shadow);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        transition: all .25s cubic-bezier(.4, 0, .2, 1);
        position: relative;
        overflow: hidden;
        min-width: 0;
    }

    .ord-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--ord-shadow-hover);
        border-color: #d1d5db;
    }

    .ord-kpi-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--ord-accent, linear-gradient(90deg, var(--ord-gold), var(--ord-gold-dark)));
    }

    .ord-kpi-card.orders {
        --ord-accent: linear-gradient(90deg, #6366f1, #4f46e5);
    }

    .ord-kpi-card.revenue {
        --ord-accent: linear-gradient(90deg, #f59e0b, #b45309);
    }

    .ord-kpi-card.delivered {
        --ord-accent: linear-gradient(90deg, #10b981, #059669);
    }

    .ord-kpi-card.pending {
        --ord-accent: linear-gradient(90deg, #f97316, #ea580c);
    }

    .ord-kpi-body {
        min-width: 0;
    }

    .ord-kpi-label {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .6px;
        text-transform: uppercase;
        color: var(--ord-muted);
        display: block;
        margin-bottom: 8px;
    }

    .ord-kpi-value {
        font-size: 1.65rem;
        font-weight: 800;
        color: var(--ord-dark);
        margin: 0 0 6px 0;
        line-height: 1;
    }

    .ord-kpi-sub {
        font-size: .75rem;
        color: var(--ord-muted);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .ord-kpi-icon {
        width: 50px;
        height: 50px;
        flex-shrink: 0;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .ord-kpi-card.orders .ord-kpi-icon {
        background: rgba(79, 70, 229, .12);
        color: #4f46e5;
    }

    .ord-kpi-card.revenue .ord-kpi-icon {
        background: rgba(212, 175, 55, .18);
        color: #b45309;
    }

    .ord-kpi-card.delivered .ord-kpi-icon {
        background: rgba(16, 185, 129, .14);
        color: #059669;
    }

    .ord-kpi-card.pending .ord-kpi-icon {
        background: rgba(249, 115, 22, .14);
        color: #ea580c;
    }

    /* ---------- Filter card ---------- */
    .ord-filter-card {
        background: #fff;
        border: 1px solid var(--ord-border);
        border-radius: var(--ord-radius);
        box-shadow: var(--ord-shadow);
        padding: 20px;
        margin-bottom: 22px;
    }

    .ord-pills-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f0f1f5;
        margin-bottom: 16px;
    }

    .ord-pill {
        border: 1.5px solid var(--ord-border);
        background: #f9fafb;
        color: #4b5563;
        font-size: .82rem;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all .18s ease;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .ord-pill:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
    }

    .ord-pill.active {
        background: var(--ord-dark);
        color: #fff;
        border-color: var(--ord-dark);
        box-shadow: 0 4px 12px rgba(17, 24, 39, .22);
    }

    .ord-pill .ord-count {
        font-size: .72rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 999px;
        background: rgba(0, 0, 0, .08);
        color: inherit;
    }

    .ord-pill.active .ord-count {
        background: rgba(255, 255, 255, .25);
        color: #fff;
    }

    .ord-filter-inputs {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }

    .ord-search-wrap {
        flex: 1 1 320px;
        position: relative;
        display: flex;
        align-items: center;
        border: 1.5px solid var(--ord-border);
        border-radius: var(--ord-radius-sm);
        background: #fff;
        overflow: hidden;
        transition: border-color .18s ease, box-shadow .18s ease;
        min-width: 0;
    }

    .ord-search-wrap:focus-within {
        border-color: var(--ord-gold);
        box-shadow: 0 0 0 4px rgba(200, 151, 56, .14);
    }

    .ord-search-wrap i.ord-search-icon {
        padding: 0 14px;
        color: var(--ord-muted);
    }

    .ord-search-wrap input {
        border: none;
        outline: none;
        flex: 1;
        min-width: 0;
        padding: 11px 6px 11px 0;
        font-size: .87rem;
        font-family: inherit;
        text-overflow: ellipsis;
    }

    .ord-clear-search {
        background: transparent;
        border: none;
        color: var(--ord-muted);
        cursor: pointer;
        padding: 0 14px;
        font-size: .85rem;
    }

    .ord-clear-search:hover {
        color: var(--ord-dark);
    }

    .ord-status-select {
        flex: 1 1 220px;
        padding: 11px 34px 11px 14px;
        border: 1.5px solid var(--ord-border);
        border-radius: var(--ord-radius-sm);
        font-size: .87rem;
        font-family: inherit;
        background-color: #fff;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%236b7280' stroke-width='1.6' fill='none' fill-rule='evenodd'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .ord-status-select:focus {
        outline: none;
        border-color: var(--ord-gold);
        box-shadow: 0 0 0 4px rgba(200, 151, 56, .14);
    }

    .ord-reset-btn {
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 20px;
        border-radius: var(--ord-radius-sm);
        background: var(--ord-dark-2);
        color: #fff;
        border: none;
        font-weight: 600;
        font-size: .85rem;
        cursor: pointer;
        transition: all .18s ease;
    }

    .ord-reset-btn:hover {
        background: var(--ord-dark);
        transform: translateY(-1px);
    }

    /* ---------- Table card ---------- */
    .ord-table-card {
        background: #fff;
        border: 1px solid var(--ord-border);
        border-radius: var(--ord-radius);
        box-shadow: var(--ord-shadow);
        overflow: hidden;
    }

    .ord-table-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 16px 20px;
        border-bottom: 1px solid var(--ord-border);
        flex-wrap: wrap;
    }

    .ord-table-bar h6 {
        margin: 0;
        font-weight: 700;
        color: var(--ord-dark);
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: .98rem;
    }

    .ord-record-badge {
        font-size: .74rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 999px;
        background: #f3f4f6;
        color: var(--ord-muted);
        border: 1px solid var(--ord-border);
    }

    .ord-table-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .ord-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1040px;
    }

    .ord-table thead th {
        background: var(--ord-dark-2);
        color: #fff;
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .6px;
        font-weight: 700;
        padding: 14px 14px;
        text-align: left;
        white-space: nowrap;
        border-bottom: 3px solid var(--ord-gold);
    }

    .ord-table thead th:first-child {
        padding-left: 22px;
    }

    .ord-table thead th.text-end {
        text-align: right;
        padding-right: 22px;
    }

    .ord-table tbody td {
        padding: 14px;
        border-bottom: 1px solid #f1f2f5;
        vertical-align: middle;
    }

    .ord-table tbody td:first-child {
        padding-left: 22px;
    }

    .ord-table tbody td.text-end {
        text-align: right;
        padding-right: 22px;
    }

    .ord-table tbody tr {
        transition: background-color .15s ease;
    }

    .ord-table tbody tr:hover {
        background: #f9fafb;
    }

    .ord-table tbody tr:last-child td {
        border-bottom: none;
    }

    .ord-row-highlight {
        background: rgba(254, 243, 199, .28);
    }

    .ord-idx {
        font-weight: 700;
        color: var(--ord-muted);
    }

    .ord-id-tag {
        font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, monospace;
        font-weight: 700;
        color: var(--ord-dark);
        background: #f3f4f6;
        border: 1px solid var(--ord-border);
        padding: 4px 10px;
        border-radius: 8px;
        font-size: .8rem;
        text-decoration: none;
        display: inline-block;
        white-space: nowrap;
    }

    .ord-id-tag:hover {
        background: var(--ord-gold);
        border-color: var(--ord-gold-dark);
        color: #fff;
    }

    /* Buyer */
    .ord-buyer {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .ord-buyer-info {
        min-width: 0;
    }

    .ord-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        flex-shrink: 0;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: #fff;
        font-weight: 700;
        font-size: .82rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 3px 8px rgba(29, 78, 216, .25);
    }

    .ord-buyer-name {
        font-weight: 700;
        color: var(--ord-dark);
        font-size: .89rem;
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .ord-buyer-sub {
        font-size: .75rem;
        color: var(--ord-muted);
        display: flex;
        align-items: center;
        gap: 6px;
        line-height: 1.5;
        min-width: 0;
    }

    .ord-buyer-sub i {
        width: 12px;
        text-align: center;
        flex-shrink: 0;
    }

    .ord-buyer-sub span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Product */
    .ord-product {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .ord-product-img {
        width: 46px;
        height: 46px;
        flex-shrink: 0;
        border-radius: 10px;
        border: 1px solid var(--ord-border);
        background: #f3f4f6;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
    }

    .ord-product-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .ord-product-info {
        min-width: 0;
    }

    .ord-product-name {
        font-weight: 600;
        color: var(--ord-dark);
        font-size: .88rem;
        line-height: 1.35;
        display: block;
        word-break: break-word;
    }

    .ord-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 5px;
    }

    .ord-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
        font-size: .68rem;
        font-weight: 700;
        line-height: 1;
        padding: 4px 9px;
        border-radius: 999px;
    }

    .ord-chip-size {
        background: #fffbeb;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .ord-chip-more {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    /* Quantity */
    .ord-qty {
        display: inline-flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 2px;
        white-space: nowrap;
    }

    .ord-qty-main {
        display: inline-flex;
        align-items: baseline;
        gap: 5px;
        font-weight: 800;
        font-size: 1rem;
        color: var(--ord-dark);
    }

    .ord-qty-main small {
        font-size: .74rem;
        font-weight: 600;
        color: var(--ord-muted);
    }

    .ord-qty-sub {
        font-size: .7rem;
        font-weight: 600;
        color: var(--ord-muted);
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .ord-amount {
        font-weight: 800;
        color: var(--ord-dark);
        font-size: .95rem;
        white-space: nowrap;
    }

    .ord-date {
        font-size: .83rem;
        font-weight: 600;
        color: var(--ord-dark);
        white-space: nowrap;
    }

    .ord-time {
        font-size: .73rem;
        color: var(--ord-muted);
        white-space: nowrap;
        margin-top: 2px;
    }

    .ord-date i,
    .ord-time i {
        margin-right: 5px;
    }

    /* status badges */
    .ord-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 13px;
        border-radius: 999px;
        font-size: .76rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .ord-status-pending {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fcd34d;
    }

    .ord-status-placed {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    .ord-status-confirmed {
        background: #eef2ff;
        color: #4338ca;
        border: 1px solid #c7d2fe;
    }

    .ord-status-packed {
        background: #ecfeff;
        color: #0e7490;
        border: 1px solid #a5f3fc;
    }

    .ord-status-out_for_delivery {
        background: #fff7ed;
        color: #c2410c;
        border: 1px solid #fed7aa;
    }

    .ord-status-delivered {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .ord-status-completed {
        background: #f0fdfa;
        color: #0f766e;
        border: 1px solid #99f6e4;
    }

    .ord-status-cancelled {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    @keyframes ordPulse {
        0% {
            opacity: .6;
            transform: scale(.95);
        }

        50% {
            opacity: 1;
            transform: scale(1.1);
        }

        100% {
            opacity: .6;
            transform: scale(.95);
        }
    }

    .ord-pulse {
        animation: ordPulse 1.8s infinite;
    }

    @media (prefers-reduced-motion: reduce) {
        .ord-pulse {
            animation: none;
        }
    }

    /* action buttons */
    .ord-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
    }

    .ord-btn-view,
    .ord-btn-delete {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: .86rem;
        font-weight: 700;
        font-family: inherit;
        border: 1.5px solid transparent;
        cursor: pointer;
        text-decoration: none;
        transition: all .18s ease;
    }

    .ord-btn-text {
        display: none;
    }

    .ord-btn-view {
        background: #eff6ff;
        color: #2563eb;
        border-color: #bfdbfe;
    }

    .ord-btn-view:hover {
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(37, 99, 235, .28);
    }

    .ord-btn-delete {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    .ord-btn-delete:hover {
        background: #dc2626;
        border-color: #dc2626;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(220, 38, 38, .28);
    }

    /* empty state */
    .ord-empty {
        text-align: center;
        padding: 60px 20px;
    }

    .ord-empty i {
        font-size: 3.4rem;
        color: #d1d5db;
        margin-bottom: 14px;
    }

    .ord-empty h6 {
        font-weight: 700;
        color: var(--ord-dark);
        margin-bottom: 6px;
    }

    .ord-empty p {
        color: var(--ord-muted);
        font-size: .87rem;
        margin-bottom: 16px;
    }

    .ord-empty button {
        padding: 9px 18px;
        border-radius: 10px;
        border: 1.5px solid #bfd6fe;
        background: #fff;
        color: #2563eb;
        font-weight: 600;
        font-size: .84rem;
        cursor: pointer;
        transition: all .18s ease;
    }

    .ord-empty button:hover {
        background: #2563eb;
        color: #fff;
    }

    /* footer / pagination */
    .ord-footer {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-top: 1px solid var(--ord-border);
    }

    .ord-footer-info {
        font-size: .83rem;
        color: var(--ord-muted);
    }

    .ord-pagination {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .ord-page-link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 7px 13px;
        border-radius: 9px;
        border: 1.5px solid var(--ord-border);
        font-size: .82rem;
        font-weight: 600;
        color: var(--ord-dark-2);
        text-decoration: none;
        transition: all .18s ease;
        cursor: pointer;
        background: #fff;
    }

    .ord-page-link:hover {
        border-color: var(--ord-gold);
        color: var(--ord-gold-dark);
    }

    .ord-page-item.active .ord-page-link {
        background: var(--ord-dark);
        border-color: var(--ord-dark);
        color: #fff;
    }

    .ord-page-item.disabled .ord-page-link {
        opacity: .4;
        pointer-events: none;
    }

    /* ============ Tablet ============ */
    @media (max-width: 991px) {
        .ord-kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* ============ Mobile: each row becomes a clean order card ============ */
    @media (max-width: 767px) {
        .ord-header {
            margin-bottom: 16px;
        }

        .ord-header>div {
            flex: 1 1 100%;
        }

        .ord-refresh-btn {
            width: 100%;
            justify-content: center;
        }

        /* KPI: compact 2x2 */
        .ord-kpi-grid {
            gap: 10px;
            margin-bottom: 16px;
        }

        .ord-kpi-card {
            padding: 14px;
            flex-direction: column-reverse;
            align-items: flex-start;
            gap: 10px;
        }

        .ord-kpi-icon {
            width: 38px;
            height: 38px;
            font-size: 1rem;
            border-radius: 11px;
        }

        .ord-kpi-value {
            font-size: 1.2rem;
        }

        .ord-kpi-label {
            font-size: .64rem;
            margin-bottom: 6px;
        }

        .ord-kpi-sub {
            font-size: .68rem;
        }

        /* Filters */
        .ord-filter-card {
            padding: 14px;
            margin-bottom: 16px;
        }

        .ord-pills-bar {
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: 8px;
            margin: 0 -14px 14px;
            padding: 0 14px 14px;
            scrollbar-width: none;
        }

        .ord-pills-bar::-webkit-scrollbar {
            display: none;
        }

        .ord-pill {
            padding: 8px 14px;
            font-size: .8rem;
        }

        .ord-filter-inputs {
            gap: 10px;
        }

        .ord-search-wrap {
            flex: 1 1 100%;
        }

        .ord-status-select {
            flex: 1 1 0;
            min-width: 0;
        }

        .ord-reset-btn {
            flex: 0 0 auto;
        }

        .ord-table-bar {
            padding: 14px 16px;
        }

        /* Table -> cards */
        .ord-table-scroll {
            overflow-x: visible;
        }

        .ord-table {
            min-width: 0;
            display: block;
        }

        .ord-table thead {
            display: none;
        }

        .ord-table tbody {
            display: block;
            padding: 4px 12px 12px;
        }

        .ord-table tbody tr {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-areas:
                "id      status"
                "buyer   buyer"
                "product product"
                "qty     amount"
                "date    date"
                "actions actions";
            background: #fff;
            border: 1px solid var(--ord-border);
            border-radius: 16px;
            margin-top: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(17, 24, 39, .05);
        }

        .ord-table tbody tr:hover {
            background: #fff;
        }

        .ord-table tbody tr.ord-row-highlight {
            background: #fffdf5;
            border-left: 4px solid var(--ord-gold);
        }

        .ord-table tbody tr.ord-empty-row {
            display: block;
        }

        .ord-table tbody td,
        .ord-table tbody td:first-child,
        .ord-table tbody td.text-end {
            display: block;
            padding: 12px 16px;
            border: none;
            text-align: left;
        }

        .ord-table tbody tr:last-child td {
            border-bottom: none;
        }

        .ord-td-idx {
            display: none !important;
        }

        .ord-td-id {
            grid-area: id;
            display: flex !important;
            align-items: center;
            background: #f9fafb;
            border-bottom: 1px solid var(--ord-border) !important;
        }

        .ord-td-status {
            grid-area: status;
            display: flex !important;
            align-items: center;
            justify-content: flex-end;
            background: #f9fafb;
            border-bottom: 1px solid var(--ord-border) !important;
        }

        .ord-td-buyer {
            grid-area: buyer;
        }

        .ord-td-product {
            grid-area: product;
            border-top: 1px dashed #eceef3 !important;
            border-bottom: 1px dashed #eceef3 !important;
        }

        .ord-td-qty {
            grid-area: qty;
        }

        .ord-td-amount {
            grid-area: amount;
            text-align: right !important;
        }

        .ord-td-date {
            grid-area: date;
            border-top: 1px dashed #eceef3 !important;
        }

        .ord-td-actions {
            grid-area: actions;
            background: #f9fafb;
            border-top: 1px solid var(--ord-border) !important;
        }

        /* Small labels only on the stat cells */
        .ord-td-qty::before,
        .ord-td-amount::before,
        .ord-td-date::before {
            content: attr(data-label);
            display: block;
            margin-bottom: 4px;
            font-size: .66rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--ord-muted);
        }

        .ord-td-date {
            display: flex !important;
            flex-wrap: wrap;
            align-items: center;
            gap: 4px 14px;
        }

        .ord-td-date::before {
            flex: 0 0 100%;
            margin-bottom: 0;
        }

        .ord-amount {
            font-size: 1.1rem;
        }

        .ord-qty-main {
            font-size: 1.05rem;
        }

        .ord-buyer-sub span {
            white-space: normal;
            word-break: break-all;
        }

        .ord-product-img {
            width: 52px;
            height: 52px;
        }

        /* Full-width tappable action buttons */
        .ord-actions {
            gap: 10px;
        }

        .ord-btn-view,
        .ord-btn-delete {
            flex: 1;
            width: auto;
            height: 42px;
            font-size: .86rem;
        }

        .ord-btn-text {
            display: inline;
        }

        .ord-footer {
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 14px;
        }

        .ord-pagination {
            justify-content: center;
        }

        .ord-page-link {
            padding: 8px 12px;
        }
    }

    @media (max-width: 380px) {
        .ord-kpi-card {
            padding: 12px;
        }

        .ord-kpi-value {
            font-size: 1.05rem;
        }
    }
</style>

<div class="ord-wrap">

    <!-- Page Header -->
    <div class="ord-header">
        <div>
            <h3>Order Management</h3>
            <p>Track real-time fulfillment, customer payments, and referral commission distribution.</p>
        </div>
        <button type="button" id="refreshBtn" class="ord-refresh-btn">
            <i class="fa-solid fa-arrows-rotate"></i> Refresh
        </button>
    </div>

    <!-- KPI Cards -->
    <div class="ord-kpi-grid">
        <div class="ord-kpi-card orders">
            <div class="ord-kpi-body">
                <span class="ord-kpi-label">Total Orders</span>
                <h3 class="ord-kpi-value"><?php echo number_format($kpi_total_orders ?? 0); ?></h3>
                <span class="ord-kpi-sub"><i class="fa-solid fa-chart-line" style="color:#4f46e5;"></i>All system orders</span>
            </div>
            <div class="ord-kpi-icon"><i class="fa-solid fa-bag-shopping"></i></div>
        </div>

        <div class="ord-kpi-card revenue">
            <div class="ord-kpi-body">
                <span class="ord-kpi-label">Paid Order Value</span>
                <h3 class="ord-kpi-value">₹<?php echo number_format($kpi_total_revenue ?? 0, 2); ?></h3>
                <span class="ord-kpi-sub"><i class="fa-solid fa-shield-check" style="color:#b45309;"></i>Active &amp; completed</span>
            </div>
            <div class="ord-kpi-icon"><i class="fa-solid fa-wallet"></i></div>
        </div>

        <div class="ord-kpi-card delivered">
            <div class="ord-kpi-body">
                <span class="ord-kpi-label">Delivered / Done</span>
                <h3 class="ord-kpi-value"><?php echo number_format($kpi_delivered_orders ?? 0); ?></h3>
                <span class="ord-kpi-sub"><i class="fa-solid fa-circle-check" style="color:#059669;"></i>Fulfilled deliveries</span>
            </div>
            <div class="ord-kpi-icon"><i class="fa-solid fa-truck-ramp-box"></i></div>
        </div>

        <div class="ord-kpi-card pending">
            <div class="ord-kpi-body">
                <span class="ord-kpi-label">Pending Action</span>
                <h3 class="ord-kpi-value"><?php echo number_format($kpi_pending_orders ?? 0); ?></h3>
                <span class="ord-kpi-sub"><i class="fa-solid fa-clock" style="color:#ea580c;"></i>Awaiting pay / fulfillment</span>
            </div>
            <div class="ord-kpi-icon"><i class="fa-solid fa-bell"></i></div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="ord-filter-card">
        <div class="ord-pills-bar">
            <button type="button" class="ord-pill <?php echo (empty($status)) ? 'active' : ''; ?>" data-status="">
                <i class="fa-solid fa-list-check"></i> All Orders
                <span class="ord-count"><?php echo $status_counts['all'] ?? 0; ?></span>
            </button>
            <button type="button" class="ord-pill <?php echo ($status === 'pending') ? 'active' : ''; ?>" data-status="pending">
                <i class="fa-solid fa-clock"></i> Awaiting Payment
                <span class="ord-count"><?php echo $status_counts['pending'] ?? 0; ?></span>
            </button>
            <button type="button" class="ord-pill <?php echo ($status === 'placed') ? 'active' : ''; ?>" data-status="placed">
                <i class="fa-solid fa-receipt"></i> Placed
                <span class="ord-count"><?php echo $status_counts['placed'] ?? 0; ?></span>
            </button>
            <button type="button" class="ord-pill <?php echo ($status === 'confirmed') ? 'active' : ''; ?>" data-status="confirmed">
                <i class="fa-solid fa-circle-check"></i> Confirmed
                <span class="ord-count"><?php echo $status_counts['confirmed'] ?? 0; ?></span>
            </button>
            <button type="button" class="ord-pill <?php echo ($status === 'packed') ? 'active' : ''; ?>" data-status="packed">
                <i class="fa-solid fa-box-open"></i> Packed
                <span class="ord-count"><?php echo $status_counts['packed'] ?? 0; ?></span>
            </button>
            <button type="button" class="ord-pill <?php echo ($status === 'out_for_delivery') ? 'active' : ''; ?>" data-status="out_for_delivery">
                <i class="fa-solid fa-truck-fast"></i> Out for Delivery
                <span class="ord-count"><?php echo $status_counts['out_for_delivery'] ?? 0; ?></span>
            </button>
            <button type="button" class="ord-pill <?php echo ($status === 'delivered') ? 'active' : ''; ?>" data-status="delivered">
                <i class="fa-solid fa-circle-check"></i> Delivered
                <span class="ord-count"><?php echo $status_counts['delivered'] ?? 0; ?></span>
            </button>
            <button type="button" class="ord-pill <?php echo ($status === 'cancelled') ? 'active' : ''; ?>" data-status="cancelled">
                <i class="fa-solid fa-circle-xmark"></i> Cancelled
                <span class="ord-count"><?php echo $status_counts['cancelled'] ?? 0; ?></span>
            </button>
        </div>

        <form id="filterForm" class="ord-filter-inputs" onsubmit="return false;">
            <div class="ord-search-wrap">
                <i class="fa-solid fa-magnifying-glass ord-search-icon"></i>
                <input type="text" name="search" id="searchInput" placeholder="Search order ID, customer, email or product..." value="<?php echo htmlspecialchars($search ?? ''); ?>" autocomplete="off">
                <button type="button" id="clearSearchBtn" class="ord-clear-search" title="Clear search" style="<?php echo empty($search) ? 'display:none;' : ''; ?>">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <select name="status" id="statusFilter" class="ord-status-select">
                <option value="">All Statuses (<?php echo $status_counts['all'] ?? 0; ?>)</option>
                <option value="pending" <?php echo ($status === 'pending') ? 'selected' : ''; ?>>🟡 Awaiting Payment (Pending)</option>
                <option value="placed" <?php echo ($status === 'placed') ? 'selected' : ''; ?>>🔵 Placed</option>
                <option value="confirmed" <?php echo ($status === 'confirmed') ? 'selected' : ''; ?>>🟣 Confirmed</option>
                <option value="packed" <?php echo ($status === 'packed') ? 'selected' : ''; ?>>🌐 Packed</option>
                <option value="out_for_delivery" <?php echo ($status === 'out_for_delivery') ? 'selected' : ''; ?>>🟠 Out for Delivery</option>
                <option value="delivered" <?php echo ($status === 'delivered') ? 'selected' : ''; ?>>🟢 Delivered / Completed</option>
                <option value="cancelled" <?php echo ($status === 'cancelled') ? 'selected' : ''; ?>>🔴 Cancelled</option>
            </select>

            <button type="button" id="resetBtn" class="ord-reset-btn">
                <i class="fa-solid fa-arrow-rotate-left"></i> Reset
            </button>
        </form>
    </div>

    <!-- Table Card -->
    <div class="ord-table-card">
        <div class="ord-table-bar">
            <h6><i class="fa-solid fa-table-list" style="color:var(--ord-gold);"></i> Orders Directory</h6>
            <span class="ord-record-badge"><?php echo number_format($total_rows ?? count($orders)); ?> Records</span>
        </div>

        <div id="table-container">
            <div class="ord-table-scroll">
                <table class="ord-table">
                    <thead>
                        <tr>
                            <th style="width:56px;">#</th>
                            <th style="width:90px;">Order ID</th>
                            <th style="width:250px;">Buyer Account</th>
                            <th>Product Details</th>
                            <th style="width:110px;">Quantity</th>
                            <th style="width:120px;">Total Amount</th>
                            <th style="width:160px;">Status</th>
                            <th style="width:130px;">Ordered Date</th>
                            <th class="text-end" style="width:100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr class="ord-empty-row">
                                <td colspan="9">
                                    <div class="ord-empty">
                                        <i class="fa-solid fa-box-open"></i>
                                        <h6>No orders found</h6>
                                        <p>No customer orders matched your active search or filter criteria.</p>
                                        <button type="button" onclick="document.getElementById('resetBtn').click();">
                                            <i class="fa-solid fa-rotate-left me-1"></i> Clear Filters
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php
                            $index_num = ($current_page - 1) * 10 + 1;
                            foreach ($orders as $order):
                                $is_new_placed = in_array($order->status, ['placed', 'pending']);

                                // Initials: first letter of first two words (e.g. "Kaushik Makwana" => "KM")
                                $name_parts = preg_split('/\s+/', trim($order->buyer_name ?? 'Customer'));
                                $initials = strtoupper(substr($name_parts[0] ?? 'C', 0, 1) . (isset($name_parts[1]) ? substr($name_parts[1], 0, 1) : ''));

                                $items_count = (int)($order->items_count ?? 1);
                                $extra_items = max(0, $items_count - 1);
                                $qty = (int)($order->quantity ?? 1);
                            ?>
                                <tr id="order-row-<?php echo $order->id; ?>" class="<?php echo $is_new_placed ? 'ord-row-highlight' : ''; ?>">
                                    <td data-label="#" class="ord-td-idx"><span class="ord-idx"><?php echo $index_num++; ?></span></td>

                                    <td data-label="Order ID" class="ord-td-id">
                                        <?php 
                                            $display_order_id = !empty($order->order_number) 
                                                ? $order->order_number 
                                                : ('DS' . date('Ymd', strtotime($order->created_at ?: date('Y-m-d'))) . sprintf('%04d', $order->id));
                                        ?>
                                        <a href="<?php echo base_url('admin/orders/detail/' . $order->id); ?>" class="ord-id-tag" title="View details for Order <?php echo htmlspecialchars($display_order_id); ?>">
                                            <?php echo htmlspecialchars($display_order_id); ?>
                                        </a>
                                    </td>

                                    <td data-label="Buyer Account" class="ord-td-buyer">
                                        <div class="ord-buyer">
                                            <div class="ord-avatar"><?php echo htmlspecialchars($initials); ?></div>
                                            <div class="ord-buyer-info">
                                                <div class="ord-buyer-name"><?php echo htmlspecialchars($order->buyer_name ?? 'Customer'); ?></div>
                                                <?php if (!empty($order->buyer_email)): ?>
                                                    <div class="ord-buyer-sub" title="<?php echo htmlspecialchars($order->buyer_email); ?>"><i class="fa-regular fa-envelope"></i><span><?php echo htmlspecialchars($order->buyer_email); ?></span></div>
                                                <?php endif; ?>
                                                <?php if (!empty($order->buyer_phone)): ?>
                                                    <div class="ord-buyer-sub"><i class="fa-solid fa-phone"></i><span><?php echo htmlspecialchars($order->buyer_phone); ?></span></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>

                                    <td data-label="Product Details" class="ord-td-product">
                                        <div class="ord-product">
                                            <div class="ord-product-img">
                                                <?php if (!empty($order->product_image)): ?>
                                                    <img src="<?php echo base_url($order->product_image); ?>" alt="Product" loading="lazy" onerror="this.style.display='none';">
                                                <?php else: ?>
                                                    <i class="fa-regular fa-image"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="ord-product-info">
                                                <span class="ord-product-name"><?php echo htmlspecialchars($order->product_name ?? 'Product'); ?></span>
                                                <?php if (!empty($order->size) || $extra_items > 0): ?>
                                                    <div class="ord-chips">
                                                        <?php if (!empty($order->size)): ?>
                                                            <span class="ord-chip ord-chip-size"><i class="fa-solid fa-shirt"></i>Size: <?php echo htmlspecialchars($order->size); ?></span>
                                                        <?php endif; ?>
                                                        <?php if ($extra_items > 0): ?>
                                                            <span class="ord-chip ord-chip-more"><i class="fa-solid fa-boxes-stacked"></i>+<?php echo $extra_items; ?> more item<?php echo $extra_items > 1 ? 's' : ''; ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>

                                    <td data-label="Quantity" class="ord-td-qty">
                                        <div class="ord-qty">
                                            <span class="ord-qty-main"><?php echo $qty; ?> <small><?php echo $qty === 1 ? 'unit' : 'units'; ?></small></span>
                                            <?php if ($items_count > 1): ?>
                                                <span class="ord-qty-sub"><i class="fa-solid fa-layer-group"></i><?php echo $items_count; ?> items</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <td data-label="Total Amount" class="ord-td-amount">
                                        <span class="ord-amount">₹<?php echo number_format($order->amount, 2); ?></span>
                                    </td>

                                    <td data-label="Status" class="ord-td-status">
                                        <?php if ($order->status === 'pending'): ?>
                                            <span class="ord-status ord-status-pending" title="Buyer has not completed payment yet">
                                                <i class="fa-solid fa-clock ord-pulse"></i> Awaiting Payment
                                            </span>
                                        <?php elseif ($order->status === 'placed'): ?>
                                            <span class="ord-status ord-status-placed" title="Order received and ready for review">
                                                <i class="fa-solid fa-receipt"></i> Placed
                                            </span>
                                        <?php elseif ($order->status === 'confirmed'): ?>
                                            <span class="ord-status ord-status-confirmed" title="Order has been approved and confirmed">
                                                <i class="fa-solid fa-circle-check"></i> Confirmed
                                            </span>
                                        <?php elseif ($order->status === 'packed'): ?>
                                            <span class="ord-status ord-status-packed" title="Items packed and ready for dispatch">
                                                <i class="fa-solid fa-box-open"></i> Packed
                                            </span>
                                        <?php elseif ($order->status === 'out_for_delivery'): ?>
                                            <span class="ord-status ord-status-out_for_delivery" title="Handed to courier for delivery">
                                                <i class="fa-solid fa-truck-fast"></i> Out for Delivery
                                            </span>
                                        <?php elseif ($order->status === 'delivered'): ?>
                                            <span class="ord-status ord-status-delivered" title="Delivered successfully to customer">
                                                <i class="fa-solid fa-circle-check"></i> Delivered
                                            </span>
                                        <?php elseif ($order->status === 'completed'): ?>
                                            <span class="ord-status ord-status-completed" title="Order finalized and fully completed">
                                                <i class="fa-solid fa-award"></i> Completed
                                            </span>
                                        <?php else: ?>
                                            <span class="ord-status ord-status-cancelled" title="Order cancelled">
                                                <i class="fa-solid fa-circle-xmark"></i> Cancelled
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td data-label="Ordered Date" class="ord-td-date">
                                        <div class="ord-date"><i class="fa-regular fa-calendar"></i><?php echo date('M d, Y', strtotime($order->created_at)); ?></div>
                                        <div class="ord-time"><i class="fa-regular fa-clock"></i><?php echo date('h:i A', strtotime($order->created_at)); ?></div>
                                    </td>

                                    <td data-label="Actions" class="text-end ord-td-actions">
                                        <div class="ord-actions">
                                            <a href="<?php echo base_url('admin/orders/detail/' . $order->id); ?>" class="ord-btn-view" title="View Order Details">
                                                <i class="fa-solid fa-eye"></i><span class="ord-btn-text">View Details</span>
                                            </a>
                                            <button type="button" class="ord-btn-delete btn-delete-order" data-order-id="<?php echo $order->id; ?>" data-order-name="Order #<?php echo $order->id; ?>" title="Delete Order #<?php echo $order->id; ?>">
                                                <i class="fa-solid fa-trash-can"></i><span class="ord-btn-text">Delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($total_rows) && $total_rows > 0):
                $start_record = ($current_page - 1) * 10 + 1;
                $end_record = min($current_page * 10, $total_rows);
            ?>
                <div class="ord-footer">
                    <div class="ord-footer-info">
                        Showing <strong><?php echo $start_record; ?></strong> to <strong><?php echo $end_record; ?></strong> of <strong><?php echo number_format($total_rows); ?></strong> available orders
                    </div>
                    <?php if (!empty($total_pages) && $total_pages > 1): ?>
                        <nav aria-label="Order Page Navigation">
                            <ul class="ord-pagination">
                                <li class="ord-page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                                    <a class="ord-page-link" href="#" data-page="<?php echo $current_page - 1; ?>">
                                        <i class="fa-solid fa-chevron-left"></i> Prev
                                    </a>
                                </li>
                                <?php
                                $start_page = max(1, $current_page - 2);
                                $end_page = min($total_pages, $current_page + 2);
                                for ($i = $start_page; $i <= $end_page; $i++):
                                ?>
                                    <li class="ord-page-item <?php echo ($current_page == $i) ? 'active' : ''; ?>">
                                        <a class="ord-page-link" href="#" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="ord-page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                                    <a class="ord-page-link" href="#" data-page="<?php echo $current_page + 1; ?>">
                                        Next <i class="fa-solid fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Interactive Order Management JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const resetBtn = document.getElementById('resetBtn');
        const refreshBtn = document.getElementById('refreshBtn');
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        const quickStatusBtns = document.querySelectorAll('.ord-pill');
        let debounceTimer;

        function toggleClearBtn() {
            if (clearSearchBtn && searchInput) {
                clearSearchBtn.style.display = searchInput.value ? '' : 'none';
            }
        }

        // Load table data via AJAX
        function loadTable(page = 1) {
            const search = searchInput ? searchInput.value : '';
            const status = statusFilter ? statusFilter.value : '';
            const url = new URL(window.location.href);

            url.searchParams.set('search', search);
            url.searchParams.set('status', status);
            url.searchParams.set('page', page);

            window.history.pushState({}, '', url.toString());

            const container = document.getElementById('table-container');
            if (container) {
                container.style.opacity = '0.5';
                container.style.pointerEvents = 'none';
            }

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
                    if (newTable && container) {
                        container.innerHTML = newTable.innerHTML;
                    }
                    if (container) {
                        container.style.opacity = '1';
                        container.style.pointerEvents = 'auto';
                    }
                    bindDeleteHandlers();
                })
                .catch(err => {
                    console.error('Error fetching table data: ', err);
                    if (container) {
                        container.style.opacity = '1';
                        container.style.pointerEvents = 'auto';
                    }
                });
        }

        // Sync quick status pill active states with status select
        function syncQuickPills(selectedStatus) {
            quickStatusBtns.forEach(btn => {
                btn.classList.toggle('active', btn.getAttribute('data-status') === selectedStatus);
            });
        }

        // Quick Status Pills
        quickStatusBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const chosenStatus = this.getAttribute('data-status');
                if (statusFilter) statusFilter.value = chosenStatus;
                syncQuickPills(chosenStatus);
                loadTable(1);
            });
        });

        // Search (debounced)
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                toggleClearBtn();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => loadTable(1), 300);
            });
        }

        // Clear search
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                if (searchInput) searchInput.value = '';
                toggleClearBtn();
                loadTable(1);
            });
        }

        // Status dropdown
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                syncQuickPills(this.value);
                loadTable(1);
            });
        }

        // Reset
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                if (searchInput) searchInput.value = '';
                if (statusFilter) statusFilter.value = '';
                toggleClearBtn();
                syncQuickPills('');
                loadTable(1);
            });
        }

        // Refresh
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                const spinIcon = this.querySelector('i');
                if (spinIcon) spinIcon.classList.add('fa-spin');
                loadTable(1);
                setTimeout(() => {
                    if (spinIcon) spinIcon.classList.remove('fa-spin');
                }, 600);
            });
        }

        // Pagination
        document.addEventListener('click', function(e) {
            const pageLink = e.target.closest('#table-container .ord-pagination .ord-page-link');
            if (pageLink) {
                e.preventDefault();
                const page = pageLink.getAttribute('data-page');
                if (page) loadTable(page);
            }
        });

        // Delete handler (dsConfirm / dsToast / dsAlert)
        function bindDeleteHandlers() {
            document.querySelectorAll('.btn-delete-order').forEach(btn => {
                const newBtn = btn.cloneNode(true);
                btn.parentNode.replaceChild(newBtn, btn);

                newBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const orderId = this.getAttribute('data-order-id');
                    const orderName = this.getAttribute('data-order-name') || ('Order #' + orderId);

                    dsConfirm({
                        title: 'Delete ' + orderName + '?',
                        text: 'Are you sure you want to permanently delete ' + orderName + '? Associated commissions will be removed and product inventory restored. This action cannot be undone!',
                        icon: 'warning',
                        confirmText: 'Yes, Delete Order',
                        cancelText: 'Cancel',
                        isDangerous: true,
                        onConfirm: function() {
                            const row = document.getElementById('order-row-' + orderId);
                            if (row) {
                                row.style.transition = 'all 0.35s ease';
                                row.style.opacity = '0.4';
                            }

                            fetch('<?php echo base_url("admin/orders/delete/"); ?>' + orderId, {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data && data.status) {
                                        dsToast({
                                            icon: 'success',
                                            title: data.message || (orderName + ' has been deleted successfully.')
                                        });
                                        if (row) {
                                            row.style.transform = 'scale(0.96)';
                                            row.style.opacity = '0';
                                            setTimeout(() => loadTable(1), 350);
                                        } else {
                                            loadTable(1);
                                        }
                                    } else {
                                        if (row) row.style.opacity = '1';
                                        dsAlert({
                                            icon: 'error',
                                            title: 'Delete Failed',
                                            text: (data && data.message) ? data.message : 'Could not delete this order.'
                                        });
                                    }
                                })
                                .catch(err => {
                                    if (row) row.style.opacity = '1';
                                    dsAlert({
                                        icon: 'error',
                                        title: 'Network Error',
                                        text: 'Failed to communicate with server to delete the order.'
                                    });
                                });
                        }
                    });
                });
            });
        }

        bindDeleteHandlers();
    });
</script>