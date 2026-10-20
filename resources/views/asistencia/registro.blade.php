@extends($layout ?? 'layouts.direccion')
@section('title', 'Registro de asistencia estudiantil')
@section('module-title', 'Control de entrada y salida')
@section('content')
<style>
.atlas-asistencia{max-width:1180px;margin:0 auto;padding:26px 22px 40px;color:#183454;font-family:inherit}
.atlas-asistencia *{box-sizing:border-box}
.atlas-asistencia-header{display:flex;align-items:center;justify-content:space-between;gap:18px;flex-wrap:wrap;margin-bottom:25px}
.atlas-asistencia-eyebrow{font-size:12px;font-weight:800;letter-spacing:.09em;text-transform:uppercase;color:#3573b7;margin-bottom:8px}
.atlas-asistencia h1{font-size:clamp(23px,3vw,31px);line-height:1.25;margin:0 0 8px;font-weight:800;color:#142f52}
.atlas-asistencia-subtitle{margin:0;color:#63768b;font-size:14px;line-height:1.65}
.atlas-asistencia-clock{background:#eaf2fd;border:1px solid #c9ddf4;border-radius:12px;padding:11px 18px;font-weight:800;color:#164b86;font-size:21px;letter-spacing:.04em;font-variant-numeric:tabular-nums}
.atlas-asistencia-grid{display:grid;grid-template-columns:minmax(0,1.3fr) minmax(300px,1fr);gap:22px;align-items:start}
.atlas-asistencia-card{background:#fff;border:1px solid #e1e9f2;border-radius:17px;box-shadow:0 8px 28px rgba(23,48,83,.055);overflow:hidden}
.atlas-asistencia-card-header{padding:22px 25px 0}.atlas-asistencia-card-header h2{margin:0;font-size:17px;font-weight:800;color:#183454}.atlas-asistencia-card-header p{margin:6px 0 0;font-size:13px;color:#718095}
.atlas-asistencia-card-body{padding:23px 25px 26px}
.atlas-asistencia-switch{display:grid;grid-template-columns:1fr 1fr;gap:9px;padding:6px;background:#f0f5fb;border-radius:13px;margin-bottom:25px}
.atlas-asistencia-switch input{position:absolute;opacity:0;width:1px;height:1px}
.atlas-asistencia-switch label{display:flex;align-items:center;justify-content:center;gap:9px;min-height:49px;border:1px solid transparent;border-radius:10px;cursor:pointer;font-size:14px;font-weight:750;color:#61758c;transition:.18s}
.atlas-asistencia-switch input:checked+label{background:#17549c;border-color:#17549c;color:#fff;box-shadow:0 3px 9px rgba(19,76,145,.18)}
.atlas-asistencia-switch input:focus-visible+label{outline:3px solid #89b9ef;outline-offset:2px}
.atlas-asistencia-label{display:block;margin:0 0 9px;font-size:14px;font-weight:750;color:#2b425e}
.atlas-asistencia-input,.atlas-asistencia-textarea{display:block;width:100%;border:1px solid #cbd8e7;border-radius:10px;padding:13px 14px;font:inherit;font-size:15px;color:#183454;background:#fff;outline:none;transition:border-color .15s,box-shadow .15s}
.atlas-asistencia-input:focus,.atlas-asistencia-textarea:focus{border-color:#2773bd;box-shadow:0 0 0 3px rgba(39,115,189,.12)}
.atlas-asistencia-input::placeholder,.atlas-asistencia-textarea::placeholder{color:#94a3b8}
.atlas-asistencia-help{display:block;margin-top:8px;font-size:12px;color:#7a8ba0}
.atlas-asistencia-actions{display:flex;gap:11px;flex-wrap:wrap;margin-top:22px}
.atlas-asistencia-btn{border:1px solid transparent;border-radius:10px;padding:12px 17px;min-height:44px;display:inline-flex;align-items:center;justify-content:center;gap:9px;font:inherit;font-size:14px;font-weight:750;cursor:pointer;transition:.15s;text-decoration:none}
.atlas-asistencia-btn:disabled{opacity:.55;cursor:wait}.atlas-asistencia-btn-primary{background:#17549c;color:white}.atlas-asistencia-btn-primary:hover{background:#0f3e78}.atlas-asistencia-btn-secondary{background:#fff;border-color:#c9d7e7;color:#315777}.atlas-asistencia-btn-secondary:hover{background:#edf4fc}
.atlas-asistencia-video{display:none;width:100%;max-height:310px;object-fit:contain;margin-top:18px;border-radius:12px;background:#101827}
.atlas-asistencia-alert{display:none;margin-top:18px;border-radius:10px;padding:13px 15px;font-size:13px;line-height:1.5;font-weight:650}.atlas-asistencia-alert-success{display:block;color:#166344;background:#e8f7ee;border:1px solid #aee4c6}.atlas-asistencia-alert-error{display:block;color:#9b2931;background:#fff0f0;border:1px solid #f1b9bd}
.atlas-asistencia-table-wrap{overflow-x:auto;margin-top:2px}.atlas-asistencia-table{width:100%;border-collapse:collapse;text-align:left;font-size:13px}.atlas-asistencia-table th{padding:12px 10px;background:#f5f8fc;color:#50677f;font-weight:800;white-space:nowrap}.atlas-asistencia-table td{padding:14px 10px;border-bottom:1px solid #edf1f5;color:#38506a}.atlas-asistencia-table tr:last-child td{border-bottom:0}.atlas-asistencia-empty{text-align:center;padding:34px 12px!important;color:#8796a7!important}
.atlas-asistencia-modal[hidden]{display:none!important}.atlas-asistencia-modal{position:fixed;inset:0;z-index:9999;background:rgba(13,32,57,.63);display:flex;align-items:center;justify-content:center;padding:20px}.atlas-asistencia-modal-panel{width:100%;max-width:520px;max-height:90vh;overflow-y:auto;background:#fff;border-radius:17px;box-shadow:0 20px 60px rgba(0,0,0,.24)}.atlas-asistencia-modal-head{display:flex;justify-content:space-between;align-items:center;gap:15px;padding:22px 25px;border-bottom:1px solid #e8edf3}.atlas-asistencia-modal-head h2{font-size:19px;color:#163454;margin:0}.atlas-asistencia-modal-close{background:#f0f4f9;border:0;border-radius:9px;width:34px;height:34px;cursor:pointer;font-size:22px;color:#49627b}.atlas-asistencia-modal-body{padding:23px 25px}.atlas-asistencia-modal-body p{font-size:14px;line-height:1.65;color:#566d85;margin:0 0 18px}.atlas-asistencia-modal-footer{display:flex;justify-content:flex-end;flex-wrap:wrap;gap:10px;padding:17px 25px;border-top:1px solid #e8edf3}.atlas-asistencia-error{color:#b42330;font-size:13px;margin-top:8px}
@media(max-width:860px){.atlas-asistencia-grid{grid-template-columns:1fr}}@media(max-width:540px){.atlas-asistencia{padding:17px 12px 28px}.atlas-asistencia-card-body{padding:19px 16px}.atlas-asistencia-card-header{padding:19px 16px 0}.atlas-asistencia-actions .atlas-asistencia-btn{flex:1}.atlas-asistencia-header{align-items:flex-start}.atlas-asistencia-clock{font-size:17px}.atlas-asistencia-modal-footer .atlas-asistencia-btn{flex:1}}

</style>
<div class="atlas-asistencia">
    <header class="atlas-asistencia-header">
        <div>
            <div class="atlas-asistencia-eyebrow"><i class="bi bi-clipboard2-check"></i> Control institucional</div>
            <h1>Registro de asistencia estudiantil</h1>
            <p class="atlas-asistencia-subtitle">Entrada puntual hasta las 7:10 a. m. · Horario oficial de Guatemala</p>
        </div>
        <div class="atlas-asistencia-clock" id="reloj" aria-label="Hora actual">--:--:--</div>
    </header>
    <div class="atlas-asistencia-grid">
        <section class="atlas-asistencia-card" aria-labelledby="registro-titulo">
            <div class="atlas-asistencia-card-header">
                <h2 id="registro-titulo"><i class="bi bi-qr-code-scan"></i> Registrar movimiento</h2>
                <p>Seleccione el tipo de movimiento y escanee el código QR o ingrese el carné.</p>
            </div>
            <div class="atlas-asistencia-card-body">
                <div class="atlas-asistencia-switch" role="group" aria-label="Tipo de registro">
                    <input type="radio" name="modo" id="entrada" value="ENTRADA" checked>
                    <label for="entrada"><i class="bi bi-box-arrow-in-right"></i> Entrada</label>
                    <input type="radio" name="modo" id="salida" value="SALIDA">
                    <label for="salida"><i class="bi bi-box-arrow-right"></i> Salida</label>
                </div>
                <form id="form-registro" autocomplete="off">
                    @csrf
                    <label class="atlas-asistencia-label" for="identificador">Número de carné o lectura QR</label>
                    <input class="atlas-asistencia-input" id="identificador" name="identificador" maxlength="2048" placeholder="Escanee el QR o escriba el número de carné" required autofocus>
                    <span class="atlas-asistencia-help">Los lectores QR USB funcionan como un teclado y pueden registrar automáticamente.</span>
                    <div class="atlas-asistencia-actions">
                        <button class="atlas-asistencia-btn atlas-asistencia-btn-primary" type="submit" id="guardar"><i class="bi bi-check-circle"></i> Registrar asistencia</button>
                        <button class="atlas-asistencia-btn atlas-asistencia-btn-secondary" type="button" id="camara"><i class="bi bi-camera"></i> Escanear con cámara</button>
                    </div>
                </form>
                <video id="video" class="atlas-asistencia-video" autoplay playsinline muted></video>
                <div class="atlas-asistencia-alert" id="mensaje" role="status" aria-live="polite"></div>
            </div>
        </section>
        <section class="atlas-asistencia-card" aria-labelledby="historial-titulo">
            <div class="atlas-asistencia-card-header">
                <h2 id="historial-titulo"><i class="bi bi-clock-history"></i> Últimos registros de hoy</h2>
                <p>Movimientos de entrada y salida registrados durante la jornada.</p>
            </div>
            <div class="atlas-asistencia-card-body">
                <div class="atlas-asistencia-table-wrap">
                    <table class="atlas-asistencia-table">
                        <thead><tr><th>Estudiante</th><th>Entrada</th><th>Salida</th></tr></thead>
                        <tbody>
                        @forelse($registros as $registro)
                            <tr>
                                <td>{{ $registro->inscripcion?->estudiante?->nombre_completo ?? '—' }}</td>
                                <td>{{ $registro->hora_entrada ? \Carbon\Carbon::parse($registro->hora_entrada)->format('H:i:s') : '—' }}</td>
                                <td>{{ $registro->hora_salida ? \Carbon\Carbon::parse($registro->hora_salida)->format('H:i:s') : '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="atlas-asistencia-empty">Todavía no se han registrado movimientos.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
<div class="atlas-asistencia-modal" id="modal-justificacion" role="dialog" aria-modal="true" aria-labelledby="titulo-justificacion" hidden>
    <div class="atlas-asistencia-modal-panel">
        <div class="atlas-asistencia-modal-head"><h2 id="titulo-justificacion"><i class="bi bi-exclamation-circle"></i> Justificar llegada tarde</h2><button type="button" class="atlas-asistencia-modal-close" id="cerrar-modal" aria-label="Cerrar">&times;</button></div>
        <div class="atlas-asistencia-modal-body">
            <p id="aviso-tarde"></p>
            <label for="motivo" class="atlas-asistencia-label">Motivo de justificación</label>
            <textarea id="motivo" class="atlas-asistencia-textarea" rows="4" minlength="8" maxlength="1000" placeholder="Escriba el motivo de la tardanza"></textarea>
            <div class="atlas-asistencia-error" id="error-justificacion" role="alert"></div>
        </div>
        <div class="atlas-asistencia-modal-footer"><button type="button" class="atlas-asistencia-btn atlas-asistencia-btn-secondary" id="sin-justificacion">Sin justificación</button><button type="button" class="atlas-asistencia-btn atlas-asistencia-btn-primary" id="enviar-justificacion">Guardar justificación</button></div>
    </div>
</div>
<script>
(() => {
    const input=document.getElementById('identificador'),form=document.getElementById('form-registro'),aviso=document.getElementById('mensaje');
    const token=form.querySelector('input[name="_token"]').value;
    const registroUrl=@json(route('direccion.asistencia.registrar'));
    const justificarUrl=@json(route('direccion.asistencia.justificar'));
    const modal=document.getElementById('modal-justificacion');
    let registroId=null,stream=null,activo=false;
    function mensaje(texto,error=false){aviso.style.display='block';aviso.className='atlas-asistencia-alert '+(error?'atlas-asistencia-alert-error':'atlas-asistencia-alert-success');aviso.textContent=texto}
    function abrirModal(){modal.hidden=false;document.getElementById('motivo').focus()}
    function cerrarModal(){modal.hidden=true;document.getElementById('error-justificacion').textContent='';input.focus()}
    document.getElementById('cerrar-modal').addEventListener('click',cerrarModal);
    document.getElementById('sin-justificacion').addEventListener('click',cerrarModal);
    document.addEventListener('keydown',e=>{if(e.key==='Escape'&&!modal.hidden)cerrarModal()});
    async function enviar(url,body){const r=await fetch(url,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':token},body:JSON.stringify(body)});const json=await r.json();if(!r.ok)throw new Error(Object.values(json.errors||{}).flat()[0]||json.message||'No se pudo registrar');return json}
    form.addEventListener('submit',async e=>{
        e.preventDefault();const valor=input.value.trim();if(!valor)return;
        const boton=document.getElementById('guardar');boton.disabled=true;
        try{const r=await enviar(registroUrl,{modo:document.querySelector('input[name="modo"]:checked').value,identificador:valor});mensaje(r.estudiante+' — '+r.tipo+' registrada a las '+r.hora+(r.tarde&&r.tipo==='ENTRADA'?' (TARDE)':''));input.value='';if(r.tipo==='ENTRADA'&&r.tarde){registroId=r.registro_id;document.getElementById('aviso-tarde').textContent=r.estudiante+' ingresó a las '+r.hora+'. Puede justificar la tardanza o conservar el estado TARDE.';document.getElementById('motivo').value='';abrirModal()}}
        catch(err){mensaje(err.message,true)}finally{boton.disabled=false;input.focus()}
    });
    document.getElementById('enviar-justificacion').addEventListener('click',async()=>{const boton=document.getElementById('enviar-justificacion');boton.disabled=true;try{await enviar(justificarUrl,{registro_id:registroId,motivo:document.getElementById('motivo').value});cerrarModal();mensaje('Tardanza justificada correctamente. La hora real permanece registrada.')}catch(err){document.getElementById('error-justificacion').textContent=err.message}finally{boton.disabled=false}});
    function detener(){activo=false;if(stream)stream.getTracks().forEach(t=>t.stop());stream=null;document.getElementById('video').style.display='none'}
    document.getElementById('camara').addEventListener('click',async()=>{
        if(stream){detener();return}if(!('BarcodeDetector' in window)){mensaje('Este navegador no permite lectura QR por cámara. Use un lector QR USB o escriba el carné.',true);return}
        try{stream=await navigator.mediaDevices.getUserMedia({video:{facingMode:'environment'}});const video=document.getElementById('video');video.srcObject=stream;video.style.display='block';activo=true;const detector=new BarcodeDetector({formats:['qr_code']});const leer=async()=>{if(!activo)return;try{const codigos=await detector.detect(video);if(codigos.length){input.value=codigos[0].rawValue;detener();form.requestSubmit();return}}catch(e){}requestAnimationFrame(leer)};video.addEventListener('loadeddata',leer,{once:true})}catch(e){mensaje('No se pudo abrir la cámara. Verifique permisos y conexión HTTPS.',true)}
    });
    window.addEventListener('pagehide',detener);
    const reloj=document.getElementById('reloj');function actualizarHora(){reloj.textContent=new Intl.DateTimeFormat('es-GT',{timeZone:'America/Guatemala',hour:'2-digit',minute:'2-digit',second:'2-digit',hour12:false}).format(new Date())}actualizarHora();setInterval(actualizarHora,1000);input.focus();
})();
</script>
@endsection
