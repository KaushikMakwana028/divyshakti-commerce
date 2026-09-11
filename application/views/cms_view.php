<!-- Summernote Lite WYSIWYG Editor Styles & Scripts -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<style>
/* Summernote Custom Polish */
.note-editor.note-frame {
    border: 1px solid #d1d5db;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.note-editor.note-frame .note-toolbar {
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 8px;
}
.note-editor.note-frame .note-editing-area .note-editable {
    background-color: #ffffff;
    padding: 20px;
    font-family: inherit;
    font-size: 0.94rem;
    line-height: 1.7;
    color: #1f2937;
    min-height: 420px;
}
.note-editor.note-frame .note-statusbar {
    background-color: #f8fafc;
    border-top: 1px solid #e2e8f0;
}
.note-btn {
    border-radius: 6px !important;
}

/* Guidelines Sidebar Card */
.guidelines-card {
    border-radius: 12px;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 16px rgba(0,0,0,0.04);
}
.guidelines-card .guidelines-header {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #1e293b;
}
.guidelines-card .guidelines-body {
    padding: 20px;
}
.guidelines-list {
    padding-left: 18px;
    margin-bottom: 0;
    font-size: 0.88rem;
    line-height: 1.75;
    color: #475569;
}
.guidelines-list li {
    margin-bottom: 14px;
}
.guidelines-list li:last-child {
    margin-bottom: 0;
}
.guidelines-list strong {
    color: #0f172a;
}
</style>

<div class="row mb-4 align-items-center">
    <div class="col-sm-7">
        <h3 class="fw-bold" style="color: var(--dark-sidebar);">Legal &amp; Policy Pages CMS</h3>
        <p class="text-muted mb-0">Visually update Privacy Policy and Terms &amp; Conditions without writing any code.</p>
    </div>
    <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
        <div class="d-inline-flex gap-2">
            <a href="<?php echo base_url('privacy_policy'); ?>" target="_blank" class="btn btn-sm btn-outline-dark px-3 py-2" style="border-radius: 8px;">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Public Privacy
            </a>
            <a href="<?php echo base_url('terms_conditions'); ?>" target="_blank" class="btn btn-sm btn-outline-dark px-3 py-2" style="border-radius: 8px;">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Public Terms
            </a>
            <a href="<?php echo base_url('delete_account'); ?>" target="_blank" class="btn btn-sm btn-outline-danger px-3 py-2" style="border-radius: 8px;">
                <i class="fa-solid fa-user-xmark me-1"></i> Delete Account
            </a>
        </div>
    </div>
</div>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success border-0 shadow-sm p-3 mb-4 d-flex align-items-center" style="border-radius: 10px;">
        <i class="fa-solid fa-circle-check fs-5 me-2 text-success"></i>
        <div><?php echo $this->session->flashdata('success'); ?></div>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('info')): ?>
    <div class="alert alert-info border-0 shadow-sm p-3 mb-4 d-flex align-items-center" style="border-radius: 10px;">
        <i class="fa-solid fa-circle-info fs-5 me-2 text-info"></i>
        <div><?php echo $this->session->flashdata('info'); ?></div>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger border-0 shadow-sm p-3 mb-4 d-flex align-items-center" style="border-radius: 10px;">
        <i class="fa-solid fa-triangle-exclamation fs-5 me-2 text-danger"></i>
        <div><?php echo $this->session->flashdata('error'); ?></div>
    </div>
<?php endif; ?>

