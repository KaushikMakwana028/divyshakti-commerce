<style>
    /* ---------- Brand tokens (unchanged) ---------- */
    :root {
        --text-gold: #B8860B;
        --border-gold: #D4AF37;
        --text-pink: #E91E8C;
        --soft-pink: #F4A6C6;
        --bg-off-white: #FFF8F5;
        --text-black: #2B2B2B;
    }

    .text-pink {
        color: var(--text-pink) !important;
    }

    .text-gold {
        color: var(--text-gold) !important;
    }

    @media (prefers-reduced-motion: reduce) {
        .rtx-page * {
            animation: none !important;
            transition: none !important;
        }
    }

    /* ---------- Layout shell (mobile-first) ---------- */
    .rtx-page {
        font-family: 'Poppins', sans-serif;
        display: flex;
        flex-direction: column;
        gap: 0.9rem;
    }

    @keyframes rtxRise {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .rtx-hero,
    .rtx-toolbar,
    .rtx-chart-shell {
        animation: rtxRise 0.45s ease both;
    }

    .rtx-toolbar {
        animation-delay: 0.05s;
    }

    .rtx-chart-shell {
        animation-delay: 0.1s;
    }

    /* ---------- Hero header ---------- */
    .rtx-hero {
        display: flex;
        flex-direction: column;
        gap: 0.9rem;
    }

    .rtx-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        width: fit-content;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--text-pink);
        background: var(--soft-pink);
        border-radius: 999px;
        padding: 0.3rem 0.75rem 0.3rem 0.6rem;
    }

    .rtx-title {
        margin: 0.5rem 0 0.15rem;
        font-weight: 700;
        color: var(--dark-sidebar, var(--text-black));
        font-size: clamp(1.35rem, 4.5vw, 1.9rem);
        line-height: 1.2;
    }

    .rtx-title::after {
        content: '';
        display: block;
        width: 46px;
        height: 3px;
        margin-top: 0.55rem;
        border-radius: 3px;
        background: linear-gradient(90deg, var(--border-gold), var(--text-pink));
    }

    .rtx-subtitle {
        margin: 0;
        color: #7a7a7a;
        font-size: clamp(0.82rem, 2.4vw, 0.92rem);
    }

    .rtx-hero-actions {
        display: flex;
        gap: 0.6rem;
    }

    .rtx-action {
        flex: 1 1 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border-radius: 11px;
        padding: 0.65rem 0.7rem;
        font-weight: 600;
        font-size: 0.85rem;
        white-space: nowrap;
        border: 1.5px solid transparent;
        text-decoration: none;
        transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
    }

    .rtx-action:active {
        transform: translateY(1px);
    }

    .rtx-action-ghost {
        background: var(--dark-sidebar, #1f2937);
        color: #fff;
    }

    .rtx-action-ghost:hover {
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.16);
    }

    .rtx-action-outline {
        background: #fff;
        border-color: var(--border-gold);
        color: var(--text-black);
    }

    .rtx-action-outline:hover {
        background: var(--soft-pink);
        border-color: var(--text-pink);
        color: var(--text-black);
    }

    .rtx-action span {
        display: none;
    }

    /* ---------- Toolbar ---------- */
    .rtx-toolbar {
        position: relative;
        z-index: 5;
        /* own stacking context, kept above the chart so the autocomplete never renders behind it */
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 14px rgba(43, 43, 43, 0.06);
        padding: 1rem 0.9rem 0.9rem;
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
        overflow: visible;
    }

    .rtx-toolbar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 18px;
        right: 18px;
        height: 3px;
        border-radius: 0 0 4px 4px;
        background: linear-gradient(90deg, var(--border-gold), var(--text-pink));
    }

    .rtx-search {
        position: relative;
    }

    .rtx-search-label {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.76rem;
        font-weight: 700;
        color: #6b7280;
        margin-bottom: 0.4rem;
    }

    .rtx-search-field {
        position: relative;
        display: flex;
        align-items: center;
        border: 1.5px solid #e9e2df;
        border-radius: 11px;
        background: var(--bg-off-white);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .rtx-search-field:focus-within {
        border-color: var(--text-pink);
        box-shadow: 0 0 0 3px rgba(233, 30, 140, 0.12);
    }

    .rtx-search-icon {
        color: #9c9c9c;
        margin: 0 0.7rem 0 0.9rem;
        font-size: 0.9rem;
        flex: none;
    }

    .rtx-search-input {
        flex: 1;
        min-width: 0;
        border: none;
        background: transparent;
        padding: 0.72rem 0.4rem;
        font-size: 0.92rem;
        outline: none;
        font-family: inherit;
    }

    .rtx-search-clear {
        border: none;
        background: transparent;
        color: #9c9c9c;
        padding: 0.4rem 0.8rem;
        cursor: pointer;
        flex: none;
    }

    .rtx-search-clear:hover {
        color: var(--text-pink);
    }

    .rtx-autocomplete {
        display: none;
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(43, 43, 43, 0.16);
        border: 1px solid #f0e9e6;
        max-height: 280px;
        overflow-y: auto;
        z-index: 20;
    }

    .rtx-ac-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        padding: 0.7rem 0.9rem;
        cursor: pointer;
        border-bottom: 1px solid #f5efec;
        scroll-margin: 6px;
    }

    .rtx-ac-item:last-child {
        border-bottom: none;
    }

    .rtx-ac-item:hover,
    .rtx-ac-item:focus-visible,
    .rtx-ac-item.rtx-ac-active {
        background: var(--soft-pink);
    }

    .rtx-ac-name {
        font-weight: 600;
        font-size: 0.87rem;
        color: var(--text-black);
        display: block;
    }

    .rtx-ac-email {
        font-size: 0.76rem;
        color: #8a8a8a;
        display: block;
    }

    .rtx-ac-badge {
        flex: none;
        background: var(--text-pink);
        color: #fff;
        font-size: 0.66rem;
        font-weight: 600;
        padding: 0.28rem 0.6rem;
        border-radius: 999px;
        white-space: nowrap;
    }

    .rtx-ac-empty {
        padding: 1.1rem;
        text-align: center;
        font-size: 0.85rem;
        color: #9c9c9c;
    }

    .rtx-hint {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        color: var(--text-gold);
        background: #FBF4E6;
        border: 1px solid #F0E0B6;
        border-radius: 10px;
        padding: 0.55rem 0.8rem;
    }

    /* ---------- Chart shell ---------- */
    .rtx-chart-shell {
        position: relative;
        z-index: 1;
        /* own stacking context so the org-chart library's internal layers can never climb above the toolbar */
        background-color: var(--bg-off-white);
        background-image: radial-gradient(circle at 1px 1px, rgba(212, 175, 55, 0.16) 1px, transparent 0);
        background-size: 22px 22px;
        border-radius: 18px;
        border: 2px solid var(--border-gold);
        overflow: hidden;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.05);
    }

    .rtx-ornament {
        position: absolute;
        top: 0;
        right: 0;
        width: 140px;
        height: 140px;
        pointer-events: none;
        display: none;
    }

    .rtx-chart {
        width: 100%;
        height: 58vh;
        min-height: 360px;
    }

    .rtx-empty {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.7rem;
        color: #b5aca8;
        pointer-events: none;
        text-align: center;
        padding: 1rem;
    }

    .rtx-empty-badge {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #fff;
        border: 1.5px solid #eee0d8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: var(--soft-pink);
    }

    .rtx-empty p {
        margin: 0;
        font-size: 0.9rem;
    }

    /* ---------- Loading overlay ---------- */
    .rtx-loading-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 248, 245, 0.55);
        backdrop-filter: blur(1px);
        z-index: 40;
        pointer-events: none;
    }

    .rtx-loading-spinner {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: 3px solid #f0e0d8;
        border-top-color: var(--text-pink);
        animation: rtxSpin 0.7s linear infinite;
    }

    @keyframes rtxSpin {
        to {
            transform: rotate(360deg);
        }
    }

    /* ---------- Toast ---------- */
    .rtx-toast {
        position: absolute;
        left: 50%;
        top: 16px;
        transform: translate(-50%, -12px);
        background: #2B2B2B;
        color: #fff;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0.6rem 1rem;
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
        z-index: 120;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease, transform 0.25s ease;
        max-width: 90%;
        text-align: center;
    }

    .rtx-toast.rtx-toast-show {
        opacity: 1;
        transform: translate(-50%, 0);
    }

    .rtx-toast.rtx-toast-error {
        background: #b3261e;
    }

    /* Zoom island */
    .rtx-zoom-island {
        position: absolute;
        left: 50%;
        bottom: 14px;
        transform: translateX(-50%);
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #eee0d8;
        border-radius: 999px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        padding: 0.3rem;
        z-index: 100;
    }

    .rtx-zoom-btn {
        width: 42px;
        height: 42px;
        border: none;
        background: transparent;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-pink);
        transition: background 0.15s ease, transform 0.15s ease;
    }

    .rtx-zoom-btn:nth-child(5) {
        color: var(--text-gold);
    }

    .rtx-zoom-btn:hover {
        background: var(--bg-off-white);
    }

    .rtx-zoom-btn:active {
        transform: scale(0.92);
    }

    .rtx-zoom-divider {
        width: 1px;
        height: 20px;
        background: #eee0d8;
        margin: 0 0.15rem;
    }

    /* ---------- Org chart node polish (library-controlled elements) ---------- */
    [control-node-menu] {
        fill: var(--text-gold) !important;
    }

    .node rect {
        filter: drop-shadow(0px 4px 10px rgba(43, 43, 43, 0.08));
        transition: stroke 0.25s ease, stroke-width 0.25s ease;
    }

    .node:hover rect {
        stroke: var(--text-pink) !important;
        stroke-width: 3px !important;
        cursor: pointer;
    }

    /* Parent-expand button: smooth hover + disabled/loading feedback */
    .parent-expand-btn circle {
        transition: stroke 0.15s ease, fill 0.15s ease;
    }

    .parent-expand-btn:hover circle {
        stroke: var(--text-pink);
    }

    .parent-expand-btn[data-loading="true"] {
        opacity: 0.4;
        pointer-events: none;
    }

    /* Newly-added ancestor node gets a brief highlight pulse so it's obvious where it landed */
    @keyframes rtxNodePulse {
        0% {
            filter: drop-shadow(0 0 0 rgba(233, 30, 140, 0.55));
        }

        70% {
            filter: drop-shadow(0 0 14px rgba(233, 30, 140, 0));
        }

        100% {
            filter: drop-shadow(0 0 0 rgba(233, 30, 140, 0));
        }
    }

    .rtx-node-highlight rect {
        animation: rtxNodePulse 1.1s ease-out;
    }

    /* ---------- Modal polish ---------- */
    .rtx-modal-content {
        border-radius: 18px;
        overflow: hidden;
    }

    .rtx-modal-header {
        background: linear-gradient(135deg, var(--dark-sidebar, #1f2937) 0%, #1f2937 100%);
        border-bottom: 3px solid var(--primary-gold, var(--border-gold));
    }

    #memberDetailModalBody .row.mb-4.align-items-center {
        display: none !important;
    }

    #memberDetailModalBody .card {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        border-radius: 12px !important;
    }

    :focus-visible {
        outline: 2px solid var(--text-pink);
        outline-offset: 2px;
    }

    /* =====================================================
       Tablet and up
    ===================================================== */
    @media (min-width: 576px) {
        .rtx-hero-actions {
            justify-content: flex-end;
        }

        .rtx-action {
            flex: 0 0 auto;
            padding: 0.65rem 1.05rem;
        }

        .rtx-action span {
            display: inline;
        }
    }

    @media (min-width: 768px) {
        .rtx-page {
            gap: 1.25rem;
        }

        .rtx-hero {
            flex-direction: row;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
        }

        .rtx-toolbar {
            flex-direction: row;
            align-items: flex-end;
            padding: 1.05rem 1.2rem;
        }

        .rtx-search {
            flex: 1 1 320px;
            min-width: 240px;
        }

        .rtx-hint {
            flex: 0 0 auto;
            white-space: nowrap;
        }

        .rtx-chart {
            height: 620px;
        }

        .rtx-ornament {
            display: block;
        }

        .rtx-zoom-island {
            left: auto;
            right: 20px;
            bottom: 20px;
            transform: none;
        }
    }

    @media (min-width: 992px) {
        .rtx-chart {
            height: 680px;
        }

        .rtx-title {
            font-size: 1.9rem;
        }
    }

    @media (max-width: 767.98px) {
        .rtx-modal-dialog {
            margin: 0;
            max-width: 100%;
            height: 100dvh;
        }

        .rtx-modal-content {
            border-radius: 0;
            height: 100dvh;
        }
    }
