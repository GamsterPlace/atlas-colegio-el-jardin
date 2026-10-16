<style>
.atlas-estudiantes{--ae-primary:#163b70;--ae-dark:#102d56;--ae-border:#e2e9f2;--ae-muted:#718096;color:#24364d;width:100%;font-size:14px}
.atlas-estudiantes *{box-sizing:border-box}
.atlas-estudiantes h2.h4{font-size:24px;font-weight:750;letter-spacing:-.45px;color:var(--ae-dark);margin:0 0 7px}
.atlas-estudiantes .text-muted{color:var(--ae-muted)!important}
.atlas-estudiantes .card{background:#fff;border:1px solid var(--ae-border);border-radius:13px;box-shadow:0 3px 16px rgba(24,49,85,.045)!important;overflow:hidden}
.atlas-estudiantes .card-body{padding:23px}
.atlas-estudiantes .card-header{background:#f9fbfe!important;padding:17px 22px;border-bottom:1px solid var(--ae-border);font-size:15px;color:var(--ae-dark)}
.atlas-estudiantes .card-footer{padding:14px 22px;border-top:1px solid var(--ae-border);background:#fff!important}
.atlas-estudiantes .form-label{display:block;font-size:13px;font-weight:650;color:#344861;margin-bottom:7px}
.atlas-estudiantes .form-control,.atlas-estudiantes .form-select{width:100%;min-height:43px;padding:10px 13px;border:1px solid #d6dfeb;border-radius:8px;background:#fff;color:#24364d;font:inherit;outline:none}
.atlas-estudiantes .form-control:focus,.atlas-estudiantes .form-select:focus{border-color:#5489c8;box-shadow:0 0 0 3px rgba(50,111,184,.12)}
.atlas-estudiantes .btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;min-height:40px;padding:9px 16px;border-radius:8px;font-size:13px;font-weight:650;line-height:1.3;text-decoration:none;white-space:nowrap;transition:background .15s,border-color .15s}
.atlas-estudiantes .btn-primary{background:var(--ae-primary);border:1px solid var(--ae-primary);color:#fff}
.atlas-estudiantes .btn-primary:hover{background:var(--ae-dark);color:#fff}
.atlas-estudiantes .btn-outline-secondary,.atlas-estudiantes .btn-outline-primary{background:#fff;border:1px solid #d4dfed;color:var(--ae-primary)}
.atlas-estudiantes .btn-outline-secondary:hover,.atlas-estudiantes .btn-outline-primary:hover{background:#eef4fb;color:var(--ae-dark)}
.atlas-estudiantes .btn-sm{min-height:34px;padding:7px 12px}
.atlas-estudiantes .table-responsive{width:100%;overflow-x:auto}
.atlas-estudiantes .table{width:100%;border-collapse:collapse;margin:0;font-size:13px}
.atlas-estudiantes .table thead,.atlas-estudiantes .table-light{background:#f5f8fc}
.atlas-estudiantes .table th{padding:14px 16px;color:#4d6078;font-weight:700;text-align:left;white-space:nowrap;border-bottom:1px solid var(--ae-border)}
.atlas-estudiantes .table td{padding:14px 16px;border-bottom:1px solid #edf1f6;vertical-align:middle}
.atlas-estudiantes .table tbody tr:last-child td{border-bottom:0}
.atlas-estudiantes .table-hover tbody tr:hover{background:#f9fbfe}
.atlas-estudiantes .badge{display:inline-flex;padding:6px 11px;border-radius:30px;font-size:11px;font-weight:700;letter-spacing:.15px}
.atlas-estudiantes .badge.bg-success{background:#e5f6ed!important;color:#187447!important}
.atlas-estudiantes .badge.bg-secondary{background:#eef1f5!important;color:#596779!important}
.atlas-estudiantes dl{display:grid;grid-template-columns:minmax(130px,38%) minmax(0,1fr);gap:0;margin:0}
.atlas-estudiantes dl dt,.atlas-estudiantes dl dd{margin:0;padding:10px 7px;border-bottom:1px solid #edf1f6;overflow-wrap:anywhere}
.atlas-estudiantes dl dt{color:#63758c;font-size:12px;font-weight:650}
.atlas-estudiantes dl dd{color:#24364d;font-weight:500}
.atlas-estudiantes .display-6{font-size:36px;color:var(--ae-primary);font-weight:750}
.atlas-estudiantes .pagination{display:flex;flex-wrap:wrap;gap:5px;margin:0;padding:0;list-style:none}
.atlas-estudiantes .page-link{display:block;padding:7px 11px;border:1px solid var(--ae-border);border-radius:7px;color:var(--ae-primary);text-decoration:none}
.atlas-estudiantes .page-item.active .page-link{background:var(--ae-primary);color:#fff}
.atlas-estudiantes .page-item.disabled .page-link{color:#a4b0c0}
@media(max-width:768px){.atlas-estudiantes h2.h4{font-size:21px}.atlas-estudiantes .card-body{padding:17px}.atlas-estudiantes .card-header{padding:15px 17px}.atlas-estudiantes .table th,.atlas-estudiantes .table td{padding:12px}.atlas-estudiantes .btn{white-space:normal}.atlas-estudiantes dl{grid-template-columns:40% 60%}}

/* Composición independiente de Bootstrap para evitar filtros apilados. */
.atlas-estudiantes .ae-page-heading{display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;margin-bottom:24px}
.atlas-estudiantes .ae-page-heading-copy{min-width:0}
.atlas-estudiantes .ae-page-heading-copy p{margin:8px 0 0;line-height:1.5}
.atlas-estudiantes .ae-filter-card{margin-bottom:24px!important}
.atlas-estudiantes .ae-filter-form{display:grid;grid-template-columns:minmax(240px,2.2fr) minmax(150px,1fr) minmax(180px,1.2fr) auto;gap:18px;align-items:end}
.atlas-estudiantes .ae-filter-field{min-width:0;display:flex;flex-direction:column;gap:0}
.atlas-estudiantes .ae-filter-field .form-label{margin:0 0 9px;line-height:1.3}
.atlas-estudiantes .ae-filter-field .form-control,.atlas-estudiantes .ae-filter-field .form-select{height:44px;margin:0}
.atlas-estudiantes .ae-filter-actions{display:flex;gap:9px;align-items:end;white-space:nowrap}
.atlas-estudiantes .ae-filter-actions .btn{min-height:44px}
.atlas-estudiantes .ae-students-table-heading{display:flex!important;align-items:center;justify-content:space-between!important;gap:16px;width:100%}
.atlas-estudiantes .ae-students-table-heading strong{display:block;min-width:0}
.atlas-estudiantes .ae-record-count{display:block;margin-left:auto;white-space:nowrap;text-align:right;font-size:14px;font-weight:400}
@media(max-width:1200px){.atlas-estudiantes .ae-filter-form{grid-template-columns:minmax(240px,2fr) minmax(150px,1fr) minmax(180px,1fr)}.atlas-estudiantes .ae-filter-actions{grid-column:1/-1;justify-content:flex-end}}
@media(max-width:750px){.atlas-estudiantes .ae-filter-form{grid-template-columns:1fr 1fr;gap:16px}.atlas-estudiantes .ae-filter-search{grid-column:1/-1}.atlas-estudiantes .ae-filter-actions{grid-column:1/-1;justify-content:flex-start}.atlas-estudiantes .ae-page-heading{align-items:flex-start}}
@media(max-width:480px){.atlas-estudiantes .ae-filter-form{grid-template-columns:1fr}.atlas-estudiantes .ae-filter-search,.atlas-estudiantes .ae-filter-actions{grid-column:auto}.atlas-estudiantes .ae-filter-actions .btn{flex:1}.atlas-estudiantes .ae-page-heading .btn{width:100%}}

/* Organización de expedientes y control estudiantil */
.atlas-estudiantes .ae-filter-form--compact{grid-template-columns:minmax(220px,1fr) auto;max-width:100%;align-items:end}
.atlas-estudiantes .ae-filter-form--compact .ae-filter-actions{grid-column:auto}
.atlas-estudiantes .ae-empty{text-align:center;color:var(--ae-muted);padding:30px 18px!important}
.atlas-estudiantes .ae-summary-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px;margin-bottom:24px}
.atlas-estudiantes .ae-stat-label{color:var(--ae-muted);font-size:13px;margin-bottom:12px}
.atlas-estudiantes .ae-stat-value{font-size:34px;line-height:1.15;font-weight:750;color:var(--ae-primary)}
.atlas-estudiantes .ae-number-pill{display:inline-flex;align-items:center;justify-content:center;min-width:38px;padding:6px 12px;border-radius:9px;background:#edf4fc;color:var(--ae-primary);font-weight:750}
.atlas-estudiantes .ae-detail-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px}
.atlas-estudiantes .ae-detail-grid>.card{min-width:0}
.atlas-estudiantes .ae-detail-full{grid-column:1/-1}
.atlas-estudiantes .ae-detail-grid .card-header{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap}
.atlas-estudiantes .ae-detail-grid .card-header i{margin-right:6px}
.atlas-estudiantes .ae-data-list{display:grid;grid-template-columns:minmax(125px,38%) minmax(0,1fr);margin:0}
.atlas-estudiantes .ae-data-list dt,.atlas-estudiantes .ae-data-list dd{padding:12px 8px;margin:0;border-bottom:1px solid #edf1f6;overflow-wrap:anywhere}
.atlas-estudiantes .ae-data-list dt{color:#63758c;font-size:12px;font-weight:650}
.atlas-estudiantes .ae-data-list dd{font-size:13px;color:#24364d}
.atlas-estudiantes .ae-restricted{font-size:11px;color:#9a651f;background:#fff3db;border-radius:20px;padding:5px 10px}
.atlas-estudiantes .ae-responsables{display:flex;flex-direction:column;gap:14px}
.atlas-estudiantes .ae-responsable{padding:14px 16px;border:1px solid var(--ae-border);border-radius:10px;background:#fbfcfe}
.atlas-estudiantes .ae-responsable strong{display:block;margin-bottom:7px}
.atlas-estudiantes .ae-meta{font-size:12px;color:var(--ae-muted);line-height:1.6}
@media(max-width:900px){.atlas-estudiantes .ae-detail-grid{grid-template-columns:1fr}}
@media(max-width:750px){.atlas-estudiantes .ae-filter-form--compact{grid-template-columns:1fr}.atlas-estudiantes .ae-filter-form--compact .ae-filter-actions{grid-column:auto}}
@media(max-width:550px){.atlas-estudiantes .ae-summary-grid{grid-template-columns:1fr}.atlas-estudiantes .ae-data-list{grid-template-columns:42% 58%}}
</style>
