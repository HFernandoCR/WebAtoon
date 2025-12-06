@props(['permission', 'fallback' => null])

@if(Auth::check() && Auth::user()->can($permission))
    {{ $slot }}
@else
    @if($fallback)
        <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Acceso Restringido</strong>
            <p style="margin: 5px 0 0 0; color: #856404;">{{ $fallback }}</p>
        </div>
    @endif
@endif
