<style>
    :root {
        --dsk-bg: #f4f5f9;
        --dsk-surface: #ffffff;
        --dsk-border: #e8e9ee;
        --dsk-text: #161c2d;
        --dsk-muted: #8890a3;
        --dsk-soft: #4b5266;
        --dsk-success: #0f9d58;
        --dsk-success-bg: #e8f8ee;
        --dsk-danger: #e0273d;
        --dsk-danger-bg: #fdecee;
        --dsk-radius: 16px;
        --dsk-shadow: 0 1px 2px rgba(20, 20, 40, .04), 0 6px 18px rgba(20, 20, 40, .05);
    }

    .oda,
    .oda * {
        box-sizing: border-box;
    }

    .oda {
        font-family: inherit;
        color: var(--dsk-text);
        width: 100%;
        max-width: 100%;
    }

    .oda a {
        text-decoration: none;
    }

    .oda :focus-visible {
        outline: 2px solid var(--primary-gold, #d4af37);
        outline-offset: 2px;
    }

    /* ---------- Page header ---------- */
    .oda .page-head {
        display: flex;
        flex-wrap: wrap;
        gap: .9rem;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.1rem;
    }

    .oda .page-head h1 {
        font-size: clamp(1.25rem, 1rem + 1vw, 1.6rem);
        font-weight: 800;
        margin: 0 0 .2rem;
        letter-spacing: -.01em;
    }

    .oda .page-head p {
        margin: 0;
        color: var(--dsk-muted);
        font-size: .85rem;
    }

    .oda .head-actions {
        display: flex;
        gap: .6rem;
        align-items: center;
    }

    .oda .hbtn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .45rem;
        min-height: 40px;
        padding: .6rem 1.05rem;
        border-radius: 10px;
        font-weight: 700;
        font-size: .82rem;
        line-height: 1;
        cursor: pointer;
        transition: .15s;
        white-space: nowrap;
        font-family: inherit;
    }

    .oda .hbtn.back {
        background: var(--dsk-surface);
        border: 1px solid var(--dsk-border);
        color: var(--dsk-text);
    }

    .oda .hbtn.back:hover {
        border-color: var(--dark-sidebar, #161c2d);
    }

    .oda .hbtn.del {
        background: var(--dsk-danger-bg);
        border: 1px solid #f8c9cf;
        color: var(--dsk-danger);
    }

    .oda .hbtn.del:hover {
        background: var(--dsk-danger);
        border-color: var(--dsk-danger);
        color: #fff;
    }

    /* ---------- Hero ---------- */
    .oda .hero {
        background: var(--dsk-surface);
        border: 1px solid var(--dsk-border);
        border-radius: var(--dsk-radius);
        box-shadow: var(--dsk-shadow);
        overflow: hidden;
    }

    .oda .hero-top {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem 2rem;
        align-items: center;
        justify-content: space-between;
        padding: 1.3rem 1.4rem;
        color: #fff;
        background: linear-gradient(135deg, var(--dark-sidebar, #161c2d) 0%, #252e4a 100%);
        border-bottom: 3px solid var(--primary-gold, #d4af37);
    }

    .oda .hero-id {
        display: flex;
        flex-direction: column;
        gap: .55rem;
        min-width: 0;
    }

    .oda .hero-id .row1 {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: .7rem;
    }

    .oda .hero-id h2 {
        margin: 0;
        font-size: clamp(1.4rem, 1.1rem + 1.2vw, 1.9rem);
        font-weight: 800;
        letter-spacing: -.01em;
        color: #fff;
    }

    .oda .hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: .4rem 1.1rem;
        font-size: .8rem;
        color: rgba(255, 255, 255, .72);
    }

    .oda .hero-meta span {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
    }

    .oda .hero-meta i {
        color: var(--primary-gold, #d4af37);
        font-size: .78rem;
    }

    .oda .hero-total {
        text-align: right;
    }

    .oda .hero-total .lbl {
        display: block;
        font-size: .7rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: rgba(255, 255, 255, .6);
        font-weight: 700;
        margin-bottom: .2rem;
    }

    .oda .hero-total .amt {
        display: block;
        font-size: clamp(1.7rem, 1.3rem + 1.6vw, 2.35rem);
        font-weight: 800;
        line-height: 1.1;
        color: var(--primary-gold, #d4af37);
        white-space: nowrap;
    }

    .oda .hero-total .sub {
        display: block;
        font-size: .76rem;
        color: rgba(255, 255, 255, .6);
        margin-top: .2rem;
    }

    .oda .status-pill {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .3rem .8rem;
        border-radius: 100px;
        font-weight: 700;
        font-size: .76rem;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .oda .st-pending {
        background: #fffbeb;
        color: #b45309;
        border-color: #fcd34d;
    }

    .oda .st-placed {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }

    .oda .st-confirmed {
        background: #eef2ff;
        color: #4338ca;
        border-color: #c7d2fe;
    }

    .oda .st-packed {
        background: #ecfeff;
        color: #0e7490;
        border-color: #a5f3fc;
    }

    .oda .st-out {
        background: #fff7ed;
        color: #c2410c;
        border-color: #fed7aa;
    }

    .oda .st-delivered {
        background: #f0fdf4;
        color: #15803d;
        border-color: #bbf7d0;
    }

    .oda .st-completed {
        background: #f0fdfa;
        color: #0f766e;
        border-color: #99f6e4;
    }

    .oda .st-cancelled {
        background: #fef2f2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    /* ---------- Tracker ---------- */
    .oda .tracker {
        display: flex;
        padding: 1.35rem 1rem 1.2rem;
    }

    .oda .tstep {
        flex: 1;
        position: relative;
        text-align: center;
        min-width: 0;
    }

    .oda .tstep::before {
        content: '';
        position: absolute;
        top: 17px;
        right: 50%;
        width: 100%;
        height: 3px;
        background: var(--dsk-border);
        z-index: 0;
    }

    .oda .tstep:first-child::before {
        display: none;
    }

    .oda .tstep.done::before,
    .oda .tstep.current::before {
        background: var(--primary-gold, #d4af37);
    }

    .oda .tdot {
        position: relative;
        z-index: 1;
        width: 36px;
        height: 36px;
        margin: 0 auto .5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--dsk-surface);
        border: 2px solid var(--dsk-border);
        color: var(--dsk-muted);
        font-size: .8rem;
    }

    .oda .tstep.done .tdot {
        background: var(--primary-gold, #d4af37);
        border-color: var(--primary-gold, #d4af37);
        color: #fff;
    }

    .oda .tstep.current .tdot {
        background: var(--dark-sidebar, #161c2d);
        border-color: var(--primary-gold, #d4af37);
        color: var(--primary-gold, #d4af37);
        box-shadow: 0 0 0 5px rgba(212, 175, 55, .22);
    }

    .oda .tlabel {
        font-size: .74rem;
        font-weight: 700;
        color: var(--dsk-muted);
        padding: 0 .15rem;
        line-height: 1.25;
    }

    .oda .tstep.done .tlabel,
    .oda .tstep.current .tlabel {
        color: var(--dsk-text);
    }

    .oda .banner {
        display: flex;
        align-items: center;
        gap: .65rem;
        margin: 1rem 1.2rem;
        padding: .85rem 1rem;
        border-radius: 12px;
        font-size: .84rem;
        font-weight: 600;
        line-height: 1.45;
    }

    .oda .banner.warn {
        background: #fef3c7;
        border: 1px solid #f59e0b;
        color: #92400e;
    }

    .oda .banner.cancel {
        background: var(--dsk-danger-bg);
        border: 1px solid #f8c9cf;
        color: #b91c1c;
    }

    /* ---------- Layout ---------- */
    .oda .layout {
        display: grid;
        grid-template-columns: minmax(0, 1.6fr) minmax(0, 1fr);
        gap: 1rem;
        margin-top: 1rem;
        align-items: start;
    }

    .oda .col-main,
    .oda .col-side {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        min-width: 0;
    }

    .oda .col-side {
        position: sticky;
        top: 1rem;
    }

    /* ---------- Cards ---------- */
    .oda .card {
        background: var(--dsk-surface);
        border-radius: var(--dsk-radius);
        border: 1px solid var(--dsk-border);
        overflow: hidden;
        box-shadow: var(--dsk-shadow);
        min-width: 0;
    }

    .oda .card-head {
        display: flex;
        align-items: center;
        gap: .65rem;
        padding: .85rem 1.2rem;
        border-bottom: 1px solid var(--dsk-border);
        background: #fafbfc;
    }

    .oda .card-head .ico {
        width: 30px;
        height: 30px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--dark-sidebar, #161c2d);
        color: var(--primary-gold, #d4af37);
        font-size: .8rem;
        flex-shrink: 0;
    }

    .oda .card-head h3 {
        font-size: .92rem;
        font-weight: 800;
        margin: 0;
        color: var(--dsk-text);
    }

    .oda .card-head .count {
        margin-left: auto;
        background: var(--dark-sidebar, #161c2d);
        color: #fff;
        font-size: .72rem;
        font-weight: 700;
        padding: .15rem .65rem;
        border-radius: 100px;
    }

    .oda .card-body {
        padding: 1.2rem;
    }

    /* ---------- Fields ---------- */
    .oda .field {
        padding: .7rem 0;
        border-bottom: 1px dashed var(--dsk-border);
    }

    .oda .field:first-child {
        padding-top: 0;
    }

    .oda .field:last-child {
        padding-bottom: 0;
        border-bottom: none;
    }

    .oda .field .lbl {
        display: block;
        font-size: .68rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--dsk-muted);
        margin-bottom: .2rem;
        font-weight: 700;
    }

    .oda .field .val {
        display: block;
        font-size: .92rem;
        font-weight: 600;
        color: var(--dsk-text);
        word-break: break-word;
        line-height: 1.55;
    }

    .oda .field .val.sub {
        font-weight: 500;
        color: var(--dsk-soft);
    }

    .oda a.val:hover {
        color: var(--primary-gold, #b8962e);
    }

    .oda .muted-i {
        color: var(--dsk-muted);
        font-style: italic;
        font-weight: 400;
    }

    .oda .code-row {
        display: flex;
        align-items: center;
        gap: .5rem;
        flex-wrap: wrap;
    }

    .oda .code-chip {
        display: inline-block;
        font-family: 'SFMono-Regular', Consolas, monospace;
        background: #fdf1f6;
        color: var(--primary-pink, #ec4899);
        font-weight: 700;
        padding: .3rem .7rem;
        border-radius: 8px;
        font-size: .84rem;
        letter-spacing: .04em;
    }

    .oda .copy-btn {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        min-height: 32px;
        padding: .3rem .7rem;
        border-radius: 8px;
        border: 1px solid var(--dsk-border);
        background: var(--dsk-surface);
        color: var(--dsk-soft);
        font-size: .74rem;
        font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        transition: .15s;
    }

    .oda .copy-btn:hover {
        border-color: var(--dark-sidebar, #161c2d);
        color: var(--dsk-text);
    }

    /* ---------- Buyer ---------- */
    .oda .buyer-top {
        display: flex;
        align-items: center;
        gap: .9rem;
        padding-bottom: 1rem;
        margin-bottom: .3rem;
        border-bottom: 1px dashed var(--dsk-border);
    }

    .oda .avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.1rem;
        background: var(--dark-sidebar, #161c2d);
        color: var(--primary-gold, #d4af37);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, .25);
    }

    .oda .buyer-top .nm {
        font-weight: 800;
        font-size: 1rem;
        line-height: 1.3;
        word-break: break-word;
    }

    .oda .buyer-top .role {
        font-size: .76rem;
        color: var(--dsk-muted);
        font-weight: 600;
    }

    /* ---------- Action buttons ---------- */
    .oda .btn-row {
        display: flex;
        gap: .6rem;
    }

    .oda .abtn {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        border: none;
        border-radius: 12px;
        min-height: 48px;
        padding: .75rem .9rem;
        font-weight: 700;
        font-size: .86rem;
        cursor: pointer;
        transition: .15s;
        line-height: 1.2;
        font-family: inherit;
    }

    .oda .abtn:hover {
        filter: brightness(.94);
    }

    .oda .abtn:active {
        transform: scale(.98);
    }

    .oda .abtn.approve {
        background: var(--dsk-success);
        color: #fff;
    }

    .oda .abtn.pack {
        background: var(--primary-gold, #d4af37);
        color: #fff;
    }

    .oda .abtn.ship {
        background: #0dcaf0;
        color: #fff;
    }

    .oda .abtn.deliver {
        background: #198754;
        color: #fff;
    }

    .oda .abtn.cancel {
        background: transparent;
        color: var(--dsk-danger);
        border: 1px solid #f3b8c0;
    }

    .oda .abtn.cancel:hover {
        background: var(--dsk-danger-bg);
        filter: none;
    }

    .oda .abtn[disabled] {
        opacity: .6;
        pointer-events: none;
    }

    .oda .warn-note {
        display: flex;
        gap: .5rem;
        margin-top: .85rem;
        font-size: .78rem;
        line-height: 1.5;
        color: var(--dsk-muted);
    }

    .oda .warn-note i {
        margin-top: .15rem;
    }

    .oda .warn-note.info {
        color: #0d6efd;
    }

    .oda .archived-note {
        display: flex;
        align-items: center;
        gap: .55rem;
        background: var(--dsk-bg);
        border-radius: 12px;
        padding: .85rem 1rem;
        color: var(--dsk-muted);
        font-size: .86rem;
        font-weight: 600;
    }

    .oda .archived-note.ok {
        background: var(--dsk-success-bg);
        color: var(--dsk-success);
    }

    .oda .pending-note {
        display: flex;
        align-items: center;
        gap: .55rem;
        background: #fef3c7;
        border: 1px solid #f59e0b;
        color: #92400e;
        border-radius: 12px;
        padding: .75rem .95rem;
        font-size: .83rem;
        font-weight: 600;
        margin-bottom: .85rem;
    }

    /* ---------- Items ---------- */
    .oda .item-list {
        padding: .25rem 1.2rem;
    }

    .oda .item-row {
        display: grid;
        grid-template-columns: 72px minmax(0, 1fr) auto;
        gap: 1rem;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid var(--dsk-border);
    }

    .oda .item-row:last-child {
        border-bottom: none;
    }

    .oda .item-thumb {
        width: 72px;
        height: 72px;
        border-radius: 14px;
        border: 1px solid var(--dsk-border);
        overflow: hidden;
        background: var(--dsk-bg);
    }

    .oda .item-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .oda .item-info {
        min-width: 0;
    }

    .oda .item-info h4 {
        margin: 0 0 .45rem;
        font-size: .98rem;
        font-weight: 700;
        line-height: 1.3;
        word-break: break-word;
    }

    .oda .chips {
        display: flex;
        flex-wrap: wrap;
        gap: .4rem;
    }

    .oda .chip {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        background: var(--dsk-bg);
        border-radius: 8px;
        padding: .25rem .6rem;
        font-size: .76rem;
        font-weight: 600;
        color: var(--dsk-soft);
    }

    .oda .chip i {
        color: var(--primary-gold, #d4af37);
    }

    .oda .item-total {
        text-align: right;
    }

    .oda .item-total .q {
        display: block;
        font-size: .68rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--dsk-muted);
        font-weight: 700;
        margin-bottom: .15rem;
    }

    .oda .item-total .amt {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--dsk-text);
        white-space: nowrap;
    }

    .oda .totals {
        background: #fafbfc;
        border-top: 1px solid var(--dsk-border);
        padding: 1rem 1.2rem 1.15rem;
    }

    .oda .trow {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: .86rem;
        color: var(--dsk-soft);
        padding: .2rem 0;
    }

    .oda .trow.grand {
        margin-top: .55rem;
        padding-top: .85rem;
        border-top: 1px dashed #d6d9e2;
        font-size: 1rem;
        font-weight: 800;
        color: var(--dsk-text);
    }

    .oda .trow.grand .amt {
        font-size: 1.4rem;
        color: var(--dsk-success);
    }

    /* ---------- Shipping ---------- */
    .oda .ship-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.1rem 1.6rem;
    }

    .oda .ship-grid .field,
    .oda .ship-grid .field:first-child,
    .oda .ship-grid .field:last-child {
        padding: 0;
        border-bottom: none;
    }

    .oda .ship-grid .wide {
        grid-column: 1 / -1;
    }

    .oda .addr-box {
        display: flex;
        gap: .85rem;
        align-items: flex-start;
        background: var(--dsk-bg);
        border-radius: 14px;
        padding: .95rem 1rem;
    }

    .oda .addr-box .pin {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--dark-sidebar, #161c2d);
        color: var(--primary-gold, #d4af37);
        font-size: .85rem;
    }

    .oda .addr-box .txt {
        font-size: .9rem;
        line-height: 1.6;
        font-weight: 500;
        color: var(--dsk-soft);
        min-width: 0;
        word-break: break-word;
    }

    .oda .addr-box .txt strong {
        color: var(--dsk-text);
    }

    /* ---------- Responsive ---------- */
    @media (max-width: 1100px) {
        .oda .layout {
            grid-template-columns: minmax(0, 1.35fr) minmax(0, 1fr);
        }
    }

    @media (max-width: 900px) {
        .oda .layout {
            grid-template-columns: minmax(0, 1fr);
        }

        /* unwrap columns so cards can be re-ordered for phones */
        .oda .col-main,
        .oda .col-side {
            display: contents;
            position: static;
        }

        .oda .area-actions {
            order: 1;
        }

        .oda .area-items {
            order: 2;
        }

        .oda .area-buyer {
            order: 3;
        }

        .oda .area-shipping {
            order: 4;
        }
    }

    @media (max-width: 600px) {
        .oda .page-head {
            margin-bottom: .9rem;
        }

        .oda .page-head p {
            display: none;
        }

        .oda .head-actions {
            width: 100%;
        }

        .oda .head-actions .hbtn {
            flex: 1;
            min-height: 44px;
        }

        .oda .head-actions .back {
            order: 1;
        }

        .oda .head-actions .del {
            order: 2;
        }

        .oda .hero-top {
            padding: 1.1rem 1rem;
        }

        .oda .hero-total {
            width: 100%;
            text-align: left;
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .2rem .8rem;
            padding-top: .9rem;
            border-top: 1px dashed rgba(255, 255, 255, .2);
        }

        .oda .hero-total .lbl {
            margin: 0;
        }

        .oda .hero-total .sub {
            width: 100%;
            margin: 0;
        }

        .oda .tracker {
            padding: 1.1rem .35rem .95rem;
        }

        .oda .tdot {
            width: 30px;
            height: 30px;
            font-size: .72rem;
        }

        .oda .tstep::before {
            top: 14px;
        }

        .oda .tlabel {
            font-size: .62rem;
        }

        .oda .banner {
            margin: .9rem;
        }

        .oda .card-head {
            padding: .8rem 1rem;
        }

        .oda .card-body {
            padding: 1rem;
        }

        .oda .btn-row {
            flex-direction: column;
        }

        .oda .item-list {
            padding: .1rem 1rem;
        }

        .oda .item-row {
            grid-template-columns: 60px minmax(0, 1fr);
            gap: .8rem;
        }

        .oda .item-thumb {
            width: 60px;
            height: 60px;
            border-radius: 12px;
        }

        .oda .item-total {
            grid-column: 1 / -1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: left;
            background: var(--dsk-bg);
            border-radius: 10px;
            padding: .55rem .8rem;
        }

        .oda .item-total .q {
            margin: 0;
        }

        .oda .item-total .amt {
            font-size: 1rem;
        }

        .oda .totals {
            padding: .9rem 1rem 1rem;
        }

        .oda .ship-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .oda * {
            transition: none !important;
        }
    }

    @media print {

        .oda .head-actions,
        .oda .area-actions,
        .oda .copy-btn {
            display: none !important;
        }

        .oda .layout {
            grid-template-columns: 1fr;
        }

        .oda .col-side {
            position: static;
        }
    }
</style>

<?php
// ---- Presentation helpers (display only; no business logic changed) ----
$status_map = [
    'pending'          => ['st-pending',   'fa-clock',         'Awaiting Payment'],
    'placed'           => ['st-placed',    'fa-receipt',       'Placed'],
    'confirmed'        => ['st-confirmed', 'fa-circle-check',  'Confirmed'],
    'packed'           => ['st-packed',    'fa-box-open',      'Packed'],
    'out_for_delivery' => ['st-out',       'fa-truck-fast',    'Out for Delivery'],
    'delivered'        => ['st-delivered', 'fa-circle-check',  'Delivered'],
    'completed'        => ['st-completed', 'fa-award',         'Completed'],
];
$status_ui = $status_map[$order->status] ?? ['st-cancelled', 'fa-circle-xmark', 'Cancelled'];

$track_steps = [
    ['placed',           'Placed',    'fa-receipt'],
    ['confirmed',        'Confirmed', 'fa-circle-check'],
    ['packed',           'Packed',    'fa-box'],
    ['out_for_delivery', 'On the way', 'fa-truck-fast'],
    ['delivered',        'Delivered', 'fa-house-chimney-user'],
];
$track_keys = array_column($track_steps, 0);
$status_for_track = ($order->status === 'completed') ? 'delivered' : $order->status;
$track_index = array_search($status_for_track, $track_keys, true);
if ($track_index === false) {
    $track_index = -1;
}

$total_units = 0;
if (!empty($order_items)) {
    foreach ($order_items as $it) {
        $total_units += (int)$it->quantity;
    }
} else {
    $total_units = (int)($order->quantity ?? 0);
}
$items_count = !empty($order_items) ? count($order_items) : 1;

$buyer_name    = $order->buyer_name ?? 'Customer';
$buyer_initial = mb_strtoupper(mb_substr(trim($buyer_name) !== '' ? trim($buyer_name) : 'C', 0, 1));

// Single-line shipping address for the "Copy address" button
$ship_copy = '';
if (!empty($shipping_address)) {
    $parts = array_filter([
        $shipping_address->full_name ?? '',
        $shipping_address->mobile ?? '',
        $shipping_address->address_line1 ?? '',
        $shipping_address->address_line2 ?? '',
        !empty($shipping_address->landmark) ? 'Landmark: ' . $shipping_address->landmark : '',
        trim(($shipping_address->city ?? '') . ', ' . ($shipping_address->state ?? '') . ' - ' . ($shipping_address->pincode ?? '')),
        $shipping_address->country ?? '',
    ]);
    $ship_copy = implode("\n", $parts);
}
?>

<div class="oda">

    <!-- Page header -->
    <div class="page-head">
        <div>
            <h1>Order Detail Audit</h1>
            <p>Review items, buyer details and delivery progress.</p>
        </div>
        <div class="head-actions">
            <a href="<?php echo base_url('admin/orders'); ?>" class="hbtn back">
                <i class="fa-solid fa-arrow-left"></i> Back to Orders
            </a>
            <button type="button" class="hbtn del" id="deleteOrderAuditBtn" data-order-id="<?php echo $order->id; ?>" title="Delete this order">
                <i class="fa-solid fa-trash-can"></i> Delete Order
            </button>
        </div>
    </div>

    <!-- Hero + progress tracker -->
    <div class="hero">
        <div class="hero-top">
            <div class="hero-id">
                <div class="row1">
                    <?php 
                        $display_order_id = !empty($order->order_number) 
                            ? $order->order_number 
                            : ('DS' . date('Ymd', strtotime($order->created_at ?: date('Y-m-d'))) . sprintf('%04d', $order->id));
                    ?>
                    <h2>Order <?php echo htmlspecialchars($display_order_id); ?></h2>
                    <span class="status-pill <?php echo $status_ui[0]; ?>"><i class="fa-solid <?php echo $status_ui[1]; ?>"></i> <?php echo $status_ui[2]; ?></span>
                </div>
                <div class="hero-meta">
                    <span><i class="fa-regular fa-clock"></i> <?php echo date('d M Y, h:i A', strtotime($order->created_at)); ?></span>
                    <span><i class="fa-regular fa-user"></i> <?php echo htmlspecialchars($buyer_name); ?></span>
                    <span><i class="fa-solid fa-cubes"></i> <?php echo $items_count; ?> item<?php echo $items_count > 1 ? 's' : ''; ?> &middot; <?php echo $total_units; ?> unit<?php echo $total_units > 1 ? 's' : ''; ?></span>
                </div>
            </div>
            <div class="hero-total">
                <span class="lbl">Order Total</span>
                <span class="amt">&#8377;<?php echo number_format($order->amount, 2); ?></span>
                <span class="sub">Inclusive of all items</span>
            </div>
        </div>

        <?php if ($order->status === 'pending'): ?>
            <div class="banner warn"><i class="fa-solid fa-clock"></i> Awaiting buyer payment. The order enters fulfillment once payment is completed.</div>
        <?php elseif ($track_index === -1): ?>
            <div class="banner cancel"><i class="fa-solid fa-circle-xmark"></i> This order has been cancelled.</div>
        <?php else: ?>
            <div class="tracker">
                <?php foreach ($track_steps as $i => $step):
                    $cls = $i < $track_index ? 'done' : ($i === $track_index ? 'current' : '');
                    if ($i === $track_index && $track_index === count($track_steps) - 1) {
                        $cls = 'done';
                    }
                ?>
                    <div class="tstep <?php echo $cls; ?>">
                        <div class="tdot"><i class="fa-solid <?php echo ($cls === 'done') ? 'fa-check' : $step[2]; ?>"></i></div>
                        <div class="tlabel"><?php echo $step[1]; ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="layout">

        <!-- LEFT: items + shipping -->
        <div class="col-main">

            <!-- Purchase Items -->
            <div class="card area-items">
                <div class="card-head">
                    <span class="ico"><i class="fa-solid fa-basket-shopping"></i></span>
                    <h3>Purchase Items</h3>
                    <span class="count"><?php echo $items_count; ?></span>
                </div>

                <div class="item-list">
                    <?php if (!empty($order_items)): ?>
                        <?php foreach ($order_items as $item): ?>
                            <div class="item-row">
                                <div class="item-thumb">
                                    <img loading="lazy"
                                        src="<?php echo !empty($item->product_image) ? base_url($item->product_image) : 'https://placehold.co/80x80/161c2d/d4af37?text=No+Image'; ?>"
                                        onerror="this.onerror=null;this.src='https://placehold.co/80x80/161c2d/d4af37?text=No+Image';"
                                        alt="<?php echo htmlspecialchars($item->product_name ?? 'Product'); ?>">
                                </div>
                                <div class="item-info">
                                    <h4><?php echo htmlspecialchars($item->product_name ?? 'Product'); ?></h4>
                                    <div class="chips">
                                        <span class="chip"><i class="fa-solid fa-tag"></i> &#8377;<?php echo number_format($item->price, 2); ?> each</span>
                                        <span class="chip"><i class="fa-solid fa-cubes"></i> Qty: <?php echo $item->quantity; ?></span>
                                        <?php if (!empty($item->size)): ?>
                                            <span class="chip chip-size" style="background:#fef3c7; color:#92400e; border:1px solid #fde68a; font-weight:600;"><i class="fa-solid fa-shirt"></i> Size: <?php echo htmlspecialchars($item->size); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="item-total">
                                    <span class="q">Item Total</span>
                                    <span class="amt">&#8377;<?php echo number_format($item->subtotal, 2); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="item-row">
                            <div class="item-thumb">
                                <img loading="lazy"
                                    src="<?php echo $order->product_image ? base_url($order->product_image) : 'https://placehold.co/80x80/161c2d/d4af37?text=No+Image'; ?>"
                                    onerror="this.onerror=null;this.src='https://placehold.co/80x80/161c2d/d4af37?text=No+Image';"
                                    alt="<?php echo htmlspecialchars($order->product_name); ?>">
                            </div>
                            <div class="item-info">
                                <h4><?php echo htmlspecialchars($order->product_name); ?></h4>
                                <div class="chips">
                                    <span class="chip"><i class="fa-solid fa-tag"></i> &#8377;<?php echo number_format($order->product_price, 2); ?> each</span>
                                    <span class="chip"><i class="fa-solid fa-cubes"></i> Qty: <?php echo $order->quantity; ?></span>
                                    <?php if (!empty($order->size)): ?>
                                        <span class="chip chip-size" style="background:#fef3c7; color:#92400e; border:1px solid #fde68a; font-weight:600;"><i class="fa-solid fa-shirt"></i> Size: <?php echo htmlspecialchars($order->size); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="item-total">
                                <span class="q">Total</span>
                                <span class="amt">&#8377;<?php echo number_format($order->amount, 2); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="totals">
                    <div class="trow"><span>Items</span><span><?php echo $items_count; ?></span></div>
                    <div class="trow"><span>Total Units</span><span><?php echo $total_units; ?></span></div>
                    <div class="trow grand">
                        <span>Total Order Amount</span>
                        <span class="amt">&#8377;<?php echo number_format($order->amount, 2); ?></span>
                    </div>
                </div>
            </div>

            <!-- Shipping -->
            <div class="card area-shipping">
                <div class="card-head">
                    <span class="ico"><i class="fa-solid fa-truck-fast"></i></span>
                    <h3>Shipping Details</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($shipping_address)): ?>
                        <span class="muted-i">No shipping address recorded for this order.</span>
                    <?php else: ?>
                        <div class="ship-grid">
                            <div class="field">
                                <span class="lbl">Contact Person</span>
                                <span class="val"><?php echo htmlspecialchars($shipping_address->full_name ?? '-'); ?></span>
                            </div>
                            <div class="field">
                                <span class="lbl">Phone Number</span>
                                <?php if (!empty($shipping_address->mobile)): ?>
                                    <a class="val sub" href="tel:<?php echo htmlspecialchars($shipping_address->mobile); ?>"><?php echo htmlspecialchars($shipping_address->mobile); ?></a>
                                <?php else: ?>
                                    <span class="val sub">-</span>
                                <?php endif; ?>
                            </div>
                            <div class="field wide">
                                <span class="lbl">Delivery Address</span>
                                <div class="addr-box">
                                    <span class="pin"><i class="fa-solid fa-location-dot"></i></span>
                                    <div class="txt">
                                        <?php echo htmlspecialchars($shipping_address->address_line1 ?? ''); ?><br>
                                        <?php if (!empty($shipping_address->address_line2)): ?>
                                            <?php echo htmlspecialchars($shipping_address->address_line2); ?><br>
                                        <?php endif; ?>
                                        <?php if (!empty($shipping_address->landmark)): ?>
                                            Landmark: <?php echo htmlspecialchars($shipping_address->landmark); ?><br>
                                        <?php endif; ?>
                                        <strong>
                                            <?php echo htmlspecialchars($shipping_address->city ?? ''); ?>,
                                            <?php echo htmlspecialchars($shipping_address->state ?? ''); ?> -
                                            <?php echo htmlspecialchars($shipping_address->pincode ?? ''); ?>
                                        </strong>,
                                        <?php echo htmlspecialchars($shipping_address->country ?? ''); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="wide">
                                <button type="button" class="copy-btn" data-copy="<?php echo htmlspecialchars($ship_copy, ENT_QUOTES); ?>" data-copy-label="Shipping address copied">
                                    <i class="fa-regular fa-copy"></i> Copy address
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- RIGHT: actions + buyer -->
        <div class="col-side">

            <!-- Status Changer Panel -->
            <div class="card area-actions">
                <div class="card-head">
                    <span class="ico"><i class="fa-solid fa-sliders"></i></span>
                    <h3>Order Status Control</h3>
                </div>
                <div class="card-body">
                    <?php if ($order->status === 'pending'): ?>
                        <div class="pending-note"><i class="fa-solid fa-clock"></i> Awaiting buyer payment</div>
                        <div class="btn-row">
                            <button type="button" class="abtn cancel order-cancel-btn" data-order-id="<?php echo $order->id; ?>">
                                <i class="fa-solid fa-xmark"></i> Cancel Order
                            </button>
                        </div>
                        <span class="warn-note"><i class="fa-solid fa-circle-info"></i><span>Fulfillment controls unlock once the buyer completes payment.</span></span>
                    <?php elseif ($order->status === 'placed'): ?>
                        <div class="btn-row">
                            <button type="button" class="abtn approve order-status-btn" data-status="confirmed" data-order-id="<?php echo $order->id; ?>">
                                <i class="fa-solid fa-circle-check"></i> Confirm Order
                            </button>
                            <button type="button" class="abtn cancel order-cancel-btn" data-order-id="<?php echo $order->id; ?>">
                                <i class="fa-solid fa-triangle-exclamation"></i> Cancel Order
                            </button>
                        </div>
                        <span class="warn-note info"><i class="fa-solid fa-circle-check"></i><span>Paid from wallet. Confirming moves the order to Confirmed.</span></span>
                    <?php elseif ($order->status === 'confirmed'): ?>
                        <div class="btn-row">
                            <button type="button" class="abtn pack order-status-btn" data-status="packed" data-order-id="<?php echo $order->id; ?>">
                                <i class="fa-solid fa-box"></i> Pack Order
                            </button>
                            <button type="button" class="abtn cancel order-cancel-btn" data-order-id="<?php echo $order->id; ?>">
                                <i class="fa-solid fa-triangle-exclamation"></i> Cancel &amp; Refund
                            </button>
                        </div>
                        <span class="warn-note"><i class="fa-solid fa-circle-info"></i><span>Cancel &amp; Refund restores stock, refunds the buyer's balance and reverses commissions.</span></span>
                    <?php elseif ($order->status === 'packed'): ?>
                        <div class="btn-row">
                            <button type="button" class="abtn ship order-status-btn" data-status="out_for_delivery" data-order-id="<?php echo $order->id; ?>">
                                <i class="fa-solid fa-truck"></i> Out for Delivery
                            </button>
                        </div>
                        <span class="warn-note"><i class="fa-solid fa-circle-info"></i><span>Order is packed. Move it to Out for Delivery once the courier picks it up.</span></span>
                    <?php elseif ($order->status === 'out_for_delivery'): ?>
                        <div class="btn-row">
                            <button type="button" class="abtn deliver order-status-btn" data-status="delivered" data-order-id="<?php echo $order->id; ?>">
                                <i class="fa-solid fa-house-chimney-user"></i> Mark as Delivered
                            </button>
                        </div>
                        <span class="warn-note"><i class="fa-solid fa-circle-info"></i><span>Mark as Delivered once the customer receives the package.</span></span>
                    <?php elseif ($order->status === 'delivered' || $order->status === 'completed'): ?>
                        <div class="archived-note ok">
                            <i class="fa-solid fa-circle-check"></i> Order is delivered and completed.
                        </div>
                    <?php else: ?>
                        <div class="archived-note">
                            <i class="fa-solid fa-box-archive"></i> Order is cancelled and archived.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Buyer -->
            <div class="card area-buyer">
                <div class="card-head">
                    <span class="ico"><i class="fa-solid fa-user"></i></span>
                    <h3>Buyer Account</h3>
                </div>
                <div class="card-body">
                    <div class="buyer-top">
                        <div class="avatar"><?php echo htmlspecialchars($buyer_initial); ?></div>
                        <div>
                            <div class="nm"><?php echo htmlspecialchars($order->buyer_name ?? '-'); ?></div>
                            <div class="role">Buyer</div>
                        </div>
                    </div>
                    <div class="field">
                        <span class="lbl">Email Address</span>
                        <?php if (!empty($order->buyer_email)): ?>
                            <a class="val sub" href="mailto:<?php echo htmlspecialchars($order->buyer_email); ?>"><?php echo htmlspecialchars($order->buyer_email); ?></a>
                        <?php else: ?>
                            <span class="val sub"><span class="muted-i">Not provided</span></span>
                        <?php endif; ?>
                    </div>
                    <div class="field">
                        <span class="lbl">Phone Number</span>
                        <?php if (!empty($order->buyer_phone)): ?>
                            <a class="val sub" href="tel:<?php echo htmlspecialchars($order->buyer_phone); ?>"><?php echo htmlspecialchars($order->buyer_phone); ?></a>
                        <?php else: ?>
                            <span class="val sub"><span class="muted-i">Not provided</span></span>
                        <?php endif; ?>
                    </div>
                    <div class="field">
                        <span class="lbl">Sponsor Code</span>
                        <div class="code-row">
                            <span class="code-chip"><?php echo htmlspecialchars($order->buyer_ref ?? '-'); ?></span>
                            <?php if (!empty($order->buyer_ref)): ?>
                                <button type="button" class="copy-btn" data-copy="<?php echo htmlspecialchars($order->buyer_ref, ENT_QUOTES); ?>" data-copy-label="Sponsor code copied">
                                    <i class="fa-regular fa-copy"></i> Copy
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusBtns = document.querySelectorAll('.order-status-btn');
        const cancelBtns = document.querySelectorAll('.order-cancel-btn');

        // Status progression handler
        statusBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const targetStatus = this.getAttribute('data-status');
                const orderId = this.getAttribute('data-order-id');
                const statusLabel = targetStatus.replace(/_/g, ' ').toUpperCase();

                let confirmTitle = 'Advance Order Status?';
                let confirmText = 'Are you sure you want to advance this order to ' + statusLabel + '?';
                let confirmBtnText = 'Yes, Advance';
                if (targetStatus === 'delivered') {
                    confirmTitle = 'Mark Order as Delivered?';
                    confirmText = 'Marking this order as DELIVERED will complete the order fulfillment. Are you sure you want to proceed?';
                    confirmBtnText = 'Yes, Mark Delivered';
                }

                dsConfirm({
                    title: confirmTitle,
                    text: confirmText,
                    icon: 'question',
                    confirmText: confirmBtnText,
                    isDangerous: false,
                    onConfirm: function() {
                        fetch('<?php echo base_url("api/update_order_status"); ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({
                                    order_id: orderId,
                                    status: targetStatus
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data && data.status) {
                                    dsToast({
                                        icon: 'success',
                                        title: data.message || 'Order status updated successfully'
                                    });
                                    setTimeout(() => window.location.reload(), 1200);
                                } else {
                                    dsAlert({
                                        icon: 'error',
                                        title: 'Status Update Failed',
                                        text: (data && data.message) ? data.message : 'Could not update order status.'
                                    });
                                }
                            })
                            .catch(err => {
                                dsAlert({
                                    icon: 'error',
                                    title: 'Network Error',
                                    text: 'Failed to communicate with the server. Please try again.'
                                });
                            });
                    }
                });
            });
        });

        // Cancel order handler
        cancelBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const orderId = this.getAttribute('data-order-id');
                const isPaid = <?php echo !empty($is_already_paid) ? 'true' : 'false'; ?>;

                let titleStr = "Cancel Order?";
                let textStr = "Are you sure you want to cancel this order?";
                if (isPaid) {
                    titleStr = "Cancel & Refund Order?";
                    textStr = "This will refund the buyer's wallet, restore product stock, and reverse all MLM level commission payouts! This action cannot be undone.";
                } else {
                    titleStr = "Cancel Unpaid Order?";
                    textStr = "Are you sure you want to cancel this pending order? No funds have been deducted yet.";
                }

                dsConfirm({
                    title: titleStr,
                    text: textStr,
                    icon: 'warning',
                    confirmText: 'Yes, Cancel Order',
                    isDangerous: true,
                    onConfirm: function() {
                        fetch('<?php echo base_url("api/cancel_order"); ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({
                                    order_id: orderId
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data && data.status) {
                                    let toastMsg = data.message || 'Order cancelled successfully.';
                                    if (data.data && data.data.refund_issued) {
                                        toastMsg = 'Order cancelled: ₹' + (data.data.refund_amount ? Number(data.data.refund_amount).toFixed(2) : '') + ' refunded & commissions reversed.';
                                    }
                                    dsToast({
                                        icon: 'success',
                                        title: toastMsg
                                    });
                                    setTimeout(() => window.location.reload(), 1200);
                                } else {
                                    dsAlert({
                                        icon: 'error',
                                        title: 'Cancellation Failed',
                                        text: (data && data.message) ? data.message : 'Could not cancel order.'
                                    });
                                }
                            })
                            .catch(err => {
                                dsAlert({
                                    icon: 'error',
                                    title: 'Network Error',
                                    text: 'Failed to communicate with the server. Please try again.'
                                });
                            });
                    }
                });
            });
        });

        // Delete order handler
        const deleteOrderAuditBtn = document.getElementById('deleteOrderAuditBtn');
        if (deleteOrderAuditBtn) {
            deleteOrderAuditBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const orderId = this.getAttribute('data-order-id');

                dsConfirm({
                    title: 'Delete Order #' + orderId + '?',
                    text: 'Are you sure you want to permanently delete Order #' + orderId + '? All associated commissions will be removed and product inventory restored. This action cannot be undone!',
                    icon: 'warning',
                    confirmText: 'Yes, Delete Order',
                    cancelText: 'Cancel',
                    isDangerous: true,
                    onConfirm: function() {
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
                                        title: data.message || 'Order deleted successfully'
                                    });
                                    setTimeout(() => {
                                        window.location.href = '<?php echo base_url("admin/orders"); ?>';
                                    }, 1000);
                                } else {
                                    dsAlert({
                                        icon: 'error',
                                        title: 'Delete Failed',
                                        text: (data && data.message) ? data.message : 'Could not delete order.'
                                    });
                                }
                            })
                            .catch(err => {
                                dsAlert({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to communicate with server.'
                                });
                            });
                    }
                });
            });
        }

        // Copy-to-clipboard buttons (sponsor code, shipping address)
        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const text = this.getAttribute('data-copy') || '';
                const label = this.getAttribute('data-copy-label') || 'Copied';

                const done = () => {
                    if (typeof dsToast === 'function') {
                        dsToast({
                            icon: 'success',
                            title: label
                        });
                    }
                };

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(text).then(done);
                } else {
                    const ta = document.createElement('textarea');
                    ta.value = text;
                    ta.style.position = 'fixed';
                    ta.style.opacity = '0';
                    document.body.appendChild(ta);
                    ta.select();
                    try {
                        document.execCommand('copy');
                        done();
                    } catch (err) {}
                    document.body.removeChild(ta);
                }
            });
        });
    });
</script>