</style>

<div class="rtx-page">

    <!-- Hero header -->
    <header class="rtx-hero">
        <div class="rtx-hero-text">
            <span class="rtx-eyebrow"><i class="fa-solid fa-diagram-project"></i> Network Explorer</span>
            <h1 class="rtx-title">Referral Tree Network</h1>
            <p class="rtx-subtitle">Trace every member's upline and downline, level by level.</p>
        </div>
        <div class="rtx-hero-actions">
            <button id="resetRootBtn" class="rtx-action rtx-action-ghost" title="Reset to Root">
                <i class="fa-solid fa-house-user"></i><span>Reset to Root</span>
            </button>
            <a href="<?php echo base_url('admin/members'); ?>" class="rtx-action rtx-action-outline" title="Back to Members list">
                <i class="fa-solid fa-users"></i><span>Back to Members</span>
            </a>
        </div>
    </header>

    <!-- Toolbar: search + hint -->
    <div class="rtx-toolbar">
        <div class="rtx-search">
            <label class="rtx-search-label" for="networkSearch">
                <i class="fa-solid fa-magnifying-glass"></i> Search Member
            </label>
            <div class="rtx-search-field">
                <i class="fa-solid fa-magnifying-glass rtx-search-icon"></i>
                <input type="text" id="networkSearch" class="rtx-search-input" placeholder="Search by User ID, name, email, or referral code…" autocomplete="off">
                <button type="button" id="networkSearchClear" class="rtx-search-clear" style="display:none;" aria-label="Clear search">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div id="autocompleteResults" class="rtx-autocomplete"></div>
        </div>

        <div class="rtx-hint">
            <i class="fa-solid fa-circle-info"></i>
            <span>Tap a node to view the audit profile</span>
        </div>
    </div>

    <!-- Chart -->
    <div class="rtx-chart-shell">

        <!-- Decorative brand ornament (non-interactive) -->
        <svg class="rtx-ornament" viewBox="0 0 200 200" aria-hidden="true" focusable="false">
            <circle cx="200" cy="0" r="60" fill="none" stroke="var(--border-gold)" stroke-width="1" opacity="0.35"></circle>
            <circle cx="200" cy="0" r="90" fill="none" stroke="var(--border-gold)" stroke-width="1" stroke-dasharray="2 6" opacity="0.3"></circle>
            <circle cx="200" cy="0" r="120" fill="none" stroke="var(--text-pink)" stroke-width="1" stroke-dasharray="1 8" opacity="0.22"></circle>
        </svg>

        <div id="tree" class="rtx-chart"></div>

        <div class="rtx-empty" id="rtnChartEmpty" style="display:none;">
            <div class="rtx-empty-badge"><i class="fa-solid fa-users-slash"></i></div>
            <p>No members found for this view.</p>
        </div>

        <!-- Loading overlay shown while a tree fetch is in-flight -->
        <div class="rtx-loading-overlay" id="rtnChartLoading" style="display:none;">
            <div class="rtx-loading-spinner"></div>
        </div>

        <!-- Toast for non-blocking errors -->
        <div class="rtx-toast" id="rtnToast" role="status" aria-live="polite"></div>

        <!-- Zoom controls -->
        <div class="rtx-zoom-island">
            <button id="zoomInBtn" class="rtx-zoom-btn" title="Zoom In" aria-label="Zoom in">
                <i class="fa-solid fa-plus"></i>
            </button>
            <span class="rtx-zoom-divider" aria-hidden="true"></span>
            <button id="zoomOutBtn" class="rtx-zoom-btn" title="Zoom Out" aria-label="Zoom out">
                <i class="fa-solid fa-minus"></i>
            </button>
            <span class="rtx-zoom-divider" aria-hidden="true"></span>
            <button id="zoomFitBtn" class="rtx-zoom-btn" title="Fit to Screen" aria-label="Fit to screen">
                <i class="fa-solid fa-expand"></i>
            </button>
        </div>
    </div>
