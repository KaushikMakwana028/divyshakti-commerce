<style>
    /* =====================================================
   Edit Member Profile — scoped styles (.emp- prefix)
   ===================================================== */
    .emp-wrap {
        --emp-gold: #c89738;
        --emp-gold-dark: #a97c26;
        --emp-dark: #111827;
        --emp-dark-2: #1f2937;
        --emp-pink: #ec407a;
        --emp-blue: #3b82f6;
        --emp-green: #10b981;
        --emp-red: #ef4444;
        --emp-teal: #0e7490;
        --emp-bg: #f4f6fa;
        --emp-card-bg: #ffffff;
        --emp-border: #e6e9f0;
        --emp-text: #1f2937;
        --emp-muted: #7c8697;
        --emp-radius: 18px;
        --emp-radius-sm: 12px;
        --emp-shadow: 0 4px 18px rgba(17, 24, 39, 0.06);
        --emp-shadow-hover: 0 10px 30px rgba(17, 24, 39, 0.10);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        color: var(--emp-text);
        background: var(--emp-bg);
        padding-bottom: 40px;
    }

    .emp-wrap * {
        box-sizing: border-box;
    }

    /* ---------- Top bar ---------- */
    .emp-topbar {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 24px;
    }

    .emp-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        font-size: .82rem;
        color: var(--emp-muted);
        margin-bottom: 10px;
    }

    .emp-breadcrumb a {
        color: var(--emp-blue);
        text-decoration: none;
        font-weight: 500;
    }

    .emp-breadcrumb a:hover {
        text-decoration: underline;
    }

    .emp-breadcrumb .sep {
        opacity: .5;
    }

    .emp-breadcrumb .active {
        color: var(--emp-text);
        font-weight: 600;
    }

    .emp-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: clamp(1.35rem, 2vw, 1.8rem);
        font-weight: 800;
        color: var(--emp-dark);
        margin: 0 0 6px 0;
        letter-spacing: -.02em;
    }

    .emp-title .emp-title-icon {
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--emp-gold) 0%, var(--emp-gold-dark) 100%);
        color: #fff;
        font-size: 1.05rem;
        box-shadow: 0 6px 14px rgba(200, 151, 56, .35);
    }

    .emp-subtitle {
        color: var(--emp-muted);
        font-size: .9rem;
        margin: 0;
    }

    .emp-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .emp-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 12px;
        font-weight: 600;
        font-size: .88rem;
        border: 1.5px solid transparent;
        cursor: pointer;
        text-decoration: none;
        transition: all .18s ease;
        white-space: nowrap;
    }

    .emp-btn-outline {
        background: #fff;
        border-color: var(--emp-border);
        color: var(--emp-dark-2);
    }

    .emp-btn-outline:hover {
        border-color: var(--emp-dark-2);
        background: var(--emp-dark-2);
        color: #fff;
        transform: translateY(-1px);
    }

    .emp-btn-dark {
        background: var(--emp-dark-2);
        color: #fff;
    }

    .emp-btn-dark:hover {
        background: var(--emp-dark);
        transform: translateY(-1px);
    }

    .emp-btn-gold {
        background: linear-gradient(135deg, var(--emp-gold) 0%, var(--emp-gold-dark) 100%);
        color: #1c1204;
        box-shadow: 0 6px 16px rgba(200, 151, 56, .35);
    }

    .emp-btn-gold:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(200, 151, 56, .45);
    }

    .emp-btn-danger-outline {
        background: #fff;
        border-color: #fbdada;
        color: var(--emp-red);
    }

    .emp-btn-danger-outline:hover {
        background: var(--emp-red);
        border-color: var(--emp-red);
        color: #fff;
    }

    .emp-btn-primary-outline {
        background: #fff;
        border-color: #bfd6fe;
        color: var(--emp-blue);
    }

    .emp-btn-primary-outline:hover {
        background: var(--emp-blue);
        border-color: var(--emp-blue);
        color: #fff;
    }

    .emp-btn-block {
        width: 100%;
        justify-content: center;
    }

    .emp-btn-lg {
        padding: 14px 20px;
        font-size: .95rem;
    }

    /* ---------- Alerts ---------- */
    .emp-alert {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 18px;
        border-radius: var(--emp-radius-sm);
        margin-bottom: 20px;
        font-size: .88rem;
        font-weight: 500;
        box-shadow: var(--emp-shadow);
        animation: emp-slidein .3s ease;
    }

    @keyframes emp-slidein {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .emp-alert-error {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .emp-alert-success {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .emp-alert .emp-alert-body {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .emp-alert-close {
        background: transparent;
        border: none;
        font-size: 1rem;
        cursor: pointer;
        color: inherit;
        opacity: .6;
        line-height: 1;
    }

    .emp-alert-close:hover {
        opacity: 1;
    }

    /* ---------- Profile header banner ---------- */
    .emp-banner {
        position: relative;
        border-radius: var(--emp-radius);
        padding: 26px 28px;
        margin-bottom: 26px;
        background: linear-gradient(120deg, var(--emp-dark) 0%, var(--emp-dark-2) 55%, #2c3646 100%);
        color: #fff;
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        justify-content: space-between;
        align-items: center;
        overflow: hidden;
        box-shadow: 0 14px 34px rgba(17, 24, 39, .28);
    }

    .emp-banner::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--emp-gold), var(--emp-pink), var(--emp-blue));
    }

    .emp-banner::after {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(200, 151, 56, .18) 0%, transparent 70%);
        top: -100px;
        right: -60px;
        pointer-events: none;
    }

    .emp-banner-left {
        display: flex;
        align-items: center;
        gap: 16px;
        z-index: 1;
        position: relative;
    }

    .emp-avatar-wrap {
        width: 68px;
        height: 68px;
        flex-shrink: 0;
        position: relative;
    }

    .emp-avatar-wrap img,
    .emp-avatar-fallback {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, .85);
        box-shadow: 0 4px 14px rgba(0, 0, 0, .35);
    }

    .emp-avatar-fallback {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--emp-gold), var(--emp-gold-dark));
        font-weight: 800;
        font-size: 1.5rem;
        color: #fff;
    }

    .emp-banner-name {
        font-size: 1.25rem;
        font-weight: 800;
        margin: 0 0 6px 0;
    }

    .emp-banner-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        font-size: .82rem;
        color: rgba(255, 255, 255, .72);
    }

    .emp-banner-meta strong {
        color: #fff;
        font-weight: 700;
    }

    .emp-banner-meta .dot {
        opacity: .4;
    }

    .emp-wallet {
        color: #6ee7b7 !important;
    }

    /* ---------- Grid layout ---------- */
    .emp-grid {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 24px;
        align-items: start;
    }

    @media (max-width:991px) {
        .emp-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ---------- Card ---------- */
    .emp-card {
        background: var(--emp-card-bg);
        border-radius: var(--emp-radius);
        box-shadow: var(--emp-shadow);
        margin-bottom: 22px;
        overflow: hidden;
        transition: box-shadow .2s ease;
        border: 1px solid var(--emp-border);
    }

    .emp-card:hover {
        box-shadow: var(--emp-shadow-hover);
    }

    .emp-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 18px 22px;
        border-bottom: 1px solid var(--emp-border);
    }

    .emp-card-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .emp-card-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .emp-card-title {
        font-weight: 700;
        font-size: 1rem;
        margin: 0;
        color: var(--emp-dark);
    }

    .emp-card-body {
        padding: 22px;
    }

    .emp-badge-soft {
        font-size: .75rem;
        font-weight: 600;
        background: #f3f4f8;
        color: var(--emp-muted);
        padding: 5px 12px;
        border-radius: 999px;
        border: 1px solid var(--emp-border);
    }

    /* ---------- Form elements ---------- */
    .emp-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    .emp-row.emp-row-1 {
        grid-template-columns: 1fr;
    }

    @media (max-width:575px) {
        .emp-row {
            grid-template-columns: 1fr;
        }
    }

    .emp-field {
        margin-bottom: 4px;
    }

    .emp-label {
        display: block;
        font-weight: 600;
        font-size: .83rem;
        color: var(--emp-dark-2);
        margin-bottom: 7px;
    }

    .emp-label .req {
        color: var(--emp-red);
    }

    .emp-label .opt {
        color: var(--emp-muted);
        font-weight: 500;
    }

    .emp-input-group {
        display: flex;
        align-items: stretch;
        border: 1.5px solid var(--emp-border);
        border-radius: var(--emp-radius-sm);
        overflow: hidden;
        background: #fff;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .emp-input-group:focus-within {
        border-color: var(--emp-gold);
        box-shadow: 0 0 0 4px rgba(200, 151, 56, .14);
    }

    .emp-input-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        flex-shrink: 0;
        background: #f7f8fb;
        color: var(--emp-muted);
        border-right: 1.5px solid var(--emp-border);
        font-size: .88rem;
    }

    .emp-input-group input,
    .emp-input-group select {
        border: none;
        outline: none;
        padding: 10px 12px;
        font-size: .88rem;
        width: 100%;
        background: transparent;
        color: var(--emp-text);
        font-family: inherit;
    }

    .emp-input-group.emp-select {
        padding: 0;
    }

    .emp-input-group.emp-select select {
        padding: 10px 12px;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%237c8697' stroke-width='1.6' fill='none' fill-rule='evenodd'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 32px;
    }

    .emp-input-group.plain {
        border: 1.5px solid var(--emp-border);
    }

    .emp-input-group.plain input,
    .emp-input-group.plain select {
        padding: 10px 14px;
    }

    .emp-textarea {
        width: 100%;
        border: 1.5px solid var(--emp-border);
        border-radius: var(--emp-radius-sm);
        padding: 12px 14px;
        font-size: .88rem;
        font-family: inherit;
        resize: vertical;
        min-height: 90px;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .emp-textarea:focus {
        outline: none;
        border-color: var(--emp-gold);
        box-shadow: 0 0 0 4px rgba(200, 151, 56, .14);
    }

    .emp-hint {
        font-size: .74rem;
        color: var(--emp-muted);
        margin-top: 6px;
        line-height: 1.4;
    }

    .emp-icon-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 0 14px;
        border: none;
        background: #f3f4f8;
        color: var(--emp-dark-2);
        font-size: .82rem;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        border-left: 1.5px solid var(--emp-border);
        transition: background .15s ease;
    }

    .emp-icon-btn:hover {
        background: #e9ebf1;
    }

    .emp-icon-btn.danger {
        color: var(--emp-red);
    }

    .emp-icon-btn.danger:hover {
        background: #fef2f2;
    }

    .emp-icon-btn.primary {
        color: var(--emp-blue);
    }

    .emp-icon-btn.primary:hover {
        background: #eff6ff;
    }

    /* gender toggle */
    .emp-gender-group {
        display: flex;
        gap: 8px;
    }

    .emp-gender-option {
        flex: 1;
    }

    .emp-gender-option input {
        display: none;
    }

    .emp-gender-option label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px 8px;
        border: 1.5px solid var(--emp-border);
        border-radius: var(--emp-radius-sm);
        font-size: .83rem;
        font-weight: 600;
        color: var(--emp-muted);
        cursor: pointer;
        transition: all .16s ease;
    }

    .emp-gender-option input:checked+label {
        background: linear-gradient(135deg, var(--emp-gold), var(--emp-gold-dark));
        border-color: var(--emp-gold-dark);
        color: #fff;
        box-shadow: 0 4px 12px rgba(200, 151, 56, .3);
    }

    /* sponsor feedback */
    .emp-sponsor-box {
        margin-top: 10px;
        padding: 12px 14px;
        border-radius: var(--emp-radius-sm);
        font-size: .82rem;
        border: 1.5px solid var(--emp-border);
        background: #f7f8fb;
        color: var(--emp-muted);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
        transition: all .2s ease;
    }

    .emp-sponsor-box.success {
        background: #f0fdf4;
        border-color: #bbf7d0;
        color: #15803d;
    }

    .emp-sponsor-box.danger {
        background: #fef2f2;
        border-color: #fecaca;
        color: #b91c1c;
    }

    .emp-sponsor-box.info {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1d4ed8;
    }

    /* upload rows */
    .emp-file-existing {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 10px 12px;
        border-radius: var(--emp-radius-sm);
        background: #f7f8fb;
        border: 1px solid var(--emp-border);
        margin-bottom: 10px;
    }

    .emp-file-existing-left {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .emp-file-existing-left span {
        font-size: .8rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 170px;
    }

    .emp-file-view-link {
        font-size: .75rem;
        font-weight: 600;
        color: var(--emp-blue);
        padding: 5px 10px;
        border: 1px solid #bfd6fe;
        border-radius: 8px;
        text-decoration: none;
        flex-shrink: 0;
    }

    .emp-file-view-link:hover {
        background: var(--emp-blue);
        color: #fff;
    }

    .emp-file-input {
        width: 100%;
        padding: 9px 12px;
        border: 1.5px dashed var(--emp-border);
        border-radius: var(--emp-radius-sm);
        font-size: .82rem;
        background: #fafbfd;
        cursor: pointer;
    }

    .emp-profile-upload {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .emp-profile-thumb {
        width: 72px;
        height: 72px;
        border-radius: 14px;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid var(--emp-gold);
        box-shadow: var(--emp-shadow);
    }

    /* KYC / bank icon accents */
    .emp-icon-gold {
        background: rgba(200, 151, 56, .15);
        color: var(--emp-gold-dark);
    }

    .emp-icon-pink {
        background: rgba(236, 64, 122, .15);
        color: var(--emp-pink);
    }

    .emp-icon-green {
        background: rgba(16, 185, 129, .15);
        color: var(--emp-green);
    }

    .emp-icon-teal {
        background: rgba(14, 116, 144, .15);
        color: var(--emp-teal);
    }

    hr.emp-divider {
        border: none;
        border-top: 1px dashed var(--emp-border);
        margin: 20px 0;
    }

    /* sticky save panel */
    .emp-save-panel {
        position: sticky;
        top: 16px;
        background: #fff;
        border-radius: var(--emp-radius);
        box-shadow: var(--emp-shadow);
        border: 1px solid var(--emp-border);
        padding: 18px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* mobile tweaks */
    @media (max-width:575px) {
        .emp-banner {
            padding: 20px;
        }

        .emp-banner-left {
            width: 100%;
        }

        .emp-actions {
            width: 100%;
        }

        .emp-actions .emp-btn {
            flex: 1;
            justify-content: center;
        }

        .emp-topbar {
            flex-direction: column;
        }

        .emp-card-body {
            padding: 16px;
        }

        .emp-card-header {
            padding: 14px 16px;
        }
    }
</style>

<div class="emp-wrap">

    <div class="emp-topbar">
        <div>
            <nav aria-label="breadcrumb" class="emp-breadcrumb">
                <a href="<?php echo base_url('admin/dashboard'); ?>">Dashboard</a>
                <span class="sep">/</span>
                <a href="<?php echo base_url('admin/members'); ?>">Members</a>
                <span class="sep">/</span>
                <a href="<?php echo base_url('admin/members/view/' . $member->id); ?>"><?php echo htmlspecialchars($member->name); ?></a>
                <span class="sep">/</span>
                <span class="active">Edit Member</span>
            </nav>
            <h3 class="emp-title">
                <span class="emp-title-icon"><i class="fa-solid fa-user-pen"></i></span>
                Edit Member Profile
            </h3>
            <p class="emp-subtitle">Update personal info, banking details, KYC documents, and referral code/sponsor.</p>
        </div>
    </div>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="emp-alert emp-alert-error" role="alert">
            <div class="emp-alert-body">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div><?php echo $this->session->flashdata('error'); ?></div>
            </div>
            <button type="button" class="emp-alert-close" onclick="this.closest('.emp-alert').remove()">&times;</button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="emp-alert emp-alert-success" role="alert">
            <div class="emp-alert-body">
                <i class="fa-solid fa-circle-check"></i>
                <div><?php echo $this->session->flashdata('success'); ?></div>
            </div>
            <button type="button" class="emp-alert-close" onclick="this.closest('.emp-alert').remove()">&times;</button>
        </div>
    <?php endif; ?>

    <form action="<?php echo base_url('admin/members/edit/' . $member->id); ?>" method="POST" enctype="multipart/form-data" id="editMemberForm">

        <!-- Profile header banner -->
        <div class="emp-banner">
            <div class="emp-banner-left">
                <div class="emp-avatar-wrap">
                    <?php if (!empty($member->profile_image) && file_exists(FCPATH . ltrim($member->profile_image, '/'))): ?>
                        <img src="<?php echo base_url(ltrim($member->profile_image, '/')); ?>" id="headerAvatarPreview" alt="">
                    <?php else: ?>
                        <div id="headerAvatarFallback" class="emp-avatar-fallback">
                            <?php echo strtoupper(substr($member->name ?? 'M', 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <h5 class="emp-banner-name"><?php echo htmlspecialchars($member->name); ?></h5>
                    <div class="emp-banner-meta">
                        <span><i class="fa-solid fa-id-badge me-1"></i>ID: <strong><?php echo htmlspecialchars($member->custom_id ?? '-'); ?></strong></span>
                        <span class="dot">&middot;</span>
                        <span><i class="fa-solid fa-tag me-1"></i>Ref: <strong><?php echo htmlspecialchars($member->referral_code); ?></strong></span>
                        <span class="dot">&middot;</span>
                        <span><i class="fa-solid fa-wallet me-1"></i>Wallet: <strong class="emp-wallet">₹<?php echo number_format($member->wallet_balance, 2); ?></strong></span>
                    </div>
                </div>
            </div>
            <div class="emp-actions">
                <a href="<?php echo base_url('admin/members/view/' . $member->id); ?>" class="emp-btn emp-btn-outline">
                    <i class="fa-solid fa-arrow-left"></i> Back to Detail
                </a>
                <a href="<?php echo base_url('admin/members'); ?>" class="emp-btn emp-btn-dark">
                    <i class="fa-solid fa-users"></i> Member List
                </a>
                <button type="submit" class="emp-btn emp-btn-gold">
                    <i class="fa-solid fa-floppy-disk"></i> Save Changes
                </button>
            </div>
        </div>

        <div class="emp-grid">
            <!-- Left Column -->
            <div>

                <!-- Card 1: Referral & Network -->
                <div class="emp-card">
                    <div class="emp-card-header">
                        <div class="emp-card-header-left">
                            <span class="emp-card-icon emp-icon-gold"><i class="fa-solid fa-network-wired"></i></span>
                            <h6 class="emp-card-title">Referral Code & Network Assignment</h6>
                        </div>
                    </div>
                    <div class="emp-card-body">
                        <div class="emp-row">
                            <div class="emp-field">
                                <label for="custom_id" class="emp-label">Member Custom ID <span class="opt">(Sequence)</span></label>
                                <div class="emp-input-group">
                                    <span class="emp-input-icon">#</span>
                                    <input type="text" name="custom_id" id="custom_id"
                                        placeholder="0002001" value="<?php echo set_value('custom_id', $member->custom_id ?? ''); ?>">
                                </div>
                                <div class="emp-hint">Sequential Member ID used in app & invoices.</div>
                            </div>

                            <?php $has_existing_ref = !empty($member->referral_code); ?>
                            <div class="emp-field">
                                <label for="referral_code" class="emp-label">
                                    Member's Referral Code
                                    <?php if ($has_existing_ref): ?>
                                        <span class="badge" style="background:#475569; color:#fff; font-size:11px; font-weight:600; padding:2px 8px; border-radius:10px; margin-left:6px;"><i class="fa-solid fa-lock"></i> Locked</span>
                                    <?php else: ?>
                                        <span class="req">*</span>
                                    <?php endif; ?>
                                </label>
                                <div class="emp-input-group">
                                    <span class="emp-input-icon"><i class="fa-solid fa-gift"></i></span>
                                    <input type="text" name="referral_code" id="referral_code" style="text-transform:uppercase; <?php echo $has_existing_ref ? 'background-color:#f1f5f9; cursor:not-allowed; color:#475569; font-weight:600;' : ''; ?>"
                                        required placeholder="DS123456" value="<?php echo set_value('referral_code', $member->referral_code); ?>"
                                        <?php echo $has_existing_ref ? 'readonly title="Referral code cannot be changed once created"' : ''; ?>>
                                    <?php if (!$has_existing_ref): ?>
                                        <button type="button" class="emp-icon-btn" id="btnGenRefCode" title="Generate New Code">
                                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <div class="emp-hint">
                                    <?php if ($has_existing_ref): ?>
                                        <span style="color:#64748b;"><i class="fa-solid fa-shield-halved me-1"></i> Existing referral codes are permanent and cannot be modified.</span>
                                    <?php else: ?>
                                        The code this member gives to invite new downline members.
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php $has_existing_sponsor = !empty($member->parent_id); ?>
                            <div class="emp-field" style="grid-column:1 / -1;">
                                <label for="parent_sponsor" class="emp-label">
                                    Parent Sponsor (Upline)
                                    <?php if ($has_existing_sponsor): ?>
                                        <span class="badge" style="background:#475569; color:#fff; font-size:11px; font-weight:600; padding:2px 8px; border-radius:10px; margin-left:6px;"><i class="fa-solid fa-lock"></i> Permanent Sponsor (Locked)</span>
                                    <?php endif; ?>
                                </label>
                                <div class="emp-input-group">
                                    <span class="emp-input-icon"><i class="fa-solid fa-handshake"></i></span>
                                    <input type="text" name="parent_sponsor" id="parent_sponsor"
                                        placeholder="Enter sponsor Referral Code, Phone, or Custom ID (leave empty for root)"
                                        value="<?php echo set_value('parent_sponsor', $referrer ? $referrer->referral_code : ''); ?>"
                                        style="<?php echo $has_existing_sponsor ? 'background-color:#f1f5f9; cursor:not-allowed; color:#475569; font-weight:600;' : ''; ?>"
                                        <?php echo $has_existing_sponsor ? 'readonly title="Existing sponsor cannot be altered"' : ''; ?>>
                                    <?php if (!$has_existing_sponsor): ?>
                                        <button type="button" class="emp-icon-btn primary" id="btnVerifySponsor">
                                            <i class="fa-solid fa-magnifying-glass"></i> Verify
                                        </button>
                                        <button type="button" class="emp-icon-btn danger" id="btnClearSponsor" title="Clear sponsor to make root member">
                                            <i class="fa-solid fa-xmark"></i> Clear
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <div id="sponsorFeedback" class="emp-sponsor-box <?php echo $referrer ? 'success' : ''; ?>">
                                    <?php if ($referrer): ?>
                                        <div>
                                            <i class="fa-solid fa-circle-check me-1"></i>
                                            Current Sponsor: <strong><?php echo htmlspecialchars($referrer->name); ?></strong>
                                            (<code><?php echo htmlspecialchars($referrer->referral_code); ?></code>)
                                        </div>
                                        <div>Phone: <?php echo htmlspecialchars($referrer->phone); ?></div>
                                    <?php else: ?>
                                        <div><i class="fa-solid fa-info-circle me-1"></i> No sponsor currently assigned (Root Member). Admin can assign a sponsor.</div>
                                    <?php endif; ?>
                                </div>
                                <div class="emp-hint">
                                    <?php if ($has_existing_sponsor): ?>
                                        <span style="color:#64748b;"><i class="fa-solid fa-shield-halved me-1"></i> Sponsor is permanently assigned and cannot be changed.</span>
                                    <?php else: ?>
                                        No sponsor assigned yet. Admin can assign an upline sponsor.
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="emp-field">
                                <label for="status" class="emp-label">Account Status <span class="req">*</span></label>
                                <div class="emp-input-group emp-select">
                                    <select name="status" id="status">
                                        <option value="1" <?php echo set_select('status', '1', (int)$member->status === 1); ?>>Active Account</option>
                                        <option value="0" <?php echo set_select('status', '0', (int)$member->status === 0); ?>>Blocked / Suspended</option>
                                    </select>
                                </div>
                            </div>

                            <div class="emp-field">
                                <label for="is_profile_active" class="emp-label">KYC Verification Status <span class="req">*</span></label>
                                <div class="emp-input-group emp-select">
                                    <select name="is_profile_active" id="is_profile_active">
                                        <option value="1" <?php echo set_select('is_profile_active', '1', (int)$member->is_profile_active === 1); ?>>Verified & Approved</option>
                                        <option value="0" <?php echo set_select('is_profile_active', '0', (int)$member->is_profile_active === 0); ?>>Pending Admin Review</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Personal Information -->
                <div class="emp-card">
                    <div class="emp-card-header">
                        <div class="emp-card-header-left">
                            <span class="emp-card-icon emp-icon-pink"><i class="fa-solid fa-user"></i></span>
                            <h6 class="emp-card-title">Personal Information</h6>
                        </div>
                    </div>
                    <div class="emp-card-body">
                        <div class="emp-row" style="margin-bottom:18px;">
                            <div class="emp-field">
                                <label for="name" class="emp-label">Full Name <span class="req">*</span></label>
                                <div class="emp-input-group">
                                    <span class="emp-input-icon"><i class="fa-regular fa-user"></i></span>
                                    <input type="text" name="name" id="name" required placeholder="Full Name"
                                        value="<?php echo set_value('name', $member->name); ?>">
                                </div>
                            </div>

                            <div class="emp-field">
                                <label for="phone" class="emp-label">Phone Number <span class="req">*</span></label>
                                <div class="emp-input-group">
                                    <span class="emp-input-icon"><i class="fa-solid fa-phone"></i></span>
                                    <input type="tel" name="phone" id="phone" required placeholder="10-digit mobile"
                                        value="<?php echo set_value('phone', $member->phone); ?>">
                                </div>
                            </div>

                            <div class="emp-field">
                                <label for="email" class="emp-label">Email Address</label>
                                <div class="emp-input-group">
                                    <span class="emp-input-icon"><i class="fa-regular fa-envelope"></i></span>
                                    <input type="email" name="email" id="email" placeholder="user@example.com"
                                        value="<?php echo set_value('email', $member->email ?? ''); ?>">
                                </div>
                            </div>

                            <div class="emp-field">
                                <label class="emp-label">Gender</label>
                                <?php $current_gender = strtolower($member->gender ?? ''); ?>
                                <div class="emp-gender-group">
                                    <div class="emp-gender-option">
                                        <input type="radio" name="gender" id="gender_male" value="male" <?php echo ($current_gender === 'male') ? 'checked' : ''; ?>>
                                        <label for="gender_male"><i class="fa-solid fa-mars"></i> Male</label>
                                    </div>
                                    <div class="emp-gender-option">
                                        <input type="radio" name="gender" id="gender_female" value="female" <?php echo ($current_gender === 'female') ? 'checked' : ''; ?>>
                                        <label for="gender_female"><i class="fa-solid fa-venus"></i> Female</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="emp-field" style="margin-bottom:18px;">
                            <label class="emp-label">Profile Image</label>
                            <div class="emp-profile-upload">
                                <?php if (!empty($member->profile_image) && file_exists(FCPATH . ltrim($member->profile_image, '/'))): ?>
                                    <img src="<?php echo base_url(ltrim($member->profile_image, '/')); ?>" id="profileImagePreview" class="emp-profile-thumb" alt="">
                                <?php else: ?>
                                    <img src="https://placehold.co/100x100/1f2937/d4af37?text=Avatar" id="profileImagePreview" class="emp-profile-thumb" alt="">
                                <?php endif; ?>
                                <div style="flex:1;">
                                    <input type="file" name="profile_image" id="profileImageInput" class="emp-file-input" accept="image/*">
                                    <div class="emp-hint">Accepts JPG, PNG, WebP (Max 4MB). Leave empty to retain current avatar.</div>
                                </div>
                            </div>
                        </div>

                        <div class="emp-field">
                            <label for="address" class="emp-label">Complete Address</label>
                            <textarea name="address" id="address" class="emp-textarea" rows="3" placeholder="Street address, city, state, pincode..."><?php echo set_value('address', $member->address ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column -->
            <div>

                <!-- Card 3: KYC & Identity -->
                <div class="emp-card">
                    <div class="emp-card-header">
                        <div class="emp-card-header-left">
                            <span class="emp-card-icon emp-icon-green"><i class="fa-solid fa-id-card"></i></span>
                            <h6 class="emp-card-title">KYC & Identity</h6>
                        </div>
                        <span class="emp-badge-soft">Profile: <?php echo (int)($member->profile_completion_percentage ?? 0); ?>%</span>
                    </div>
                    <div class="emp-card-body">
                        <div>
                            <label for="aadhar_number" class="emp-label">Aadhaar Number</label>
                            <div class="emp-input-group" style="margin-bottom:12px;">
                                <span class="emp-input-icon"><i class="fa-regular fa-id-card"></i></span>
                                <input type="text" name="aadhar_number" id="aadhar_number"
                                    placeholder="12-digit Aadhaar" value="<?php echo set_value('aadhar_number', $member->aadhar_number ?? ''); ?>" maxlength="16">
                            </div>

                            <label class="emp-label" style="font-weight:500;color:var(--emp-muted);">Aadhaar Card Document</label>
                            <?php if (!empty($member->aadhar_image)): ?>
                                <?php $is_pdf = strtolower(pathinfo($member->aadhar_image, PATHINFO_EXTENSION)) === 'pdf'; ?>
                                <div class="emp-file-existing">
                                    <div class="emp-file-existing-left">
                                        <i class="fa-solid <?php echo $is_pdf ? 'fa-file-pdf' : 'fa-image'; ?>" style="color:<?php echo $is_pdf ? '#ef4444' : '#3b82f6'; ?>;"></i>
                                        <span><?php echo basename($member->aadhar_image); ?></span>
                                    </div>
                                    <a href="<?php echo base_url(ltrim($member->aadhar_image, '/')); ?>" target="_blank" class="emp-file-view-link">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i> View
                                    </a>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="aadhar_image" class="emp-file-input" accept="image/*,.pdf">
                            <div class="emp-hint">Upload new file to replace (JPG, PNG, PDF max 5MB).</div>
                        </div>

                        <hr class="emp-divider">

                        <div>
                            <label for="pan_number" class="emp-label">PAN Number</label>
                            <div class="emp-input-group" style="margin-bottom:12px;">
                                <span class="emp-input-icon"><i class="fa-solid fa-credit-card"></i></span>
                                <input type="text" name="pan_number" id="pan_number" style="text-transform:uppercase;"
                                    placeholder="10-digit PAN" value="<?php echo set_value('pan_number', $member->pan_number ?? ''); ?>" maxlength="10">
                            </div>

                            <label class="emp-label" style="font-weight:500;color:var(--emp-muted);">PAN Card Document</label>
                            <?php if (!empty($member->pan_image)): ?>
                                <?php $is_pdf = strtolower(pathinfo($member->pan_image, PATHINFO_EXTENSION)) === 'pdf'; ?>
                                <div class="emp-file-existing">
                                    <div class="emp-file-existing-left">
                                        <i class="fa-solid <?php echo $is_pdf ? 'fa-file-pdf' : 'fa-image'; ?>" style="color:#ef4444;"></i>
                                        <span><?php echo basename($member->pan_image); ?></span>
                                    </div>
                                    <a href="<?php echo base_url(ltrim($member->pan_image, '/')); ?>" target="_blank" class="emp-file-view-link">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i> View
                                    </a>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="pan_image" class="emp-file-input" accept="image/*,.pdf">
                            <div class="emp-hint">Upload new file to replace (JPG, PNG, PDF max 5MB).</div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Bank Account Details -->
                <div class="emp-card">
                    <div class="emp-card-header">
                        <div class="emp-card-header-left">
                            <span class="emp-card-icon emp-icon-teal"><i class="fa-solid fa-building-columns"></i></span>
                            <h6 class="emp-card-title">Bank Account Details</h6>
                        </div>
                    </div>
                    <div class="emp-card-body">
                        <div class="emp-row emp-row-1" style="margin-bottom:18px;gap:16px;">
                            <div class="emp-field">
                                <label for="account_holder_name" class="emp-label">Account Holder Name</label>
                                <div class="emp-input-group plain">
                                    <input type="text" name="account_holder_name" id="account_holder_name"
                                        placeholder="Name as per bank records" value="<?php echo set_value('account_holder_name', $member->account_holder_name ?? ''); ?>">
                                </div>
                            </div>

                            <div class="emp-field">
                                <label for="bank_name" class="emp-label">Bank Name</label>
                                <div class="emp-input-group plain">
                                    <input type="text" name="bank_name" id="bank_name"
                                        placeholder="e.g. State Bank of India, HDFC" value="<?php echo set_value('bank_name', $member->bank_name ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="emp-row">
                            <div class="emp-field">
                                <label for="account_number" class="emp-label">Account Number</label>
                                <div class="emp-input-group plain">
                                    <input type="text" name="account_number" id="account_number"
                                        placeholder="Account Number" value="<?php echo set_value('account_number', $member->account_number ?? ''); ?>">
                                </div>
                            </div>

                            <div class="emp-field">
                                <label for="ifsc_code" class="emp-label">IFSC Code</label>
                                <div class="emp-input-group plain">
                                    <input type="text" name="ifsc_code" id="ifsc_code" style="text-transform:uppercase;"
                                        placeholder="e.g. SBIN0001234" value="<?php echo set_value('ifsc_code', $member->ifsc_code ?? ''); ?>">
                                </div>
                            </div>

                            <div class="emp-field">
                                <label for="account_type" class="emp-label">Account Type</label>
                                <div class="emp-input-group emp-select plain">
                                    <select name="account_type" id="account_type">
                                        <option value="" <?php echo empty($member->account_type) ? 'selected' : ''; ?>>Select Type</option>
                                        <option value="Savings" <?php echo ($member->account_type === 'Savings') ? 'selected' : ''; ?>>Savings</option>
                                        <option value="Current" <?php echo ($member->account_type === 'Current') ? 'selected' : ''; ?>>Current</option>
                                    </select>
                                </div>
                            </div>

                            <div class="emp-field">
                                <label for="branch_name" class="emp-label">Branch Name</label>
                                <div class="emp-input-group plain">
                                    <input type="text" name="branch_name" id="branch_name"
                                        placeholder="Branch City/Area" value="<?php echo set_value('branch_name', $member->branch_name ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sticky Save panel -->
                <div class="emp-save-panel">
                    <button type="submit" class="emp-btn emp-btn-gold emp-btn-block emp-btn-lg">
                        <i class="fa-solid fa-circle-check"></i> Save & Apply Updates
                    </button>
                    <a href="<?php echo base_url('admin/members/view/' . $member->id); ?>" class="emp-btn emp-btn-outline emp-btn-block">
                        Cancel & Return
                    </a>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const memberId = <?php echo (int)$member->id; ?>;
        const sponsorInput = document.getElementById('parent_sponsor');
        const feedbackBox = document.getElementById('sponsorFeedback');
        const btnVerify = document.getElementById('btnVerifySponsor');
        const btnClear = document.getElementById('btnClearSponsor');
        const btnGenRef = document.getElementById('btnGenRefCode');
        const refCodeInput = document.getElementById('referral_code');

        // Profile Image live preview
        const profileImgInput = document.getElementById('profileImageInput');
        const profilePreview = document.getElementById('profileImagePreview');
        const headerAvatar = document.getElementById('headerAvatarPreview');

        if (profileImgInput) {
            profileImgInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        if (profilePreview) profilePreview.src = evt.target.result;
                        if (headerAvatar) headerAvatar.src = evt.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Generate random referral code helper
        if (btnGenRef && refCodeInput) {
            btnGenRef.addEventListener('click', function() {
                const randomSuffix = Math.floor(100000 + Math.random() * 900000);
                refCodeInput.value = 'DS' + randomSuffix;
            });
        }

        // Clear sponsor helper
        if (btnClear && sponsorInput) {
            btnClear.addEventListener('click', function() {
                sponsorInput.value = '';
                feedbackBox.className = 'emp-sponsor-box';
                feedbackBox.innerHTML = '<div><i class="fa-solid fa-info-circle me-1"></i> Sponsor cleared. This member will be set as a Root member.</div>';
            });
        }

        // Sponsor verify check
        function verifySponsor() {
            const query = sponsorInput.value.trim();
            if (!query) {
                feedbackBox.className = 'emp-sponsor-box';
                feedbackBox.innerHTML = '<div><i class="fa-solid fa-info-circle me-1"></i> No sponsor entered (Root member).</div>';
                return;
            }

            feedbackBox.className = 'emp-sponsor-box info';
            feedbackBox.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Checking sponsor validity...';

            fetch('<?php echo base_url('admin/members/ajax_check_sponsor'); ?>?query=' + encodeURIComponent(query) + '&member_id=' + memberId)
                .then(res => res.json())
                .then(data => {
                    if (data.valid && data.sponsor) {
                        feedbackBox.className = 'emp-sponsor-box success';
                        feedbackBox.innerHTML = '<div><i class="fa-solid fa-circle-check me-1"></i> Valid Sponsor: <strong>' + escapeHtml(data.sponsor.name) + '</strong> (' + escapeHtml(data.sponsor.referral_code) + ')</div>' +
                            '<div>Phone: ' + escapeHtml(data.sponsor.phone) + '</div>';
                    } else if (data.valid && !data.sponsor) {
                        feedbackBox.className = 'emp-sponsor-box';
                        feedbackBox.innerHTML = '<div><i class="fa-solid fa-info-circle me-1"></i> ' + escapeHtml(data.message) + '</div>';
                    } else {
                        feedbackBox.className = 'emp-sponsor-box danger';
                        feedbackBox.innerHTML = '<i class="fa-solid fa-circle-xmark me-1"></i> ' + escapeHtml(data.message);
                    }
                })
                .catch(() => {
                    feedbackBox.className = 'emp-sponsor-box danger';
                    feedbackBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-1"></i> Error checking sponsor.';
                });
        }

        if (btnVerify) {
            btnVerify.addEventListener('click', verifySponsor);
        }

        if (sponsorInput && !sponsorInput.readOnly) {
            let debounceTimer = null;
            sponsorInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(verifySponsor, 600);
            });
        }

        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
        }
    });
</script>