<!-- Tabs Navigation -->
<ul class="nav nav-pills mb-4 gap-2" id="cmsTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link <?php echo ($active_tab !== 'terms_conditions') ? 'active' : ''; ?> px-4 py-2.5 fw-semibold d-flex align-items-center gap-2" 
                id="privacy-tab" 
                data-bs-toggle="tab" 
                data-bs-target="#privacyTabPane" 
                type="button" 
                role="tab" 
                aria-controls="privacyTabPane" 
                aria-selected="<?php echo ($active_tab !== 'terms_conditions') ? 'true' : 'false'; ?>"
                style="border-radius: 8px;">
            <i class="fa-solid fa-shield-halved"></i>
            <span>Privacy Policy</span>
            <?php if ($privacy_page['is_custom']): ?>
                <span class="badge bg-success-subtle text-success border border-success-subtle ms-1" style="font-size: 0.68rem;">Custom</span>
            <?php else: ?>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-1" style="font-size: 0.68rem;">Default</span>
            <?php endif; ?>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link <?php echo ($active_tab === 'terms_conditions') ? 'active' : ''; ?> px-4 py-2.5 fw-semibold d-flex align-items-center gap-2" 
                id="terms-tab" 
                data-bs-toggle="tab" 
                data-bs-target="#termsTabPane" 
                type="button" 
                role="tab" 
                aria-controls="termsTabPane" 
                aria-selected="<?php echo ($active_tab === 'terms_conditions') ? 'true' : 'false'; ?>"
                style="border-radius: 8px;">
            <i class="fa-solid fa-scale-balanced"></i>
            <span>Terms and Conditions</span>
            <?php if ($terms_page['is_custom']): ?>
                <span class="badge bg-success-subtle text-success border border-success-subtle ms-1" style="font-size: 0.68rem;">Custom</span>
            <?php else: ?>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-1" style="font-size: 0.68rem;">Default</span>
            <?php endif; ?>
        </button>
    </li>
</ul>

