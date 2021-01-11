@if ($content === null)
Hello {{ $data['vendor']->rep }},

Please find the attached purchase order for a total of ${{ number_format($data['purchaseOrder']->total, 2) }}.

Thanks,
Walt's TV Accounting Team
@else
{{ $content }}
@endif