</div>

<!-- Member Details View Modal (reuses existing member details controller output) -->
<div class="modal fade" id="memberDetailModal" tabindex="-1" aria-labelledby="memberDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable rtx-modal-dialog">
        <div class="modal-content border-0 shadow-lg rtx-modal-content">
            <div class="modal-header text-white p-3 rtx-modal-header">
                <h5 class="modal-title fw-bold" id="memberDetailModalLabel">
                    <i class="fa-solid fa-user-gear me-2 text-warning"></i> Member Audit Profile
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light" id="memberDetailModalBody" style="min-height: 350px;">
                <!-- Content will load dynamically here -->
            </div>
        </div>
    </div>
</div>

<!-- Load Balkangraph OrgChart with Dual Fallback (Official CDN + Local Server) -->
<script src="https://balkan.app/js/OrgChart.js"></script>
<script>
    if (typeof OrgChart === 'undefined') {
        document.write('<script src="<?php echo base_url("assets/js/orgchart.js"); ?>"><\/script>');
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const initialUserId = <?php echo json_encode($initial_user_id); ?>;
        const chartEmptyEl = document.getElementById('rtnChartEmpty');
        const chartLoadingEl = document.getElementById('rtnChartLoading');
        const toastEl = document.getElementById('rtnToast');

        // Verify OrgChart library is loaded
        if (typeof OrgChart === 'undefined') {
            console.error('OrgChart library failed to load from both CDN and local server.');
            if (chartEmptyEl) {
                chartEmptyEl.innerHTML = `
                    <div class="rtx-empty-badge" style="background:rgba(239,68,68,0.12);color:#ef4444;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <p style="font-weight:600;color:#dc2626;margin-bottom:4px;">OrgChart Library Not Available</p>
                    <p style="color:#64748b;font-size:0.85rem;">Please check your internet connection or verify <code>assets/js/orgchart.js</code> is uploaded to the server.</p>
                `;
                chartEmptyEl.style.display = 'flex';
            }
            return;
        }

        // ---- State ----
        let chart = null; // current live OrgChart instance
        let currentNodes = []; // complete tracker of nodes currently rendered in chart
        let loadedNodesMap = {}; // tracks nodes whose children have already been fetched
        const pendingChildLoads = new Set(); // node ids currently mid-fetch for children (guards double-click)
        const pendingParentLoads = new Set(); // node ids currently mid-fetch for their parent

        let toastTimer = null;

        function showToast(message, isError) {
            clearTimeout(toastTimer);
            toastEl.textContent = message;
            toastEl.classList.toggle('rtx-toast-error', !!isError);
            toastEl.classList.add('rtx-toast-show');
            toastTimer = setTimeout(() => {
                toastEl.classList.remove('rtx-toast-show');
            }, 3200);
        }

        function setChartLoading(isLoading) {
            chartLoadingEl.style.display = isLoading ? 'flex' : 'none';
        }

        function fetchJSON(url, options) {
            return fetch(url, options).then(res => {
                if (!res.ok) {
                    throw new Error('Request failed (' + res.status + ')');
                }
                return res.json();
            });
        }

        // Generate instant inline SVG avatar with member's first initial (0ms latency, zero external network dependency)
        function getAvatarDataUri(name) {
            const initial = (name || 'M').trim().charAt(0).toUpperCase();
            const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128"><rect width="128" height="128" rx="64" fill="#F4A6C6"/><text x="50%" y="54%" font-family="'Poppins', sans-serif" font-weight="700" font-size="52" fill="#2B2B2B" text-anchor="middle" dominant-baseline="middle">${initial}</text></svg>`;
            return 'data:image/svg+xml;utf8,' + encodeURIComponent(svg);
        }

        // Define circular clipping and premium theme card SVG for nodes
        OrgChart.templates.divyShakti = Object.assign({}, OrgChart.templates.ana);
        OrgChart.templates.divyShakti.size = [250, 124];

        // Background card shape: white card pop fill, gold border (shifted down 14px to leave room for the parent button above it)
        OrgChart.templates.divyShakti.node =
            '<rect x="0" y="14" height="110" width="250" fill="#ffffff" stroke="#D4AF37" stroke-width="2" rx="15" ry="15"></rect>' +
            '<line x1="15" y1="94" x2="235" y2="94" stroke="#F4A6C6" stroke-width="1.5"></line>';

        // Parent expand button (▲): displayed above card when member has an upline sponsor not yet rendered
        OrgChart.templates.divyShakti.parent_btn =
            '<g class="parent-expand-btn" data-action="expand-parent" style="cursor:pointer; display: {val};">' +
            '<circle cx="125" cy="12" r="12" fill="#ffffff" stroke="#D4AF37" stroke-width="1.5"></circle>' +
            '<text x="125" y="16" text-anchor="middle" style="font-size: 12px; font-weight: bold; fill: #E91E8C; font-family:\'Poppins\',sans-serif; pointer-events: none;">▲</text>' +
            '</g>';

        // Circular clipping container for profile images using unique randId
        OrgChart.templates.divyShakti.img_0 =
            '<clipPath id="{randId}">' +
            '<circle cx="45" cy="54" r="30"></circle>' +
            '</clipPath>' +
            '<image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="15" y="24" width="60" height="60"></image>';

        // Typography bindings
        OrgChart.templates.divyShakti.field_0 =
            '<text text-overflow="ellipsis" width="150" style="font-size: 13px; font-weight: 700; font-family:\'Poppins\',sans-serif;" fill="#2B2B2B" x="85" y="46">{val}</text>'; // Name
        OrgChart.templates.divyShakti.field_1 =
            '<text text-overflow="ellipsis" width="150" style="font-size: 11px; font-family:\'Poppins\',sans-serif;" fill="#E91E9C" x="85" y="64">Code: {val}</text>'; // Referral code
        OrgChart.templates.divyShakti.field_2 =
            '<text text-overflow="ellipsis" width="150" style="font-size: 11px; font-weight: 600; font-family:\'Poppins\',sans-serif;" fill="#B8860B" x="85" y="82">Bal: ₹{val}</text>'; // Wallet Balance

        // Format a user object into OrgChart node structure
        function formatNode(userObj, parentId) {
            let photoUrl = userObj.profile_image;
            if (!photoUrl) {
                photoUrl = getAvatarDataUri(userObj.name);
            }

            let displayName = (userObj.name || '').trim();
            if (displayName.length > 20) {
                displayName = displayName.substring(0, 19).trim() + '…';
            }

            const rawParentId = userObj.parent_id !== null ? parseInt(userObj.parent_id) : null;

            const node = {
                id: parseInt(userObj.id),
                _raw_parent_id: rawParentId,
                name: displayName,
                referral_code: userObj.referral_code,
                wallet_balance: parseFloat(userObj.wallet_balance || 0).toFixed(2),
                profile_image: photoUrl
            };

            if (parentId !== undefined && parentId !== null) {
                node.pid = parseInt(parentId);
            }

            // If the member has an upline sponsor in DB that is not yet visible in currentNodes, show ▲ button
            const parentInChart = rawParentId ? currentNodes.some(n => n.id === rawParentId) : false;
            if (rawParentId && !parentInChart) {
                node.parent_btn = 'block';
            } else {
                node.parent_btn = 'none';
            }

            // Set dummy child IDs to render OrgChart's down/expand badge with the exact child count
            const childCount = parseInt(userObj.children_count) || (userObj.has_children ? 1 : 0);
            if (childCount > 0) {
                node.cids = [];
                for (let i = 0; i < childCount; i++) {
                    node.cids.push(userObj.id + '_dummy_child_' + i);
                }
            }

            return node;
        }

        // Initialize OrgChart instance
        function initChart(nodesArray) {
            currentNodes = [...nodesArray];
            loadedNodesMap = {};
            pendingChildLoads.clear();
            pendingParentLoads.clear();

            chartEmptyEl.style.display = nodesArray.length === 0 ? 'flex' : 'none';
            if (nodesArray.length === 0) {
                if (chart) {
                    chart.destroy();
                    chart = null;
                }
                return;
            }

            if (chart) {
                chart.destroy();
                chart = null;
            }

            chart = new OrgChart(document.getElementById("tree"), {
                template: "divyShakti",
                mouseScroller: OrgChart.action.zoom,
                enableDragDrop: false,
                enableSearch: false,
                nodeMouseClick: OrgChart.action.none,
                nodeBinding: {
                    field_0: "name",
                    field_1: "referral_code",
                    field_2: "wallet_balance",
                    img_0: "profile_image",
                    parent_btn: "parent_btn"
                },
                nodes: nodesArray
            });

            // Handle Expand load-on-demand: triggered when user clicks the down button / [count] badge
            chart.onDemand(function(args) {
                const parentId = parseInt(args.id);

                if (loadedNodesMap[parentId] || pendingChildLoads.has(parentId)) return;
                pendingChildLoads.add(parentId);

                fetchJSON(`<?php echo base_url('admin/members/getReferralTree/'); ?>${parentId}`)
                    .then(res => {
                        if (res.status && res.children) {
                            const newNodes = res.children
                                .filter(child => !currentNodes.some(n => n.id === parseInt(child.id)))
                                .map(child => formatNode(child, parentId));

                            const filteredCids = args.ids.filter(id => !id.toString().includes('_dummy_child'));

                            chart.addNodes(parentId, newNodes, function() {
                                loadedNodesMap[parentId] = true;
                                pendingChildLoads.delete(parentId);

                                // Append new child nodes to currentNodes tracker
                                newNodes.forEach(n => currentNodes.push(n));

                                // Remove dummy placeholder cids from parent node
                                const parentNode = chart.get(parentId);
                                if (parentNode && parentNode.cids) {
                                    delete parentNode.cids;
                                    chart.updateNode(parentNode);
                                }
                                const parentInArr = currentNodes.find(n => n.id === parentId);
                                if (parentInArr && parentInArr.cids) {
                                    delete parentInArr.cids;
                                }

                                if (newNodes.length > 0) {
                                    chart.moveNodesToVisibleAreaAfterExpand(parentId, filteredCids.concat(newNodes.map(n => n.id)));
                                }
                            });
                        } else {
                            pendingChildLoads.delete(parentId);
                        }
                    })
                    .catch(err => {
                        pendingChildLoads.delete(parentId);
                        console.error("Error lazy loading downline: ", err);
                        showToast("Couldn't load this member's downline. Please try again.", true);
                    });
            });

            // Bind click event on nodes
            chart.on('click', function(sender, args) {
                // Parent-expand (▲) button click
                const expandBtn = args.event.target.closest('[data-action="expand-parent"]');
                if (expandBtn) {
                    expandParentNodeById(args.node.id, expandBtn);
                    return;
                }

                // Exclude clicks on the built-in +/- children toggle badge
                if (args.event.target.tagName === 'circle' ||
                    (args.event.target.tagName === 'text' && args.event.target.closest('g[transform]'))) {
                    return;
                }

                // Show Member Audit Profile modal
                showMemberProfileModal(args.node.id);
            });
        }

        // Fetch and show member details modal dynamically
        function showMemberProfileModal(memberId) {
            const modalBody = document.getElementById('memberDetailModalBody');
            modalBody.innerHTML = `
                <div class="text-center py-5">
                     <div class="spinner-border text-pink" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mt-2 fw-semibold">Fetching member audit profile details...</p>
                </div>
            `;

            const detailModal = new bootstrap.Modal(document.getElementById('memberDetailModal'));
            detailModal.show();

            fetch(`<?php echo base_url('admin/members/view/'); ?>${memberId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Request failed (' + res.status + ')');
                    return res.text();
                })
                .then(html => {
                    modalBody.innerHTML = html;
                })
                .catch(err => {
                    modalBody.innerHTML = `
                    <div class="alert alert-danger text-center py-4">
                        <i class="fa-solid fa-triangle-exclamation fs-3 mb-2 d-block"></i>
                        <strong>Error fetching details!</strong> Please check database status and try again.
                    </div>
                `;
                    console.error("AJAX error fetching details view: ", err);
                });
        }

        // Load referral tree:
        // - When specificUserId is given (search): SHOW ONLY THAT USER'S CARD (no ancestors above, no children below)
        // - When specificUserId is null (reset): SHOW TOPMOST ROOT MEMBERS
        function loadInitialTree(specificUserId = null) {
            setChartLoading(true);

            const url = specificUserId ?
                `<?php echo base_url('admin/members/getReferralTree/'); ?>${specificUserId}` :
                `<?php echo base_url('admin/members/getReferralTree'); ?>`;

            fetchJSON(url)
                .then(res => {
                    if (res.status) {
                        let nodes = [];
                        currentNodes = [];

                        if (specificUserId && res.user) {
                            // SHOW ONLY THE SEARCHED MEMBER'S CARD!
                            // Do not show ancestors above or children below automatically.
                            // User clicks ▲ to reveal parent, or ▼ (count badge) to reveal children.
                            nodes.push(formatNode(res.user, null));
                        } else if (res.children && res.children.length > 0) {
                            // Root view: render root members (parent_id = NULL)
                            res.children.forEach(rootMember => {
                                nodes.push(formatNode(rootMember, null));
                            });
                        }

                        initChart(nodes);

                        // If a specific user was searched, smoothly center & highlight their card
                        if (specificUserId && res.user) {
                            requestAnimationFrame(() => {
                                try {
                                    chart.center(res.user.id);
                                } catch(e) {}
                                const userEl = document.querySelector('.node[node-id="' + res.user.id + '"]');
                                if (userEl) {
                                    userEl.classList.add('rtx-node-highlight');
                                    setTimeout(() => userEl.classList.remove('rtx-node-highlight'), 1800);
                                }
                            });
                        }
                    } else {
                        initChart([]);
                        showToast(res.message || "Member not found.", true);
                    }
                })
                .catch(err => {
                    console.error("Error loading referral tree: ", err);
                    initChart([]);
                    showToast("Couldn't load the network. Please check your connection and try again.", true);
                })
                .finally(() => setChartLoading(false));
        }

        // Expand parent node action (▲): reveals the upline parent member card directly above this member
        function expandParentNodeById(nodeId, buttonEl) {
            const childId = parseInt(nodeId);
            if (pendingParentLoads.has(childId)) return;
            pendingParentLoads.add(childId);
            if (buttonEl) buttonEl.setAttribute('data-loading', 'true');

            // Find child node from currentNodes tracker
            const childNode = currentNodes.find(n => n.id === childId);
            const rawParentId = childNode ? childNode._raw_parent_id : null;

            if (!rawParentId) {
                showToast("This member does not have an upline sponsor.", false);
                pendingParentLoads.delete(childId);
                if (buttonEl) buttonEl.removeAttribute('data-loading');
                return;
            }

            // If parent is already rendered in the chart, hide ▲ and re-center
            if (currentNodes.some(n => n.id === rawParentId)) {
                if (childNode) {
                    childNode.pid = rawParentId;
                    childNode.parent_btn = 'none';
                    if (chart) chart.updateNode(childNode);
                }
                pendingParentLoads.delete(childId);
                if (buttonEl) buttonEl.removeAttribute('data-loading');
                try { chart.center(rawParentId); } catch(e) {}
                return;
            }

            // Fetch upline parent details
            fetchJSON(`<?php echo base_url('admin/members/getReferralTree/'); ?>${rawParentId}`)
                .then(res => {
                    if (res.status && res.user) {
                        const parentUser = res.user;

                        // 1. Update the child node: link its pid to parentUser.id and hide parent button
                        if (childNode) {
                            childNode.pid = parseInt(parentUser.id);
                            childNode.parent_btn = 'none';
                        }

                        // 2. Format the new parent node
                        const parentPid = (parentUser.parent_id && currentNodes.some(n => n.id === parseInt(parentUser.parent_id))) 
                            ? parseInt(parentUser.parent_id) 
                            : null;
                        const parentNode = formatNode(parentUser, parentPid);

                        // Adjust dummy cids on parent: this child is already in the chart,
                        // so remaining unexpanded children count = Math.max(0, total - 1)
                        const totalParentChildren = parseInt(parentUser.children_count) || (parentUser.has_children ? 1 : 0);
                        const otherChildrenCount = Math.max(0, totalParentChildren - 1);
                        if (otherChildrenCount > 0) {
                            parentNode.cids = [];
                            for (let i = 0; i < otherChildrenCount; i++) {
                                parentNode.cids.push(parentUser.id + '_dummy_child_' + i);
                            }
                        } else {
                            delete parentNode.cids;
                        }

                        // 3. Add parent node to currentNodes (at beginning)
                        currentNodes.unshift(parentNode);

                        // 4. Reload chart with the updated tree structure
                        if (chart) {
                            chart.load(currentNodes, function() {
                                try {
                                    chart.center(parentUser.id);
                                } catch(e) {}

                                setTimeout(() => {
                                    const parentEl = document.querySelector('.node[node-id="' + parentUser.id + '"]');
                                    if (parentEl) {
                                        parentEl.classList.add('rtx-node-highlight');
                                        setTimeout(() => parentEl.classList.remove('rtx-node-highlight'), 1800);
                                    }
                                }, 150);
                            });
                        }
                    } else {
                        showToast(res.message || "Couldn't find this member's upline sponsor.", true);
                    }
                })
                .catch(err => {
                    console.error("Error expanding parent node: ", err);
                    showToast("Couldn't load the parent member. Please try again.", true);
                })
                .finally(() => {
                    pendingParentLoads.delete(childId);
                    if (buttonEl) buttonEl.removeAttribute('data-loading');
                });
        }

        // Autocomplete Search
        const searchInput = document.getElementById('networkSearch');
        const searchClearBtn = document.getElementById('networkSearchClear');
        const resultsBox = document.getElementById('autocompleteResults');
        let debounceTimer;
        let activeAcIndex = -1;

        // Selects a member from search suggestions
        function selectAutocompleteUser(user) {
            searchInput.value = user.name;
            searchClearBtn.style.display = 'inline-flex';
            resultsBox.style.display = 'none';
            activeAcIndex = -1;
            loadInitialTree(user.id);
        }

        function setActiveAcIndex(index, items) {
            items.forEach(el => el.classList.remove('rtx-ac-active'));
            if (index >= 0 && index < items.length) {
                items[index].classList.add('rtx-ac-active');
                items[index].scrollIntoView({
                    block: 'nearest'
                });
            }
            activeAcIndex = index;
        }

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const query = this.value.trim();
            searchClearBtn.style.display = query.length ? 'inline-flex' : 'none';
            activeAcIndex = -1;

            if (query.length < 2) {
                resultsBox.style.display = 'none';
                return;
            }

            debounceTimer = setTimeout(() => {
                fetchJSON(`<?php echo base_url('admin/members/search?query='); ?>${encodeURIComponent(query)}`)
                    .then(res => {
                        resultsBox.innerHTML = '';
                        activeAcIndex = -1;
                        if (res.status && res.results.length > 0) {
                            res.results.forEach(user => {
                                const item = document.createElement('div');
                                item.className = 'rtx-ac-item';
                                item.innerHTML = `
                                    <div>
                                        <div style="display:flex; align-items:center; gap:6px;">
                                            <span class="rtx-ac-name">${user.name}</span>
                                            ${user.custom_id ? `<span class="badge bg-dark-subtle text-dark border font-monospace" style="font-size:10px; padding:2px 6px;">ID: ${user.custom_id}</span>` : ''}
                                        </div>
                                        <span class="rtx-ac-email">${user.email ? user.email : (user.phone ? user.phone : 'Not provided')}</span>
                                    </div>
                                    <span class="rtx-ac-badge">Code: ${user.referral_code}</span>
                                `;
                                item.addEventListener('mouseenter', function() {
                                    setActiveAcIndex(Array.from(resultsBox.querySelectorAll('.rtx-ac-item')).indexOf(item), Array.from(resultsBox.querySelectorAll('.rtx-ac-item')));
                                });
                                item.addEventListener('click', function() {
                                    selectAutocompleteUser(user);
                                });
                                resultsBox.appendChild(item);
                            });
                        } else {
                            resultsBox.innerHTML = '<div class="rtx-ac-empty">No members found matching query</div>';
                        }
                        resultsBox.style.display = 'block';
                    })
                    .catch(err => console.error("Autocomplete fetch error: ", err));
            }, 250);
        });

        // Keyboard navigation and Enter-to-select
        searchInput.addEventListener('keydown', function(e) {
            if (resultsBox.style.display !== 'block') return;
            const items = Array.from(resultsBox.querySelectorAll('.rtx-ac-item'));
            if (items.length === 0) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                setActiveAcIndex((activeAcIndex + 1) % items.length, items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                setActiveAcIndex((activeAcIndex - 1 + items.length) % items.length, items);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                const targetIndex = activeAcIndex > -1 ? activeAcIndex : 0;
                if (items[targetIndex]) {
                    items[targetIndex].dispatchEvent(new MouseEvent('click', { bubbles: true }));
                }
            } else if (e.key === 'Escape') {
                resultsBox.style.display = 'none';
                activeAcIndex = -1;
            }
        });

        searchClearBtn.addEventListener('click', function() {
            searchInput.value = '';
            searchClearBtn.style.display = 'none';
            resultsBox.style.display = 'none';
            activeAcIndex = -1;
            searchInput.focus();
        });

        // Hide results list when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
                resultsBox.style.display = 'none';
            }
        });

        // Zoom Overlay Button Click Event bindings
        document.getElementById('zoomInBtn').addEventListener('click', function() {
            if (chart) chart.zoom(true);
        });
        document.getElementById('zoomOutBtn').addEventListener('click', function() {
            if (chart) chart.zoom(false);
        });
        document.getElementById('zoomFitBtn').addEventListener('click', function() {
            if (chart) chart.fit();
        });

        // Reset Root Tree Action
        document.getElementById('resetRootBtn').addEventListener('click', function() {
            searchInput.value = '';
            searchClearBtn.style.display = 'none';
            resultsBox.style.display = 'none';
            loadInitialTree();
        });

        // Initial Load
        if (initialUserId) {
            loadInitialTree(initialUserId);
        } else {
            loadInitialTree();
        }
    });
</script>
