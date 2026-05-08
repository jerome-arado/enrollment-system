{{--
    Include this partial inside admin/enrollment-detail.blade.php
    Usage: @include('admin.partials.documents', ['enrollment' => $enrollment])
--}}

<div class="card mt-3">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
        <h3 style="font-family:var(--font); font-size:1.05rem; font-weight:normal; color:var(--bark);">
            📁 Submitted Documents
        </h3>
        <span style="font-size:0.78rem; color:var(--stone);">
            {{ $enrollment->documents->count() }} file(s)
        </span>
    </div>

    @if($enrollment->documents->isEmpty())
        <div style="text-align:center; padding:2rem; background:var(--sand);
                    border-radius:var(--radius-sm); border:1.5px dashed var(--stone-light);">
            <p style="color:var(--stone); font-size:0.88rem;">
                No documents uploaded yet.
            </p>
        </div>
    @else
        <div style="display:flex; flex-direction:column; gap:0;">
            @foreach($enrollment->documents as $doc)
                <div style="padding:1rem 0; border-bottom:1px solid var(--clay-pale);">
                    <div style="display:flex; align-items:flex-start; gap:0.9rem; flex-wrap:wrap;">
                        {{-- Icon + info --}}
                        <div style="font-size:1.5rem; flex-shrink:0; width:32px; text-align:center;">
                            {{ $doc->icon }}
                        </div>
                        <div style="flex:1; min-width:0;">
                            <p style="font-weight:700; font-size:0.9rem; color:var(--bark);">
                                {{ $doc->label }}
                            </p>
                            <p style="font-size:0.76rem; color:var(--stone); margin-top:0.1rem;">
                                {{ $doc->original_name }}
                                &middot; {{ $doc->formatted_size }}
                                &middot; {{ $doc->created_at->format('M j, Y') }}
                            </p>
                            @if($doc->remarks)
                                <p style="font-size:0.78rem; color:var(--error); margin-top:0.25rem;">
                                    💬 {{ $doc->remarks }}
                                </p>
                            @endif
                        </div>

                        {{-- Actions (download only) --}}
                        <div style="display:flex; flex-direction:column; align-items:flex-end; gap:0.5rem; flex-shrink:0;">
                            <div style="display:flex; gap:0.4rem;">
                                <a href="{{ route('documents.download', $doc) }}"
                                   class="btn btn-outline btn-sm">
                                    ⬇ Download
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Admin status update form (remains, but without status badge) --}}
                    <details style="margin-top:0.75rem;">
                        <summary style="font-size:0.78rem; color:var(--clay); cursor:pointer;
                                        font-weight:600; letter-spacing:0.02em; list-style:none;
                                        display:inline-flex; align-items:center; gap:0.3rem;">
                            ⚙ Update document status
                        </summary>
                        <form action="{{ route('admin.document.status', $doc) }}"
                              method="POST"
                              style="margin-top:0.75rem; background:var(--sand);
                                     padding:1rem; border-radius:var(--radius-sm);
                                     border:1px solid var(--clay-pale);">
                            @csrf @method('PUT')

                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                                <div class="form-group" style="margin-bottom:0;">
                                    <label>Decision</label>
                                    <select name="status">
                                        @foreach(['pending' => 'Pending', 'approved' => 'Approve', 'rejected' => 'Reject'] as $val => $lbl)
                                            <option value="{{ $val }}" {{ $doc->status === $val ? 'selected' : '' }}>
                                                {{ $lbl }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label>Remarks (optional)</label>
                                    <input type="text" name="remarks"
                                           value="{{ $doc->remarks }}"
                                           placeholder="Note for student…">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm" style="margin-top:0.75rem;">
                                Save
                            </button>
                        </form>
                    </details>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    /* Keep badge styling if used elsewhere, but not needed here */
</style>