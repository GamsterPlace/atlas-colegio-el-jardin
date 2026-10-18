@extends('layouts.atlas')
@section('title', 'Notificaciones')
@section('role', 'ATLAS')
@section('module-title', 'Notificaciones internas')
@section('dashboard-url', route('dashboard'))

@section('content')
<style>
.atlas-notificaciones{max-width:1040px;margin:0 auto;padding:28px 20px;color:#18304d}
.atlas-notificaciones__volver{display:inline-flex;align-items:center;gap:9px;margin-bottom:22px;padding:10px 16px;border:1px solid #d5e2ed;border-radius:9px;background:#fff;color:#175e98;font-size:14px;font-weight:700;text-decoration:none;transition:background .2s,border-color .2s}
.atlas-notificaciones__volver:hover{background:#edf5fc;border-color:#1769a9;color:#104a78}
.atlas-notificaciones__head{display:flex;justify-content:space-between;gap:18px;align-items:center;margin-bottom:22px;flex-wrap:wrap}
.atlas-notificaciones h1{font-size:1.7rem;font-weight:800;margin:0 0 6px;color:#102c4b}
.atlas-notificaciones p{color:#64778c;margin:0}
.atlas-notificaciones__btn{border:0;background:#1769a9;color:#fff;padding:11px 17px;border-radius:9px;font-weight:700;cursor:pointer}
.atlas-notificaciones__list{border:1px solid #dce6ef;border-radius:16px;overflow:hidden;background:white;box-shadow:0 8px 26px #18304d0a}
.atlas-notificaciones__item{display:flex;gap:18px;justify-content:space-between;align-items:center;padding:20px 24px;border-bottom:1px solid #e9eef4}
.atlas-notificaciones__item:last-child{border-bottom:0}
.atlas-notificaciones__item--nueva{background:#f0f7fe;border-left:4px solid #1776bb}
.atlas-notificaciones__title{font-weight:800;color:#173653;margin:0 0 5px}
.atlas-notificaciones__message{color:#50657b;line-height:1.55}
.atlas-notificaciones__date{font-size:.78rem;color:#74859a;margin-top:8px}
.atlas-notificaciones__link{display:inline-block;margin-top:8px;color:#1769a9;font-weight:700;text-decoration:none}
.atlas-notificaciones__secondary{border:1px solid #cbdceb;background:white;color:#175e98;padding:9px 12px;border-radius:8px;white-space:nowrap;cursor:pointer;font-weight:700}
.atlas-notificaciones__empty{text-align:center;padding:55px 20px;color:#63788d}
@media(max-width:650px){.atlas-notificaciones__item{flex-direction:column;align-items:flex-start}.atlas-notificaciones__item form{align-self:flex-end}}
</style>

<div class="atlas-notificaciones">
    <a href="{{ route('dashboard') }}" class="atlas-notificaciones__volver">
        <i class="bi bi-arrow-left" aria-hidden="true"></i>
        Regresar al Dashboard
    </a>

    <div class="atlas-notificaciones__head">
        <div>
            <h1>Notificaciones internas</h1>
            <p>{{ $pendientes }} notificación(es) pendiente(s) de lectura.</p>
        </div>

        @if($pendientes > 0)
            <form method="POST" action="{{ route('notificaciones.leer-todas') }}">
                @csrf
                @method('PATCH')
                <button class="atlas-notificaciones__btn" type="submit">Marcar todas como leídas</button>
            </form>
        @endif
    </div>

    <div class="atlas-notificaciones__list">
        @forelse($notificaciones as $n)
            <article class="atlas-notificaciones__item {{ $n->leida_at ? '' : 'atlas-notificaciones__item--nueva' }}">
                <div>
                    <div class="atlas-notificaciones__title">{{ $n->titulo }}</div>
                    <div class="atlas-notificaciones__message">{{ $n->mensaje }}</div>
                    <div class="atlas-notificaciones__date">
                        {{ $n->created_at?->format('d/m/Y H:i') }} · {{ $n->leida_at ? 'Leída' : 'Sin leer' }}
                    </div>
                    @if($n->enlace && str_starts_with($n->enlace, '/') && !str_starts_with($n->enlace, '//'))
                        <a class="atlas-notificaciones__link" href="{{ $n->enlace }}">Ver actividad →</a>
                    @endif
                </div>

                @unless($n->leida_at)
                    <form method="POST" action="{{ route('notificaciones.leer', $n) }}">
                        @csrf
                        @method('PATCH')
                        <button class="atlas-notificaciones__secondary" type="submit">Marcar como leída</button>
                    </form>
                @endunless
            </article>
        @empty
            <div class="atlas-notificaciones__empty">No tienes notificaciones por el momento.</div>
        @endforelse
    </div>

    <div style="margin-top:20px">{{ $notificaciones->links() }}</div>
</div>
@endsection