<!-- Tabs Content -->
<div class="tab-content" id="cmsTabsContent">

    <!-- ================= TAB 1: PRIVACY POLICY ================= -->
    <div class="tab-pane fade <?php echo ($active_tab !== 'terms_conditions') ? 'show active' : ''; ?>" id="privacyTabPane" role="tabpanel" aria-labelledby="privacy-tab">
        <div class="row">
            <!-- Left Form Column -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                    <div class="p-3 bg-white border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-primary fs-5"></i>
                            <h5 class="fw-bold mb-0 text-dark">Privacy Policy Visual Editor</h5>
                        </div>
                        <div>
                            <?php if ($privacy_page['is_custom']): ?>
                                <span class="badge bg-success text-white px-2.5 py-1" style="border-radius: 6px;">
                                    <i class="fa-solid fa-circle-check me-1"></i> Serving Custom Content
                                </span>
                            <?php else: ?>
                                <span class="badge bg-primary text-white px-2.5 py-1" style="border-radius: 6px;">
                                    <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Serving Default Template
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form action="<?php echo base_url('admin/cms/update'); ?>" method="POST" id="formPrivacy">
                            <input type="hidden" name="slug" value="privacy_policy">

                            <div class="mb-3">
                                <label for="privacy_title" class="form-label fw-semibold text-dark small text-uppercase">Page Document Title</label>
                                <input type="text" name="title" id="privacy_title" class="form-control" value="<?php echo htmlspecialchars($privacy_page['title']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark small text-uppercase">Page Slug (Read-Only)</label>
                                <input type="text" class="form-control bg-light text-muted" value="privacy_policy" readonly>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="privacy_content" class="form-label fw-bold text-dark small text-uppercase mb-0">Content <span class="text-danger">*</span></label>
                                    <button type="button" class="btn btn-sm btn-outline-primary px-2.5 py-1" style="border-radius: 6px; font-size: 0.8rem;" onclick="loadDefaultTemplate('privacy')">
                                        <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Insert Default Template into Editor
                                    </button>
                                </div>
                                <textarea name="content" id="privacy_content"><?php echo $privacy_page['content']; ?></textarea>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3 border-top">
                                <div>
                                    <?php if ($privacy_page['is_custom']): ?>
                                        <a href="<?php echo base_url('admin/cms/reset/privacy_policy'); ?>" 
                                           class="btn btn-sm btn-outline-secondary" 
                                           onclick="return confirm('Are you sure you want to reset Privacy Policy to default content? Your custom text will be cleared and the default template will be restored.');">
                                            <i class="fa-solid fa-rotate-left me-1"></i> Reset to Default Content
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="<?php echo base_url('privacy_policy'); ?>" target="_blank" class="btn btn-sm btn-outline-dark">
                                        <i class="fa-solid fa-eye me-1"></i> Preview Live Page
                                    </a>
                                    <button type="submit" class="btn btn-sm btn-primary px-4 fw-semibold text-white" style="background: linear-gradient(135deg, var(--primary-pink), var(--primary-gold)); border: none; border-radius: 8px;">
                                        <i class="fa-solid fa-floppy-disk me-1"></i> Save &amp; Publish Privacy Policy
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Writing Guidelines Column -->
            <div class="col-lg-4">
                <div class="guidelines-card mb-4">
                    <div class="guidelines-header">
                        <i class="fa-solid fa-circle-info text-danger fs-5"></i>
                        <span>Writing Guidelines</span>
                    </div>
                    <div class="guidelines-body">
                        <p class="text-muted small mb-3">
                            Follow these step-by-step guidelines to keep your policies clear, professional, and visually consistent with your customer application interface:
                        </p>
                        <ol class="guidelines-list">
                            <li>
                                <strong>Use Section Headings:</strong> Highlight main sections using the <strong>Header 2 (H2)</strong> format from the style dropdown. (Avoid Header 1 as the page already defines a main title at the top).
                            </li>
                            <li>
                                <strong>Keep Paragraphs Readable:</strong> Structure explanations into brief, clear paragraphs. Press <strong>Enter</strong> to create a new paragraph.
                            </li>
                            <li>
                                <strong>Format Lists Properly:</strong> Use the bulleted or numbered list options for itemized clauses (e.g., types of data collected, order steps, return conditions).
                            </li>
                            <li>
                                <strong>Emphasize Key Terms:</strong> Use <strong>Bold</strong> styling on key terms, important notices, and labels to make scanning easy.
                            </li>
                            <li>
                                <strong>Complete All Details:</strong> Double check that no placeholder text is saved. Replace company names, support email, helpline phone, and address with your actual business details.
                            </li>
                            <li>
                                <strong>1-Click Default Template:</strong> Click the <strong>"Insert Default Template"</strong> button anytime to load our professionally drafted, ready-to-use policy text.
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= TAB 2: TERMS AND CONDITIONS ================= -->
    <div class="tab-pane fade <?php echo ($active_tab === 'terms_conditions') ? 'show active' : ''; ?>" id="termsTabPane" role="tabpanel" aria-labelledby="terms-tab">
        <div class="row">
            <!-- Left Form Column -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                    <div class="p-3 bg-white border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-scale-balanced text-primary fs-5"></i>
                            <h5 class="fw-bold mb-0 text-dark">Terms &amp; Conditions Visual Editor</h5>
                        </div>
                        <div>
                            <?php if ($terms_page['is_custom']): ?>
                                <span class="badge bg-success text-white px-2.5 py-1" style="border-radius: 6px;">
                                    <i class="fa-solid fa-circle-check me-1"></i> Serving Custom Content
                                </span>
                            <?php else: ?>
                                <span class="badge bg-primary text-white px-2.5 py-1" style="border-radius: 6px;">
                                    <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Serving Default Template
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form action="<?php echo base_url('admin/cms/update'); ?>" method="POST" id="formTerms">
                            <input type="hidden" name="slug" value="terms_conditions">

                            <div class="mb-3">
                                <label for="terms_title" class="form-label fw-semibold text-dark small text-uppercase">Page Document Title</label>
                                <input type="text" name="title" id="terms_title" class="form-control" value="<?php echo htmlspecialchars($terms_page['title']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark small text-uppercase">Page Slug (Read-Only)</label>
                                <input type="text" class="form-control bg-light text-muted" value="terms_conditions" readonly>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="terms_content" class="form-label fw-bold text-dark small text-uppercase mb-0">Content <span class="text-danger">*</span></label>
                                    <button type="button" class="btn btn-sm btn-outline-primary px-2.5 py-1" style="border-radius: 6px; font-size: 0.8rem;" onclick="loadDefaultTemplate('terms')">
                                        <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Insert Default Template into Editor
                                    </button>
                                </div>
                                <textarea name="content" id="terms_content"><?php echo $terms_page['content']; ?></textarea>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3 border-top">
                                <div>
                                    <?php if ($terms_page['is_custom']): ?>
                                        <a href="<?php echo base_url('admin/cms/reset/terms_conditions'); ?>" 
                                           class="btn btn-sm btn-outline-secondary" 
                                           onclick="return confirm('Are you sure you want to reset Terms & Conditions to default content? Your custom text will be cleared and the default template will be restored.');">
                                            <i class="fa-solid fa-rotate-left me-1"></i> Reset to Default Content
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="<?php echo base_url('terms_conditions'); ?>" target="_blank" class="btn btn-sm btn-outline-dark">
                                        <i class="fa-solid fa-eye me-1"></i> Preview Live Page
                                    </a>
                                    <button type="submit" class="btn btn-sm btn-primary px-4 fw-semibold text-white" style="background: linear-gradient(135deg, var(--primary-pink), var(--primary-gold)); border: none; border-radius: 8px;">
                                        <i class="fa-solid fa-floppy-disk me-1"></i> Save &amp; Publish Terms
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Writing Guidelines Column -->
            <div class="col-lg-4">
                <div class="guidelines-card mb-4">
                    <div class="guidelines-header">
                        <i class="fa-solid fa-circle-info text-danger fs-5"></i>
                        <span>Writing Guidelines</span>
                    </div>
                    <div class="guidelines-body">
                        <p class="text-muted small mb-3">
                            Follow these step-by-step guidelines to keep your terms clear, professional, and visually consistent with your customer application interface:
                        </p>
                        <ol class="guidelines-list">
                            <li>
                                <strong>Use Section Headings:</strong> Highlight main sections using the <strong>Header 2 (H2)</strong> format from the style dropdown. (Avoid Header 1 as the page already defines a main title at the top).
                            </li>
                            <li>
                                <strong>Keep Paragraphs Readable:</strong> Structure explanations into brief, clear paragraphs. Press <strong>Enter</strong> to create a new paragraph.
                            </li>
                            <li>
                                <strong>Format Lists Properly:</strong> Use the bulleted or numbered list options for itemized clauses (e.g., wallet rules, order policies, cancellation &amp; returns).
                            </li>
                            <li>
                                <strong>Emphasize Key Terms:</strong> Use <strong>Bold</strong> styling on key terms, important notices, and labels to make scanning easy.
                            </li>
                            <li>
                                <strong>Complete All Details:</strong> Double check that no placeholder text is saved. Make sure policies regarding delivery timelines, wallet credits, and return periods match your actual operations.
                            </li>
                            <li>
                                <strong>1-Click Default Template:</strong> Click the <strong>"Insert Default Template"</strong> button anytime to load our professionally drafted, ready-to-use terms text.
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Hidden Default Content for 1-Click Injection -->
<div id="rawDefaultPrivacy" style="display: none;"><?php echo htmlspecialchars($default_privacy['content']); ?></div>
<div id="rawDefaultTerms" style="display: none;"><?php echo htmlspecialchars($default_terms['content']); ?></div>

<script>
$(document).ready(function() {
    const summernoteConfig = {
        placeholder: 'Write your policy content here visually like Microsoft Word...',
        tabsize: 2,
        height: 420,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'hr']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    };

    // Initialize Summernote for both editors
    $('#privacy_content').summernote(summernoteConfig);
    $('#terms_content').summernote(summernoteConfig);

    // Re-adjust Summernote height/width when switching Bootstrap tabs
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        const target = $(e.target).attr('data-bs-target');
        if (target === '#privacyTabPane') {
            $('#privacy_content').summernote(summernoteConfig);
        } else if (target === '#termsTabPane') {
            $('#terms_content').summernote(summernoteConfig);
        }
    });
});

function loadDefaultTemplate(type) {
    if (confirm("Load the complete default system template into the editor? Any unsaved edits will be replaced.")) {
        if (type === 'privacy') {
            const raw = document.getElementById('rawDefaultPrivacy').textContent;
            $('#privacy_content').summernote('code', raw);
        } else if (type === 'terms') {
            const raw = document.getElementById('rawDefaultTerms').textContent;
            $('#terms_content').summernote('code', raw);
        }
    }
}
</script>